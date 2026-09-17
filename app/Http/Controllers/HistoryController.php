<?php

namespace App\Http\Controllers;

use App\Models\SertifikatPKL;
use App\Constants\BidangConstants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HistoryController extends Controller
{
    /**
     * Menampilkan halaman Riwayat data siswa/mahasiswa yang pernah magang
     */
    public function index(Request $request)
    {
        $query = SertifikatPKL::query();

        // SELALU filter hanya data yang sudah selesai/lulus
        $query->where(function($q) {
            $q->where('sts_diambil', 1)
              ->orWhere(DB::raw('tgl_selesai'), '<', now());
        });

        // Filter berdasarkan bidang
        if ($request->filled('bidang')) {
            $query->where('bidang', $request->input('bidang'));
        }

        // Filter berdasarkan asal sekolah
        if ($request->filled('sekolah')) {
            $query->where('asal_sekolah', 'LIKE', '%' . $request->input('sekolah') . '%');
        }

        // Pencarian berdasarkan nama peserta
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'LIKE', '%' . $search . '%')
                  ->orWhere('nim_nis', 'LIKE', '%' . $search . '%');
            });
        }

        // Urutkan berdasarkan tanggal selesai terbaru
        $query->orderBy('tgl_selesai', 'DESC');

        // Pagination
        $peserta = $query->paginate(10)->appends($request->query())->fragment('filter-section');

        // Get valid bidang untuk filter dropdown dari constants
        $bidangList = BidangConstants::getValidBidang();

        // Get statistik
        $totalPeserta = SertifikatPKL::count();
        $pesertaSelesai = SertifikatPKL::where(function($q) {
            $q->where('sts_diambil', 1)
              ->orWhere(DB::raw('tgl_selesai'), '<', now());
        })->count();
        $pesertaAktif = SertifikatPKL::where('sts_diambil', 0)
            ->where('tgl_mulai', '<=', now())
            ->where('tgl_selesai', '>=', now())
            ->count();
        $lembagaUnik = SertifikatPKL::select('asal_sekolah')
            ->distinct()
            ->count('asal_sekolah');

        return view('landing.history', compact(
            'peserta',
            'bidangList',
            'totalPeserta',
            'pesertaSelesai',
            'pesertaAktif',
            'lembagaUnik'
        ));
    }

    /**
     * Menampilkan form untuk download sertifikat
     */
    public function downloadCertificateForm()
    {
        return view('landing.download-certificate');
    }

    /**
     * Validasi dan download sertifikat
     */
    public function validateAndDownloadCertificate(Request $request)
    {
        $request->validate([
            'nama_peserta' => 'required|string',
            'nim_nis' => 'required|string'
        ], [
            'nama_peserta.required' => 'Nama peserta harus diisi',
            'nim_nis.required' => 'NIM/NIS harus diisi'
        ]);

        $sertifikat = SertifikatPKL::where('nama_lengkap', $request->nama_peserta)
            ->where('nim_nis', $request->nim_nis)
            ->first();

        if (!$sertifikat) {
            return redirect()->route('certificate.download-form')
                ->with('error', 'Data peserta tidak ditemukan atau tidak valid. Pastikan nama dan NIM/NIS sudah benar.');
        }

        // TODO: Download sertifikat (implementasi sesuai kebutuhan)
        // Untuk sekarang, redirect dengan success message
        return redirect()->route('certificate.download-form')
            ->with('success', 'Sertifikat ditemukan! (Fitur download sedang dikembangkan)');
    }

    /**
     * Create - Hanya untuk Mentor
     */
    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu');
        }

        // Allow both mentor and sekretariat
        if (!in_array(Auth::user()->role, ['mentor', 'sekretariat'])) {
            Log::warning('Unauthorized access attempt to history.create', ['user_id' => Auth::user()->id, 'role' => Auth::user()->role]);
            return redirect()->route('history')
                ->with('error', 'Hanya Mentor dan Sekretariat yang dapat menambah data riwayat magang. Akses Anda telah dicatat.');
        }

        // Use only valid bidang from constants
        $bidangList = BidangConstants::getValidBidang();

        return view('landing.history-create', compact('bidangList'));
    }

    /**
     * Store - Hanya untuk Mentor
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            Log::warning('Unauthorized store attempt: not logged in');
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Allow both mentor and sekretariat
        if (!in_array(Auth::user()->role, ['mentor', 'sekretariat'])) {
            Log::warning('Unauthorized store attempt', ['user_id' => Auth::user()->id, 'role' => Auth::user()->role]);
            return redirect()->back()->with('error', 'Hanya Mentor dan Sekretariat yang dapat menambah data riwayat magang');
        }

        $validBidang = implode(',', BidangConstants::getValidBidang());
        
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nim_nis' => 'required|string|max:50|unique:tbl_sertifikat_pkl,nim_nis',
            'asal_sekolah' => 'required|string|max:255',
            'bidang' => 'required|string|max:100|in:' . $validBidang,
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after:tgl_mulai',
        ], [
            'nama_lengkap.required' => 'Nama peserta harus diisi',
            'nim_nis.required' => 'NIM/NIS harus diisi',
            'nim_nis.unique' => 'NIM/NIS sudah terdaftar',
            'asal_sekolah.required' => 'Asal sekolah harus diisi',
            'bidang.required' => 'Bidang harus dipilih',
            'bidang.in' => 'Bidang yang dipilih tidak valid',
            'tgl_mulai.required' => 'Tanggal mulai harus diisi',
            'tgl_selesai.required' => 'Tanggal selesai harus diisi',
            'tgl_selesai.after' => 'Tanggal selesai harus lebih besar dari tanggal mulai',
        ]);

        try {
            // Add created_id and created_by_role to validated data
            $validated['created_id'] = Auth::user()->id;
            $validated['created_by_role'] = Auth::user()->role;
            
            $sertifikat = SertifikatPKL::create($validated);

            Log::info('Riwayat magang baru dibuat oleh ' . Auth::user()->role . ' ' . Auth::user()->username, [
                'id_sertifikat' => $sertifikat->id_sertifikat,
                'nama_peserta' => $sertifikat->nama_lengkap,
                'created_id' => Auth::user()->id
            ]);

            return redirect()->route('history')
                ->with('success', 'Data riwayat magang berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error('Error create riwayat magang', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menambahkan data: ' . $e->getMessage());
        }
    }

    /**
     * Edit - Hanya untuk Mentor
     */
    public function edit($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu');
        }

        if (Auth::user()->role !== 'mentor') {
            Log::warning('Unauthorized access attempt to history.edit', ['user_id' => Auth::user()->id, 'role' => Auth::user()->role]);
            return redirect()->route('history')
                ->with('error', 'Hanya Mentor yang dapat mengedit data. Akses Anda telah dicatat.');
        }

        $peserta = SertifikatPKL::findOrFail($id);
        // Use only valid bidang from constants
        $bidangList = BidangConstants::getValidBidang();

        return view('landing.history-edit', compact('peserta', 'bidangList'));
    }

    /**
     * Update - Hanya untuk Mentor
     */
    public function update(Request $request, $id)
    {
        if (!Auth::check()) {
            Log::warning('Unauthorized update attempt: not logged in');
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (Auth::user()->role !== 'mentor') {
            Log::warning('Unauthorized update attempt', ['user_id' => Auth::user()->id, 'role' => Auth::user()->role]);
            return response()->json(['error' => 'Only mentors can update history records'], 403);
        }

        $sertifikat = SertifikatPKL::findOrFail($id);

        $validBidang = implode(',', BidangConstants::getValidBidang());
        
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nim_nis' => 'required|string|max:50|unique:tbl_sertifikat_pkl,nim_nis,' . $id . ',id_sertifikat',
            'asal_sekolah' => 'required|string|max:255',
            'bidang' => 'required|string|max:100|in:' . $validBidang,
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after:tgl_mulai',
        ]);

        try {
            $sertifikat->update($validated);

            Log::info('Riwayat magang diperbarui oleh Mentor ' . Auth::user()->username, [
                'id_sertifikat' => $id
            ]);

            return redirect()->route('history')
                ->with('success', 'Data riwayat magang berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Error update riwayat: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data');
        }
    }

    /**
     * Delete - Hanya untuk Mentor
     */
    public function destroy($id)
    {
        if (!Auth::check()) {
            Log::warning('Unauthorized destroy attempt: not logged in');
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Allow both mentor and sekretariat
        if (!in_array(Auth::user()->role, ['mentor', 'sekretariat'])) {
            Log::warning('Unauthorized destroy attempt', ['user_id' => Auth::user()->id, 'role' => Auth::user()->role]);
            return back()->with('error', 'Hanya Mentor dan Sekretariat yang dapat menghapus data riwayat magang');
        }

        try {
            $sertifikat = SertifikatPKL::findOrFail($id);
            $nama_peserta = $sertifikat->nama_lengkap;
            $sertifikat->delete();

            Log::info('Riwayat magang dihapus oleh ' . Auth::user()->role . ' ' . Auth::user()->username, [
                'id_sertifikat' => $id,
                'nama_peserta' => $nama_peserta
            ]);

            return redirect()->route('history')
                ->with('success', 'Data riwayat magang berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error delete riwayat magang', [
                'id_sertifikat' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return back()->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Export data ke CSV (opsional)
     */
    public function export(Request $request)
    {
        $query = SertifikatPKL::query();

        if ($request->filled('status') && $request->input('status') === 'selesai') {
            $query->where(function($q) {
                $q->where('sts_diambil', 1)
                  ->orWhere(DB::raw('tgl_selesai'), '<', now());
            });
        }

        if ($request->filled('bidang')) {
            $query->where('bidang', $request->input('bidang'));
        }

        if ($request->filled('sekolah')) {
            $query->where('asal_sekolah', 'LIKE', '%' . $request->input('sekolah') . '%');
        }

        $peserta = $query->orderBy('tgl_selesai', 'DESC')->get();

        // CSV Header
        $filename = 'riwayat-magang-' . date('Y-m-d-His') . '.csv';
        $headers = array(
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $callback = function () use ($peserta) {
            $file = fopen('php://output', 'w');
            
            // Header baris
            fputcsv($file, array('No', 'Nama Peserta', 'Asal Sekolah/PT', 'Bidang', 'Periode Mulai', 'Periode Selesai', 'Status'));

            // Data baris
            $no = 1;
            foreach ($peserta as $row) {
                fputcsv($file, array(
                    $no++,
                    $row->nama_lengkap,
                    $row->asal_sekolah,
                    $row->bidang,
                    $row->tgl_mulai->format('d-m-Y'),
                    $row->tgl_selesai->format('d-m-Y'),
                    'Selesai'
                ));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
