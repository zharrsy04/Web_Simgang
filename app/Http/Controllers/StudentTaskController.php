<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskSubmission;
use App\Models\SertifikatPKL;
use App\Models\PengajuanMagang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class StudentTaskController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Cari sertifikat siswa dengan helper terpusat
        $sertifikat = SertifikatPKL::getByUser($user);
        
        if (!$sertifikat) {
            return view('siswa.tasks.index', [
                'tasks' => collect([]),
                'message' => 'Data magang Anda belum tersedia.'
            ]);
        }
        
        // Ambil semua tugas untuk siswa ini
        $tasks = Task::where('sertifikat_pkl_id', $sertifikat->id_sertifikat)
            ->with(['assignedByUser', 'submissions' => function($query) use ($user) {
                $query->where('student_id', $user->id);
            }])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Hitung statistik
        $stats = [
            'total' => $tasks->count(),
            'pending' => 0,
            'submitted' => 0,
            'graded' => 0,
        ];
        
        foreach ($tasks as $task) {
            $submission = $task->submissions->first();
            if ($submission) {
                if ($submission->status === 'submitted') {
                    $stats['submitted']++;
                } elseif ($submission->status === 'graded') {
                    $stats['graded']++;
                }
            } else {
                $stats['pending']++;
            }
        }
        
        return view('siswa.tasks.index', compact('tasks', 'stats'));
    }
    
    public function show($id)
    {
        $user = Auth::user();
        $task = Task::with(['assignedByUser', 'recipientStudent'])->findOrFail($id);
        
        // Cek apakah tugas ini milik siswa yang login
        $sertifikat = SertifikatPKL::getByUser($user);
        
        if (!$sertifikat || $task->sertifikat_pkl_id != $sertifikat->id_sertifikat) {
            Log::warning('Unauthorized task access attempt', [
                'user_id' => $user->id,
                'task_id' => $id,
                'sertifikat_id' => $sertifikat ? $sertifikat->id_sertifikat : null,
                'task_sertifikat_id' => $task->sertifikat_pkl_id
            ]);
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }
        
        // Ambil submission jika ada
        $submission = TaskSubmission::where('task_id', $id)
            ->where('student_id', $user->id)
            ->first();
        
        // Debug logging untuk troubleshooting
        if ($submission) {
            Log::info('Submission data untuk task_id=' . $id, [
                'submission_id' => $submission->id,
                'status' => $submission->status,
                'grade' => $submission->grade,
                'mentor_feedback' => $submission->mentor_feedback,
                'graded_at' => $submission->graded_at,
            ]);
        } else {
            Log::info('Tidak ada submission untuk task_id=' . $id . ' dan student_id=' . $user->id);
        }
        
        return view('siswa.tasks.show', compact('task', 'submission'));
    }
    
    public function submit(Request $request, $id)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
            'files.*' => 'nullable|file|max:51200', // Maksimal 50MB, terima semua jenis file
        ], [
            'files.*.max' => 'Ukuran file maksimal 50MB',
        ]);
        
        $user = Auth::user();
        $task = Task::findOrFail($id);
        
        // Cek apakah tugas ini milik siswa yang login
        $sertifikat = SertifikatPKL::getByUser($user);
        
        if (!$sertifikat || $task->sertifikat_pkl_id != $sertifikat->id_sertifikat) {
            Log::warning('Unauthorized task submission attempt', [
                'user_id' => $user->id,
                'task_id' => $id,
                'sertifikat_id' => $sertifikat ? $sertifikat->id_sertifikat : null,
                'task_sertifikat_id' => $task->sertifikat_pkl_id
            ]);
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }

        // Cek apakah sudah melewati tenggat waktu
        if ($task->due_date && now()->gt($task->due_date)) {
            return redirect()->back()
                ->with('error', 'Tenggat waktu untuk tugas ini sudah berlalu.');
        }
        
        try {
            DB::beginTransaction();
            
            $filePaths = [];
            
            // Upload files
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $path = $file->store('task_submissions', 'public');
                    $filePaths[] = $path;
                }
            }
            
            // Cek apakah sudah ada submission
            $submission = TaskSubmission::where('task_id', $id)
                ->where('student_id', $user->id)
                ->first();
            
            if ($submission) {
                // Hapus file lama jika ada
                if ($submission->file_paths) {
                    foreach ($submission->file_paths as $oldPath) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }
                
                // Update submission dengan file baru
                $submission->update([
                    'notes' => $request->notes,
                    'file_paths' => $filePaths,
                    'status' => 'submitted',
                    'submitted_at' => now(),
                ]);
            } else {
                // Buat submission baru
                TaskSubmission::create([
                    'task_id' => $id,
                    'student_id' => $user->id,
                    'notes' => $request->notes,
                    'file_paths' => $filePaths,
                    'status' => 'submitted',
                    'submitted_at' => now(),
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('siswa.tasks.show', $id)
                ->with('success', 'Tugas berhasil dikumpulkan!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error submitting task: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal mengumpulkan tugas. Silakan coba lagi.')
                ->withInput();
        }
    }
    
    public function deleteFile(Request $request, $id)
    {
        $user = Auth::user();
        $submission = TaskSubmission::where('id', $id)
            ->where('student_id', $user->id)
            ->firstOrFail();
        
        // Hanya bisa hapus file jika status masih pending atau revision
        if (!in_array($submission->status, ['pending', 'revision'])) {
            return response()->json(['error' => 'Tidak dapat menghapus file pada status ini.'], 403);
        }
        
        $fileIndex = $request->input('file_index');
        $filePaths = $submission->file_paths ?? [];
        
        if (isset($filePaths[$fileIndex])) {
            // Hapus file dari storage
            Storage::disk('public')->delete($filePaths[$fileIndex]);
            
            // Hapus dari array
            array_splice($filePaths, $fileIndex, 1);
            
            $submission->update(['file_paths' => $filePaths]);
            
            return response()->json(['success' => true, 'message' => 'File berhasil dihapus.']);
        }
        
        return response()->json(['error' => 'File tidak ditemukan.'], 404);
    }
}
