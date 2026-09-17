<?php
/**
 * Script untuk mengecek data feedback mentor di task_submissions
 * Jalankan dengan: php check_feedback_data.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n=== CEK DATA TASK SUBMISSIONS ===\n\n";

try {
    // Cek semua submissions yang sudah dinilai
    $submissions = DB::table('task_submissions')
        ->where('status', 'graded')
        ->get();
    
    echo "Total submissions dengan status 'graded': " . $submissions->count() . "\n\n";
    
    if ($submissions->count() > 0) {
        echo "Detail submissions yang sudah dinilai:\n";
        echo str_repeat("-", 80) . "\n";
        
        foreach ($submissions as $sub) {
            echo "ID: {$sub->id}\n";
            echo "Task ID: {$sub->task_id}\n";
            echo "Student ID: {$sub->student_id}\n";
            echo "Grade: " . ($sub->grade ?? 'NULL') . "\n";
            echo "Mentor Feedback: " . ($sub->mentor_feedback ? substr($sub->mentor_feedback, 0, 50) . '...' : 'NULL') . "\n";
            echo "Graded At: " . ($sub->graded_at ?? 'NULL') . "\n";
            echo str_repeat("-", 80) . "\n";
        }
    }
    
    // Cek submission untuk task_id = 15 khusus
    echo "\n=== CEK TASK ID 15 KHUSUS ===\n\n";
    $task15 = DB::table('task_submissions')
        ->where('task_id', 15)
        ->get();
    
    if ($task15->count() > 0) {
        echo "Ditemukan " . $task15->count() . " submission untuk task_id=15:\n";
        foreach ($task15 as $sub) {
            echo "\nSubmission ID: {$sub->id}\n";
            echo "Student ID: {$sub->student_id}\n";
            echo "Status: {$sub->status}\n";
            echo "Grade: " . ($sub->grade ?? 'NULL') . "\n";
            echo "Mentor Feedback: " . ($sub->mentor_feedback ?? 'NULL') . "\n";
            echo "Submitted At: " . ($sub->submitted_at ?? 'NULL') . "\n";
            echo "Graded At: " . ($sub->graded_at ?? 'NULL') . "\n";
        }
    } else {
        echo "TIDAK ada submission untuk task_id=15\n";
    }
    
    // Cek semua task IDs yang ada
    echo "\n=== DAFTAR SEMUA TASK IDS ===\n\n";
    $allTasks = DB::table('tasks')
        ->select('id', 'title', 'sertifikat_pkl_id', 'assigned_by')
        ->orderBy('id', 'desc')
        ->limit(10)
        ->get();
    
    echo "10 Task terbaru:\n";
    foreach ($allTasks as $task) {
        echo "- Task ID {$task->id}: {$task->title}\n";
    }
    
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== SELESAI ===\n\n";
