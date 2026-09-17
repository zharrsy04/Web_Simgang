<?php

namespace App\Http\Controllers;

use App\Models\Portofolio;
use App\Models\SertifikatPKL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;



class PortofolioController extends Controller
{
    // Tampilkan daftar portofolio
    // Tampilkan daftar portofolio
public function index(Request $request)
{
    $user = Auth::user();

    // Ambil tanggal dari form filter
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    if ($user->role === 'mentor') {
        // Mentor lihat semua portofolio
        $portofolios = Portofolio::with('user', 'sertifikatPKL');
    } else {
        // Siswa hanya lihat portofolio miliknya
        $portofolios = Portofolio::with('sertifikatPKL')
            ->where('user_pkl_id', $user->id);
    }

    // Filter berdasarkan tanggal
    if ($startDate && $endDate) {
        $portofolios->whereBetween('created_at', [
            $startDate . ' 00:00:00',
            $endDate . ' 23:59:59'
        ]);
    } elseif ($startDate) {
        $portofolios->whereDate('created_at', '>=', $startDate);
    } elseif ($endDate) {
        $portofolios->whereDate('created_at', '<=', $endDate);
    }

    // Ambil data terbaru
    $portofolios = $portofolios->latest()->get();

    // Kirim ke view
    return view('siswa.portofolio.index', compact('portofolios', 'startDate', 'endDate'));
}


    // Form tambah portofolio
    public function create()
{
    $user = Auth::user();

    // Cari sertifikat berdasarkan username yang sama dengan nim_nis
    $sertifikatPKL = SertifikatPKL::where('nim_nis', $user->username)
    ->where(function ($query) {
        $query->where('sts_diambil', 0)
              ->orWhere('sts_diambil', 1); // tambahkan kondisi sts_diambil = 1
    })
    ->first();


    return view('siswa.portofolio.form', compact('sertifikatPKL'));
}
// public function create()
// {
//     $user = Auth::user();

//     $sertifikatPKL = SertifikatPKL::where('sts_diambil', 0)
//         ->where('created_id', $user->id)
//         ->first();

//     dd([
//         'auth_id' => $user->id,
//         'auth_username' => $user->username,
//         'sertifikatPKL' => $sertifikatPKL
//     ]);

//     return view('siswa.portofolio.form', compact('sertifikatPKL'));
// }



