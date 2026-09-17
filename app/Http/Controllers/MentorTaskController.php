<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\SertifikatPKL;
use App\Models\PenempatanBidang;
use App\Models\User;
use App\Models\MentorSiswa;
use App\Models\TaskSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MentorTaskController extends Controller
{
    /**
     * Display list of students being mentored with their tasks
     */
    public function daftarTugas(Request $request)
    {
        try {
            $mentor = Auth::user();

            // Get search and filter parameters
            $search       = $request->query('search');
            $bidangFilter = $request->query('bidang');

            // Ambil siswa yang pernah diberi tugas
            // Mentor: hanya tugas yang diberikan oleh mentor ini
            // Sekretariat: semua tugas dari semua mentor
            $taskQuery = Task::query();

            if ($mentor->role === 'mentor') {
                $taskQuery->where('assigned_by', $mentor->id);
            }

            $taskQuery->with(['recipientStudent' => function ($q) {
                $q->select('id_sertifikat', 'nama_lengkap', 'nim_nis', 'bidang', 'asal_sekolah');
            }]);

            if ($search) {
                $taskQuery->whereHas('recipientStudent', function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%");
                });
            }

            if ($bidangFilter) {
                $taskQuery->whereHas('recipientStudent', function ($q) use ($bidangFilter) {
                    $q->where('bidang', 'like', "%{$bidangFilter}%");
                });
            }

            // Deduplicate — only unique students across all tasks
            $students = $taskQuery->get()
                ->pluck('recipientStudent')
                ->filter()
                ->unique('id_sertifikat')
                ->sortBy('nama_lengkap')
                ->values();

            // Group by bidang for display
            $studentsByBidang = $students->groupBy(function ($s) {
                return $s->bidang ?? 'Lainnya';
            })->sortKeys();

            return view('mentor.tasks.daftar-tugas', compact('studentsByBidang', 'search', 'bidangFilter'));
        } catch (\Exception $e) {
            Log::error('Error fetching daftar tugas: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat daftar tugas');
        }
    }


    /**
     * Display all tasks for a specific student
     * Also marks all submitted-but-unviewed submissions as viewed by the mentor
     */
    public function tugasSiswa($siswaId)
    {
        try {
            $mentor = Auth::user();

            // Get student info first
            $siswa = SertifikatPKL::findOrFail($siswaId);

            // Verify student is being mentored by this mentor (optional check - allow viewing tasks even if not directly mentored)
            $mentorSiswa = MentorSiswa::where('mentor_id', $mentor->id)
                ->where('siswa_id', $siswaId)
                ->where('is_active', true)
                ->first();

            // Get all tasks for this student (from any mentor)
            $tasks = Task::where('sertifikat_pkl_id', $siswaId)
                ->with(['assignedByUser' => function($query) {
                    $query->select('id', 'nama', 'username');
                }, 'submissions' => function($query) {
                    $query->with('student');
                }])
                ->orderBy('created_at', 'desc')
                ->get();

            // Mark all submitted-but-unviewed submissions for this student as viewed
            // Only mark those belonging to tasks assigned by THIS mentor (to respect scope)
            $taskIds = $tasks->where('assigned_by', $mentor->id)->pluck('id');
            if ($taskIds->isNotEmpty()) {
                TaskSubmission::whereIn('task_id', $taskIds)
                    ->whereIn('status', ['submitted', 'revision'])
                    ->whereNull('viewed_at')
                    ->update(['viewed_at' => now()]);
            }

            return view('mentor.tasks.tugas-siswa', compact('tasks', 'siswa', 'mentorSiswa'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Student not found: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Siswa tidak ditemukan');
        } catch (\Exception $e) {
            Log::error('Error fetching tugas siswa: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat tugas siswa');
        }
    }

    /**
     * Download task submission file
     */
    public function downloadSubmission($submissionId)
    {
        try {
            $mentor = Auth::user();
            $submission = TaskSubmission::findOrFail($submissionId);
            
            // Get task
            $task = $submission->task;
            
            // Verify student is being mentored by this mentor
            $mentorSiswa = MentorSiswa::where('mentor_id', $mentor->id)
                ->where('siswa_id', $task->sertifikat_pkl_id)
                ->where('is_active', true)
                ->firstOrFail();

            // Get file paths
            $filePaths = $submission->file_paths;
            
            if (empty($filePaths)) {
                return redirect()->back()->with('error', 'Tidak ada file yang dikumpulkan');
            }

            // For simplicity, download first file
            $filePath = $filePaths[0];
            
            if (!file_exists(storage_path('app/public/' . $filePath))) {
                return redirect()->back()->with('error', 'File tidak ditemukan');
            }

            return response()->download(storage_path('app/public/' . $filePath));
        } catch (\Exception $e) {
            Log::error('Error downloading submission: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengunduh file');
        }
    }

    /**
     * Display all tasks created by the mentor
     */
    public function index()
    {
        try {
            $mentor = Auth::user();
            
            // Get all tasks created by this mentor, dengan bidang info
            $tasks = Task::where('assigned_by', $mentor->id)
                ->with(['recipientStudent' => function($query) {
                    $query->select('id_sertifikat', 'nama_lengkap', 'bidang', 'nim_nis');
                }, 'assignedByUser'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            // Enrich tasks dengan informasi bidang dari penempatan
            $tasks->load('recipientStudent');
            foreach ($tasks as $task) {
                if ($task->recipientStudent) {
                    $placement = PenempatanBidang::where('id_sertifikat', $task->recipientStudent->id_sertifikat)
                        ->first();
                    $task->student_bidang = $placement ? $placement->bidang_ditempatkan : $task->recipientStudent->bidang;
                }
            }

            return view('mentor.tasks.index', compact('tasks'));
        } catch (\Exception $e) {
            Log::error('Error fetching mentor tasks: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat daftar tugas');
        }
    }

    /**
     * Show form to create task
     */
    public function create()
    {
        try {
            $mentor = Auth::user();
            
            // Get all bidang that this mentor handles
            $mentorBidang = SertifikatPKL::where('created_id', $mentor->id)
                ->distinct('bidang')
                ->pluck('bidang')
                ->sort()
                ->values();

            if ($mentorBidang->isEmpty()) {
                return redirect()->back()
                    ->with('info', 'Anda belum menangani siswa di bidang apapun');
            }

            return view('mentor.tasks.create', compact('mentorBidang'));
        } catch (\Exception $e) {
            Log::error('Error showing create task form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membuka form tugas');
        }
    }

    /**
     * Get list of students in a specific field (AJAX endpoint)
     */
    public function getStudentsByBidang($bidang)
    {
        try {
            $mentor = Auth::user();
            
            // Verify mentor handles this bidang
            $hasBidang = SertifikatPKL::where('created_id', $mentor->id)
                ->where('bidang', $bidang)
                ->exists();

            if (!$hasBidang) {
                return response()->json(['error' => 'Anda tidak menangani bidang ini'], 403);
            }

            // Get students in this bidang handled by this mentor
            $students = SertifikatPKL::where('created_id', $mentor->id)
                ->where('bidang', $bidang)
                ->select('id_sertifikat', 'nama_lengkap', 'nim_nis')
                ->orderBy('nama_lengkap')
                ->get();

            return response()->json($students);
        } catch (\Exception $e) {
            Log::error('Error fetching students: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal memuat daftar siswa'], 500);
        }
    }

    /**
     * Get students by bidang via placement (more accurate)
     */
    public function getStudentsByBidangPlaced($bidang)
    {
        try {
            $mentor = Auth::user();
            
            // Get all students placed in this bidang where mentor is their pembimbing
            $placements = PenempatanBidang::where('bidang_ditempatkan', $bidang)
                ->with(['sertifikat' => function($query) use ($mentor) {
                    $query->where('created_id', $mentor->id)
                        ->select('id_sertifikat', 'nama_lengkap', 'nim_nis', 'created_id');
                }])
                ->get()
                ->filter(function($placement) {
                    return $placement->sertifikat !== null;
                })
                ->pluck('sertifikat')
                ->values();

            return response()->json($placements);
        } catch (\Exception $e) {
            Log::error('Error fetching placed students: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal memuat daftar siswa'], 500);
        }
    }

    /**
     * Store a new task
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'sertifikat_pkl_id' => 'required|integer|exists:tbl_sertifikat_pkl,id_sertifikat',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:5000',
                'due_date' => 'nullable|date',
            ], [
                'sertifikat_pkl_id.exists' => 'Siswa tidak ditemukan',
            ]);

            $task = Task::create([
                'sertifikat_pkl_id' => $validated['sertifikat_pkl_id'],
                'assigned_by' => Auth::id(),
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'due_date' => $validated['due_date'] ?? null,
                'status' => 'pending',
                'is_read' => false,
            ]);

            return response()->json(['success' => true, 'message' => 'Tugas berhasil ditetapkan!', 'task' => $task]);
        } catch (\Exception $e) {
            Log::error('Error storing task: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menyimpan tugas'], 500);
        }
    }

    /**
     * Get task data via API
     */
    public function getTask($taskId)
    {
        Log::info('=== DEBUG: getTask called with taskId: ' . $taskId);
        
        try {
            $task = Task::findOrFail($taskId);
            Log::info('=== DEBUG: Task found, ID: ' . $task->id . ', Title: ' . $task->title);
            
            $responseData = [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'due_date' => $task->due_date ? $task->due_date->format('Y-m-d') : null,
                'sertifikat_pkl_id' => $task->sertifikat_pkl_id,
                'status' => $task->status,
            ];
            
            Log::info('=== DEBUG: Returning JSON response', $responseData);
            return response()->json($responseData);
        } catch (\Exception $e) {
            Log::error('=== DEBUG ERROR in getTask: ' . $e->getMessage());
            Log::error('=== DEBUG ERROR stack: ' . $e->getTraceAsString());
            return response()->json(['error' => 'Task not found'], 404);
        }
    }

    /**
     * Update an existing task
     */
    public function update(Request $request, $taskId)
    {
        try {
            $task = Task::findOrFail($taskId);
            
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:5000',
                'due_date' => 'nullable|date',
            ]);

            $task->update([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'due_date' => $validated['due_date'] ?? null,
            ]);

            return response()->json(['success' => true, 'message' => 'Tugas berhasil diperbarui!']);
        } catch (\Exception $e) {
            Log::error('Error updating task: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat memperbarui tugas: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete a task
     */
    public function destroy($taskId)
    {
        Log::info('=== DEBUG: destroy called with taskId: ' . $taskId);
        
        try {
            $task = Task::findOrFail($taskId);
            Log::info('=== DEBUG: Task found for deletion, ID: ' . $task->id . ', Title: ' . $task->title);
            
            // Check if task has submissions
            $hasSubmissions = $task->submissions()->exists();
            Log::info('=== DEBUG: Has submissions: ' . ($hasSubmissions ? 'true' : 'false'));
            
            // Delete submissions first if they exist (to avoid foreign key constraint)
            if ($hasSubmissions) {
                Log::info('=== DEBUG: Deleting submissions first');
                $task->submissions()->delete();
            }
            
            Log::info('=== DEBUG: Deleting task');
            $task->delete();

            $message = $hasSubmissions 
                ? 'Tugas berhasil dihapus (catatan: tugas ini memiliki pengumpulan dari siswa)' 
                : 'Tugas berhasil dihapus';

            Log::info('=== DEBUG: Task deleted successfully');
            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            Log::error('=== DEBUG ERROR in destroy: ' . $e->getMessage());
            Log::error('=== DEBUG ERROR stack: ' . $e->getTraceAsString());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus tugas: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show a specific task
     */
    public function show($id)
    {
        try {
            $mentor = Auth::user();
            $task = Task::findOrFail($id);

            // Verify task belongs to this mentor
            if ($task->assigned_by !== $mentor->id) {
                abort(403, 'Anda tidak memiliki akses ke tugas ini');
            }

            $task->load(['recipientStudent', 'assignedByUser', 'submissions']);

            // Get placement info
            $placement = PenempatanBidang::where('id_sertifikat', $task->recipientStudent->id_sertifikat)
                ->first();
            $task->student_bidang = $placement ? $placement->bidang_ditempatkan : $task->recipientStudent->bidang;

            return view('mentor.tasks.show', compact('task'));
        } catch (\Exception $e) {
            Log::error('Error showing task: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat tugas');
        }
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        try {
            $mentor = Auth::user();
            $task = Task::findOrFail($id);

            if ($task->assigned_by !== $mentor->id) {
                abort(403, 'Anda tidak memiliki akses untuk mengubah tugas ini');
            }

            $task->load(['recipientStudent']);
            $mentorBidang = SertifikatPKL::where('created_id', $mentor->id)
                ->distinct('bidang')
                ->pluck('bidang')
                ->sort()
                ->values();

            return view('mentor.tasks.edit', compact('task', 'mentorBidang'));
        } catch (\Exception $e) {
            Log::error('Error showing edit task form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membuka form edit');
        }
    }

    /**
     * Get task submissions (for grading)
     */
    public function getSubmissions($taskId)
    {
        try {
            $mentor = Auth::user();
            $task = Task::findOrFail($taskId);

            if ($task->assigned_by !== $mentor->id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $submissions = $task->submissions()
                ->with(['student'])
                ->orderBy('submitted_at', 'desc')
                ->get();

            return response()->json($submissions);
        } catch (\Exception $e) {
            Log::error('Error fetching submissions: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal memuat submissions'], 500);
        }
    }

    /**
     * Grade a submission
     */
    public function gradeSubmission(Request $request, $submissionId)
    {
        try {
            $validated = $request->validate([
                'grade' => 'required|string|in:A,B,C,D,E',
                'mentor_feedback' => 'nullable|string|max:2000',
            ]);

            $mentor = Auth::user();
            $submission = \App\Models\TaskSubmission::findOrFail($submissionId);
            
            // Verify task belongs to this mentor
            $task = $submission->task;
            if ($task->assigned_by !== $mentor->id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            DB::beginTransaction();

            $submission->update([
                'grade' => $validated['grade'],
                'mentor_feedback' => $validated['mentor_feedback'],
                'status' => 'graded',
                'graded_at' => now(),
            ]);

            DB::commit();

            Log::info('Submission graded', [
                'submission_id' => $submissionId,
                'graded_by' => $mentor->id,
                'grade' => $validated['grade']
            ]);

            return response()->json(['success' => true, 'message' => 'Penilaian berhasil disimpan']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error grading submission: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menyimpan penilaian'], 500);
        }
    }

    /**
     * API: Hitung badge notification untuk sidebar mentor.
     * - pengajuan_count: Pengajuan magang baru yang belum diverifikasi, sesuai bidang mentor
     * - tugas_count: Pengumpulan tugas siswa yang belum dilihat mentor (viewed_at = null)
     */
    public function badgeCounts()
    {
        try {
            $mentor = Auth::user();

            // 1. Hitung pengajuan pending sesuai bidang mentor
            $pengajuanCount = \App\Models\PengajuanMagang::where('status', 'pending')
                ->where('bidang', $mentor->bidang)
                ->count();

            // 2. Hitung task submissions yang belum dilihat mentor
            //    Scope: hanya tugas yang dibuat oleh mentor ini (assigned_by = mentor->id)
            $taskIds = Task::where('assigned_by', $mentor->id)->pluck('id');
            $tugasCount = 0;
            if ($taskIds->isNotEmpty()) {
                $tugasCount = TaskSubmission::whereIn('task_id', $taskIds)
                    ->whereIn('status', ['submitted', 'revision'])
                    ->whereNull('viewed_at')
                    ->count();
            }

            return response()->json([
                'pengajuan_count' => $pengajuanCount,
                'tugas_count'     => $tugasCount,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching badge counts: ' . $e->getMessage());
            return response()->json(['pengajuan_count' => 0, 'tugas_count' => 0]);
        }
    }
}
