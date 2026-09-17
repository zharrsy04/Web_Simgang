<?php

namespace App\Http\Controllers;

use App\Models\PenempatanBidang;
use App\Models\SertifikatPKL;
use App\Models\PengajuanMagang;
use App\Constants\BidangConstants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PenempatanBidangController extends Controller
{
    /**
     * Show placement form for mentor/sekretariat
     */
    public function show($idSertifikat)
    {
        try {
            $sertifikat = SertifikatPKL::findOrFail($idSertifikat);
            
            // Check if already placed
            if (PenempatanBidang::isPlaced($idSertifikat)) {
                $penempatan = PenempatanBidang::getPlacement($idSertifikat);
                return response()->json([
                    'success' => false,
                    'message' => "Siswa ini sudah ditempatkan di bidang {$penempatan->bidang_ditempatkan} oleh {$penempatan->ditempatkan_oleh}",
                    'isPlaced' => true,
                    'placement' => $penempatan
                ]);
            }

            // Get bidang asal from pengajuan if exists
            $pengajuan = PengajuanMagang::where('user_id', $sertifikat->created_id)
                ->orderBy('created_at', 'desc')
                ->first();

            $bidangAsal = $pengajuan ? $pengajuan->bidang : $sertifikat->bidang;

            // List of available bidang
            $bidangList = BidangConstants::getValidBidang();

            return response()->json([
                'success' => true,
                'sertifikat' => $sertifikat,
                'bidang_asal' => $bidangAsal,
                'bidang_list' => $bidangList,
                'isPlaced' => false
            ]);
        } catch (\Exception $e) {
            Log::error('Error in PenempatanBidangController@show: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data sertifikat'
            ], 404);
        }
    }

    /**
     * Store penempatan bidang
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_sertifikat' => 'required|exists:tbl_sertifikat_pkl,id_sertifikat',
            'bidang_ditempatkan' => 'required|string|max:100',
            'keterangan' => 'nullable|string|max:255',
        ]);

        try {
            $sertifikat = SertifikatPKL::findOrFail($request->id_sertifikat);

            // Check if already placed
            if (PenempatanBidang::isPlaced($request->id_sertifikat)) {
                $penempatan = PenempatanBidang::getPlacement($request->id_sertifikat);
                return response()->json([
                    'success' => false,
                    'message' => "Siswa ini sudah ditempatkan di bidang {$penempatan->bidang_ditempatkan} oleh {$penempatan->ditempatkan_oleh}",
                ], 409);
            }

            DB::beginTransaction();

            // Get bidang asal from pengajuan
            $pengajuan = PengajuanMagang::where('user_id', $sertifikat->created_id)
                ->orderBy('created_at', 'desc')
                ->first();

            $bidangAsal = $pengajuan ? $pengajuan->bidang : $sertifikat->bidang;

            // Create penempatan record
            $penempatan = PenempatanBidang::create([
                'id_sertifikat' => $request->id_sertifikat,
                'bidang_asal' => $bidangAsal,
                'bidang_ditempatkan' => $request->bidang_ditempatkan,
                'ditempatkan_oleh' => Auth::user()->role,
                'user_id' => Auth::id(),
                'keterangan' => $request->keterangan,
            ]);

            // Update sertifikat bidang to the placement bidang
            $sertifikat->update([
                'bidang' => $request->bidang_ditempatkan,
            ]);

            DB::commit();

            Log::info('Siswa placed successfully', [
                'id_sertifikat' => $request->id_sertifikat,
                'nama_siswa' => $sertifikat->nama_lengkap,
                'bidang_asal' => $bidangAsal,
                'bidang_ditempatkan' => $request->bidang_ditempatkan,
                'placed_by' => Auth::user()->role,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Penempatan bidang berhasil disimpan',
                'penempatan' => $penempatan
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing penempatan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan penempatan bidang'
            ], 500);
        }
    }

    /**
     * Get penempatan details
     */
    public function detail($idSertifikat)
    {
        try {
            $penempatan = PenempatanBidang::where('id_sertifikat', $idSertifikat)->firstOrFail();
            
            return response()->json([
                'success' => true,
                'penempatan' => $penempatan,
                'user' => $penempatan->user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data penempatan tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Check if siswa is placed - untuk AJAX
     */
    public function isPlaced($idSertifikat)
    {
        try {
            $isPlaced = PenempatanBidang::isPlaced($idSertifikat);
            
            if ($isPlaced) {
                $penempatan = PenempatanBidang::getPlacement($idSertifikat);
                return response()->json([
                    'success' => true,
                    'isPlaced' => true,
                    'penempatan' => [
                        'bidang_asal' => $penempatan->bidang_asal,
                        'bidang_ditempatkan' => $penempatan->bidang_ditempatkan,
                        'ditempatkan_oleh' => $penempatan->ditempatkan_oleh,
                        'user_name' => $penempatan->user->nama_lengkap ?? $penempatan->user->username,
                        'tanggal_penempatan' => $penempatan->tanggal_penempatan->format('d-m-Y H:i'),
                    ]
                ]);
            }

            return response()->json([
                'success' => true,
                'isPlaced' => false
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking placement status'
            ], 500);
        }
    }

    /**
     * Get placements list for dashboard
     */
    public function listPlacements()
    {
        try {
            $placements = PenempatanBidang::with('sertifikat', 'user')
                ->orderBy('tanggal_penempatan', 'desc')
                ->paginate(20);

            return response()->json([
                'success' => true,
                'placements' => $placements
            ]);
        } catch (\Exception $e) {
            Log::error('Error listing placements: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data penempatan'
            ], 500);
        }
    }

    /**
     * Get placements by bidang for dashboard
     */
    public function placementsByBidang($bidang)
    {
        try {
            $placements = PenempatanBidang::where('bidang_ditempatkan', $bidang)
                ->with('sertifikat', 'user')
                ->orderBy('tanggal_penempatan', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'bidang' => $bidang,
                'total' => $placements->count(),
                'placements' => $placements
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting placements by bidang: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data penempatan'
            ], 500);
        }
    }
}