    // Simpan portofolio baru
    public function store(Request $request)
    {
        // dd($request->file('file'));
        //dd($request->all(), Auth::id());
        
        $request->validate([
            'sertifikat_pkl_id' => 'required|exists:tbl_sertifikat_pkl,id_sertifikat',
            'nama'              => 'required|string|max:255',
            'bidang'            => 'required|string|max:255',
            'deskripsi'         => 'required|string',
            'file'              => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $filePath = $request->hasFile('file') 
            ? $request->file('file')->store('portofolio', 'public') 
            : null;

        Portofolio::create([
            'user_pkl_id'       => Auth::id(), // pakai Auth default
            'sertifikat_pkl_id' => $request->sertifikat_pkl_id,
            'nama'              => $request->nama,
            'bidang'            => $request->bidang,
            'deskripsi'         => $request->deskripsi,
            'file'              => $filePath,
        ]);

        return redirect()->route('siswa.portofolio.index')
            ->with('success', 'Portofolio berhasil disimpan.');
    }
    

    // Form edit portofolio
    public function edit($id)
{
    $user = Auth::user();
    $portofolio = Portofolio::where('user_pkl_id', $user->id)->findOrFail($id);

    $sertifikatPKL = SertifikatPKL::where(function($query) use ($user, $portofolio) {
                        $query->where('sts_diambil', 0)
                              ->where('created_id', $user->id); // hanya sertifikat user login
                    })
                    ->orWhere('id_sertifikat', $portofolio->sertifikat_pkl_id) // sertifikat yang sudah dipakai tetap ditampilkan
                    ->first();

    return view('siswa.portofolio.form', compact('portofolio', 'sertifikatPKL'));
}


    // Update portofolio
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $portofolio = Portofolio::where('user_pkl_id', $user->id)->findOrFail($id);

        $request->validate([
            'sertifikat_pkl_id' => 'required|exists:tbl_sertifikat_pkl,id_sertifikat',
            'nama'              => 'required|string|max:255',
            'bidang'            => 'required|string|max:255',
            'deskripsi'         => 'required|string',
            'file'              => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $filePath = $portofolio->file;

        if ($request->hasFile('file')) {
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            $filePath = $request->file('file')->store('portofolio', 'public');
        }

        $portofolio->update([
            'sertifikat_pkl_id' => $request->sertifikat_pkl_id,
            'nama'              => $request->nama,
            'bidang'            => $request->bidang,
            'deskripsi'         => $request->deskripsi,
            'file'              => $filePath,
        ]);

        return redirect()->route('siswa.portofolio.index')
            ->with('success', 'Portofolio berhasil diperbarui.');
    }

    // Hapus portofolio
    public function destroy($id)
    {
        $user = Auth::user();
        $portofolio = Portofolio::where('user_pkl_id', $user->id)->findOrFail($id);

        if ($portofolio->file && Storage::disk('public')->exists($portofolio->file)) {
            Storage::disk('public')->delete($portofolio->file);
        }

        $portofolio->delete();

        return redirect()->route('siswa.portofolio.index')
            ->with('success', 'Portofolio berhasil dihapus.');
    }
    
    //method untuk dibagian mentor portofolio
    public function indexMentor(Request $request)
    {
       // $search = $request->input('search');

        $query = Portofolio::with(['user', 'sertifikatPKL']); // Semua portofolio siswa

        // if (!empty($search)) {
        //     $query->where(function ($q) use ($search) {
        //         $q->whereHas('user', function ($userQuery) use ($search) {
        //             $userQuery->where('username', 'like', "%{$search}%");
        //         })
        //         ->orWhere('nama', 'like', "%{$search}%")
        //         ->orWhereHas('sertifikatPKL', function ($sertifikatQuery) use ($search) {
        //             $sertifikatQuery->where('nama_lengkap', 'like', "%{$search}%");
        //         });
        //     });
        // }

        $portofolios = $query->latest()->get();

        //return view('mentor.portofolio', compact('portofolios', 'search'));
        return view('mentor.portofolio', compact('portofolios'));
    }

    //filter untuk menampilkan beberapa portofolio
    public function getDataMentor(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $searchValue = $request->input('search.value');
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir');

        $columns = ['id', 'nama', 'bidang', 'deskripsi', 'file'];
        $orderColumn = $columns[$orderColumnIndex] ?? 'id';

        $query = Portofolio::with(['user', 'sertifikatPKL']);

        $totalRecords = $query->count();

        if (!empty($searchValue)) {
            $query->where('nama', 'like', "%{$searchValue}%")
                ->orWhereHas('user', function ($q) use ($searchValue) {
                    $q->where('username', 'like', "%{$searchValue}%");
                })
                ->orWhereHas('sertifikatPKL', function ($q) use ($searchValue) {
                    $q->where('nama_lengkap', 'like', "%{$searchValue}%");
                });
        }

        $recordsFiltered = $query->count();

        $portofolios = $query->orderBy($orderColumn, $orderDir)
                            ->skip($start)
                            ->take($length)
                            ->get();

        $data = $portofolios->map(function ($item) {
            return [
                'nama_siswa' => $item->user->username ?? '-',
                'nama_sertifikat' => $item->sertifikatPKL->nama_lengkap ?? '-',
                'nama' => $item->nama,
                'bidang' => $item->bidang,
                'deskripsi' => $item->deskripsi,
                'file_link' => $item->file
                    ? '<a href="'.asset('storage/'.$item->file).'" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Lihat</a>'
                    : '<span class="badge bg-secondary">Tidak ada file</span>',
            ];
        });

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => intval($totalRecords),
            'recordsFiltered' => intval($recordsFiltered),
            'data' => $data,
        ]);
    }

    public function show($id)
{
    $portofolio = Portofolio::with(['user', 'sertifikatPKL'])->findOrFail($id);

    return response()->json([
        'id' => $portofolio->id,
        'nis' => $portofolio->user->username ?? '-',
        'nama_siswa' => $portofolio->sertifikatPKL->nama_lengkap ?? '-',
        'nama_porto' => $portofolio->nama,
        'bidang' => $portofolio->bidang,
        'deskripsi' => $portofolio->deskripsi,
        'file' => $portofolio->file ? asset('storage/' . $portofolio->file) : null,
        'created_at' => $portofolio->created_at,
        'updated_at' => $portofolio->updated_at,
    ]);
}
}
