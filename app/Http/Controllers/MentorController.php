<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MentorSiswa;
use App\Constants\BidangConstants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class MentorController extends Controller
{
    /**
     * Display a listing of mentors.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $bidangFilter = $request->query('bidang', 'all');
        $search = $request->query('search');

        $query = User::whereIn('role', ['mentor', 'sekretariat']);

        // Filter by bidang
        if ($bidangFilter !== 'all' && BidangConstants::isValidBidang($bidangFilter)) {
            $query->where('bidang', $bidangFilter);
        }

        // Search by name or username
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $mentors = $query->orderBy('nama')->paginate(10);
        $bidangList = BidangConstants::getValidBidang();

        return view('mentor.data-mentor.index', compact('mentors', 'bidangFilter', 'bidangList', 'search'));
    }

    /**
     * Show the form for creating a new mentor.
     */
    public function create()
    {
        $this->authorize('create', User::class);

        $bidangList = BidangConstants::getValidBidang();

        return view('mentor.data-mentor.create', compact('bidangList'));
    }

    /**
     * Store a newly created mentor in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:tbl_login,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:tbl_login,email'],
            'nama' => ['required', 'string', 'max:255'],
            'bidang' => ['required', 'string', 'in:' . implode(',', BidangConstants::getValidBidang())],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        DB::beginTransaction();
        try {
            $mentor = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'nama' => $request->nama,
                'pass' => Hash::make($request->password),
                'role' => 'mentor',
                'bidang' => $request->bidang,
            ]);

            DB::commit();
            return redirect()->route('mentor.data-mentor.index')->with('success', 'Mentor berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan mentor: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified mentor.
     */
    public function edit($id)
    {
        $mentor = User::where('role', 'mentor')->findOrFail($id);
        $this->authorize('update', $mentor);

        $bidangList = BidangConstants::getValidBidang();

        return view('mentor.data-mentor.edit', compact('mentor', 'bidangList'));
    }

    /**
     * Update the specified mentor in storage.
     */
    public function update(Request $request, $id)
    {
        $mentor = User::where('role', 'mentor')->findOrFail($id);
        $this->authorize('update', $mentor);

        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:tbl_login,username,' . $id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:tbl_login,email,' . $id],
            'nama' => ['required', 'string', 'max:255'],
            'bidang' => ['required', 'string', 'in:' . implode(',', BidangConstants::getValidBidang())],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        DB::beginTransaction();
        try {
            $updateData = [
                'username' => $request->username,
                'email' => $request->email,
                'nama' => $request->nama,
                'bidang' => $request->bidang,
            ];

            // Update password only if provided
            if ($request->filled('password')) {
                $updateData['pass'] = Hash::make($request->password);
            }

            $mentor->update($updateData);

            DB::commit();
            return redirect()->route('mentor.data-mentor.index')->with('success', 'Data mentor berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data mentor: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove student from mentor's guidance (unassign mentor-student relationship)
     */
    public function removeStudent(Request $request)
    {
        $request->validate([
            'mentor_id' => 'required|exists:tbl_login,id',
            'siswa_id' => 'required|exists:tbl_sertifikat_pkl,id_sertifikat',
        ]);

        try {
            $mentorSiswa = MentorSiswa::where('mentor_id', $request->mentor_id)
                ->where('siswa_id', $request->siswa_id)
                ->first();

            if (!$mentorSiswa) {
                return response()->json(['success' => false, 'message' => 'Relasi mentor-siswa tidak ditemukan'], 404);
            }

            // Get student and mentor names for confirmation message
            $student = $mentorSiswa->siswa;
            $mentor = $mentorSiswa->mentor;

            // Delete the mentor-siswa relationship (not the student data)
            $mentorSiswa->delete();

            return response()->json([
                'success' => true,
                'message' => "Siswa {$student->nama_lengkap} berhasil dihapus dari daftar bimbingan {$mentor->nama}"
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified mentor from storage.
     */
    public function destroy($id)
    {
        $mentor = User::where('role', 'mentor')->findOrFail($id);
        $this->authorize('delete', $mentor);

        // Cek apakah mentor masih memiliki siswa bimbingan aktif
        $activeStudents = MentorSiswa::where('mentor_id', $id)
            ->where('is_active', true)
            ->count();

        if ($activeStudents > 0) {
            return redirect()->back()->with('error', "Tidak dapat menghapus mentor. Masih ada {$activeStudents} siswa bimbingan aktif. Pindahkan siswa ke mentor lain terlebih dahulu.");
        }

        DB::beginTransaction();
        try {
            // Hapus relasi mentor-siswa (non-aktif)
            MentorSiswa::where('mentor_id', $id)->delete();

            // Hapus mentor
            $mentor->delete();

            DB::commit();
            return redirect()->route('mentor.index')->with('success', 'Mentor berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus mentor: ' . $e->getMessage());
        }
    }

    /**
     * Get mentors by bidang (for API/AJAX)
     */
    public function getByBidang($bidang)
    {
        $this->authorize('viewAny', User::class);

        if (!BidangConstants::isValidBidang($bidang)) {
            return response()->json(['error' => 'Bidang tidak valid'], 400);
        }

        $mentors = User::where('role', 'mentor')
            ->where('bidang', $bidang)
            ->orderBy('nama')
            ->get(['id', 'nama', 'username', 'bidang']);

        return response()->json($mentors);
    }

    /**
     * Display mentor directory grouped by bidang
     */
    public function direktori()
    {
        $this->authorize('viewAny', User::class);

        $bidangList = BidangConstants::getValidBidang();
        $mentorsByBidang = [];

        foreach ($bidangList as $bidang) {
            $mentors = User::where('role', 'mentor')
                ->where('bidang', $bidang)
                ->with(['mentorSiswa' => function($query) {
                    $query->where('is_active', true)
                        ->with('siswa');
                }])
                ->orderBy('nama')
                ->get();

            $mentorsByBidang[$bidang] = $mentors;
        }

        return view('mentor.data-mentor.direktori', compact('mentorsByBidang', 'bidangList'));
    }
}
