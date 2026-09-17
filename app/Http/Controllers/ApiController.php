<?php

namespace App\Http\Controllers;

use App\Models\SertifikatPKL;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * Get students by bidang filter
     */
    public function getStudentsByBidang(Request $request)
    {
        try {
            $bidang = $request->query('bidang');
            
            $query = SertifikatPKL::select('id_sertifikat', 'nama_lengkap', 'nim_nis', 'bidang', 'asal_sekolah')
                ->where('sts_diambil', false); // Only active students
            
            if ($bidang) {
                $query->where('bidang', 'like', '%' . $bidang . '%');
            }
            
            $students = $query->orderBy('nama_lengkap')->get();
            
            return response()->json(['students' => $students]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch students'], 500);
        }
    }
}
