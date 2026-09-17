<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SertifikatPKL; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    // Menampilkan daftar pengguna
    public function index(Request $request)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!Session::has('id_user') && !auth()->check()) {
            return redirect()->route('login');
        }

        // Query dasar
        $query = User::query();

        // Tambahkan filter pencarian jika ada
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('username', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('role', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        // Paginate hasil
        $users = $query->orderBy('id', 'desc')->paginate(10);
        
        // Pertahankan parameter pencarian saat pagination
        $users->appends($request->query());

        return view('users.index', compact('users'));
    }

    // Menampilkan form untuk membuat pengguna baru
    public function create()
    {
        if (!Session::has('id_user') && !auth()->check()) {
            return redirect()->route('login');
        }
        
        // 1. Ambil semua 'id_sertifikat' yang sudah  dipakai di tabel User.
        //    Artinya: Siapa saja yang sudah punya akun?
        $id_yg_sudah_punya_akun = User::whereNotNull('id_sertifikat')
                                      ->pluck('id_sertifikat')
                                      ->toArray();

        // 2. Ambil Data Siswa yang ID-nya TIDAK ADA di daftar nomor 1.
        //    Artinya: Hanya Memunculkan siswa yang belum terdaftar di tabel User
        $siswas = SertifikatPKL::whereNotIn('id_sertifikat', $id_yg_sudah_punya_akun)
                    ->orderBy('nama_lengkap', 'asc')
                    ->get();

        return view('users.create', compact('siswas'));
    }

    // Menyimpan pengguna baru
    public function store(Request $request)
    {
        $request->validate([
            'username'      => 'required|unique:tbl_login,username',
            'password'      => 'required|min:6',
            // Sekretariat TIDAK boleh dibuat lewat form UI.
            // Akun sekretariat hanya dibuat via: php artisan db:seed --class=SekretariatAccountSeeder
            'role'          => 'required|in:siswa,mentor',
            'id_sertifikat' => 'nullable',
        ], [
            'role.in' => 'Role tidak valid. Akun Sekretariat tidak dapat dibuat melalui form ini.',
        ]);

        // 1. Buat User Baru
        $user = User::create([
            'username'      => $request->username,
            'pass'          => Hash::make($request->password),
            'role'          => $request->role,
            'id_sertifikat' => $request->role === 'siswa' ? $request->id_sertifikat : null,
        ]);

        // 2. Sambungkan ke Data Siswa (Update created_id di tabel Sertifikat)
        if ($request->role === 'siswa' && $request->filled('id_sertifikat')) {

            $siswa = SertifikatPKL::find($request->id_sertifikat);

            if ($siswa) {
                $siswa->update(['created_id' => $user->id]);

                return redirect()->route('user.index')
                    ->with('success', "User berhasil dibuat dan disambungkan ke siswa: {$siswa->nama_lengkap}");
            }
        }

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan.');
    }

    // Menampilkan form edit
    public function edit($id)
    {
        if (!Session::has('id_user') && !auth()->check()) {
            return redirect()->route('login');
        }

        $user = User::findOrFail($id);

        return view('users.edit', compact('user'));
    }

    // Mengupdate data pengguna
    public function update(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|unique:tbl_login,username,' . $id,
            'password' => 'nullable|min:6',
            // Sekretariat tidak bisa dibuat/diubah menjadi lewat form UI.
            'role'     => 'required|in:siswa,mentor',
        ], [
            'role.in' => 'Role tidak valid. Akun Sekretariat tidak dapat dikelola melalui form ini.',
        ]);

        $user = User::findOrFail($id);

        $data = [
            'username' => $request->username,
            'role'     => $request->role,
        ];

        if ($request->filled('password')) {
            $data['pass'] = Hash::make($request->password);
        }

        $user->update($data);

        // Cek koneksi ulang saat update
        if ($request->role === 'siswa') {
            // Jika user punya id_sertifikat, pastikan tabel sertifikat juga sync
            if (!empty($user->id_sertifikat)) {
                 $siswa = SertifikatPKL::find($user->id_sertifikat);
                 if ($siswa) {
                     $siswa->update(['created_id' => $user->id]);
                 }
            } 
            // Fallback: cari berdasarkan username (NIS) jika id_sertifikat kosong
            elseif (empty($user->id_sertifikat)) {
                $siswa = SertifikatPKL::where('nim_nis', $request->username)->first();
                if ($siswa) {
                    $user->update(['id_sertifikat' => $siswa->id_sertifikat]);
                    $siswa->update(['created_id' => $user->id]);
                }
            }
        }

        return redirect()->route('user.index')->with('success', 'User berhasil diupdate.');
    }

    // Menghapus pengguna
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'siswa') {
            // Hapus data sertifikat PKL milik siswa ini agar tidak
            // muncul lagi di halaman "Data Siswa Aktif" setelah dihapus.
            // Cari via: created_id, id_sertifikat, atau nim_nis (username).
            $sertifikatIds = collect();

            if (!empty($user->id_sertifikat)) {
                $sertifikatIds->push($user->id_sertifikat);
            }

            $byCreatedId = SertifikatPKL::where('created_id', $user->id)
                ->pluck('id_sertifikat');
            $sertifikatIds = $sertifikatIds->merge($byCreatedId);

            $byNimNis = SertifikatPKL::where('nim_nis', $user->username)
                ->pluck('id_sertifikat');
            $sertifikatIds = $sertifikatIds->merge($byNimNis);

            if ($sertifikatIds->isNotEmpty()) {
                SertifikatPKL::whereIn('id_sertifikat', $sertifikatIds->unique())->delete();
            }
        }

        $user->delete();

        return redirect()->route('user.index')->with('success', 'User berhasil dihapus.');
    }

}