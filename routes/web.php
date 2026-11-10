<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SiswaDashboardController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\MentorAttendanceController;
use App\Http\Controllers\PortofolioController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\LandingCalendarController;
use App\Http\Controllers\SekretariatController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\StudentTaskController;
use App\Http\Controllers\PengajuanMagangController;
use App\Http\Controllers\MentorController;
use App\Http\Controllers\PresensiMonitoringController;
use Illuminate\Support\Facades\Route;

// --- Login & Register ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.process');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.process');
    Route::get('/captcha', [CaptchaController::class, 'generate'])->name('captcha.generate');
});


// Redirect root to landing page
Route::get('/', function () {
    return view('landing.home');
})->name('home');


// Landing (tanpa login)
Route::get('/', [LandingController::class, 'home'])->name('home');
Route::get('/portfolio', [LandingController::class, 'portfolio'])->name('portfolio');
Route::get('/contact', [LandingController::class, 'contact'])->name('contact');
Route::get('/riwayat', [HistoryController::class, 'index'])->name('history');
Route::get('/riwayat/export', [HistoryController::class, 'export'])->name('history.export');
Route::get('/certificate/download-form', [HistoryController::class, 'downloadCertificateForm'])->name('certificate.download-form');
Route::post('/certificate/validate', [HistoryController::class, 'validateAndDownloadCertificate'])->name('certificate.validate');

// CRUD Riwayat - Mentor Only
Route::get('/riwayat/create', [HistoryController::class, 'create'])->name('history.create')->middleware(['auth', 'roleProtection:mentor']);
Route::post('/riwayat', [HistoryController::class, 'store'])->name('history.store')->middleware(['auth', 'roleProtection:mentor']);
Route::get('/riwayat/{id}/edit', [HistoryController::class, 'edit'])->name('history.edit')->middleware(['auth', 'roleProtection:mentor']);
Route::put('/riwayat/{id}', [HistoryController::class, 'update'])->name('history.update')->middleware(['auth', 'roleProtection:mentor']);
Route::delete('/riwayat/{id}', [HistoryController::class, 'destroy'])->name('history.destroy')->middleware(['auth', 'roleProtection:mentor']);

Route::get('/keluar', [LoginController::class, 'keluar'])->name('keluar')->middleware('auth');
// Logout route - DIPERBAIKI: menambahkan middleware guest untuk halaman yang tidak perlu auth
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');


// Landing - Kalender Magang (Public)
Route::get('/kalender-magang', [LandingCalendarController::class, 'index'])->name('landing-calendar');
Route::get('/api/internship-data', [LandingCalendarController::class, 'getInternshipData'])->name('api.internship-data');

// Redirect root to login page
// Route::redirect('/', '/login');

// Middleware auth - Semua route yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {
    // Dashboard (untuk role selain siswa, misal admin/mentor)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/simpanpengumuman', [DashboardController::class, 'simpanpengumuman'])->name('notifikasi');

    // Rute-rute spesifik untuk Dashboard (Admin/Mentor)
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/siswaaktif', [DashboardController::class, 'siswaAktif'])->name('siswaaktif');
        Route::get('/menunggumagang', [DashboardController::class, 'menungguMagang'])->name('menunggumagang');
        Route::get('/sekolah', [DashboardController::class, 'sekolah'])->name('sekolah');
        Route::get('/siswalulus', [DashboardController::class, 'siswaLulus'])->name('siswalulus');
        Route::get('/statistik-magang', [DashboardController::class, 'statistikMagang'])->name('statistik.magang');
        Route::get('/calendar/full', [DashboardController::class, 'full'])->name('calendar.full');
    });

    // Mentor routes - DIPERBAIKI
    Route::prefix('mentor')->name('mentor.')->middleware('auth', 'roleProtection:mentor,sekretariat')->group(function () {
    Route::get('/dashboard', [SertifikatController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [\App\Http\Controllers\MentorProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [\App\Http\Controllers\MentorProfileController::class, 'update'])->name('profile.update');
    Route::get('/export-data', [SertifikatController::class, 'exportData'])->name('exportData');
    Route::get('/', [SertifikatController::class, 'index'])->name('index');
    Route::get('/create', [SertifikatController::class, 'create'])->name('create');
    Route::post('/', [SertifikatController::class, 'store'])->name('store');
    // Route portofolio yang kamu mau
    Route::get('/portofolio', [PortofolioController::class, 'indexMentor'])->name('portofolio');

    // Quota Management (Must be defined before /{sertifikat} wildcard to avoid route conflict)
    Route::get('/kuota', [\App\Http\Controllers\LandingCalendarController::class, 'manageQuota'])->name('manage-quota');

    // Absensi Siswa Bimbingan
    Route::get('/absensi-siswa', [\App\Http\Controllers\AttendanceController::class, 'mentorIndex'])->name('absensi.index');
    Route::get('/absensi-siswa/{idSertifikat}/riwayat', [\App\Http\Controllers\AttendanceController::class, 'mentorRiwayat'])->name('absensi.riwayat');

    // Logbook Kegiatan Siswa Bimbingan
    Route::get('/logbook-siswa', [\App\Http\Controllers\LogbookController::class, 'mentorIndex'])->name('logbook.index');
    Route::get('/logbook-siswa/{id}', [\App\Http\Controllers\LogbookController::class, 'mentorShow'])->name('logbook.show');
    Route::post('/logbook-siswa/{id}/review', [\App\Http\Controllers\LogbookController::class, 'mentorReview'])->name('logbook.review');

    // CRUD Data Mentor (Mentor & Sekretariat only) - Must be before wildcard routes
    Route::prefix('data-mentor')->name('data-mentor.')->middleware(['roleProtection:mentor,sekretariat'])->group(function () {
        Route::get('/', [MentorController::class, 'index'])->name('index');
        Route::get('/create', [MentorController::class, 'create'])->name('create');
        Route::post('/', [MentorController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [MentorController::class, 'edit'])->name('edit');
        Route::put('/{id}', [MentorController::class, 'update'])->name('update');
        Route::delete('/{id}', [MentorController::class, 'destroy'])->name('destroy');
        Route::post('/remove-student', [MentorController::class, 'removeStudent'])->name('remove-student');
        Route::get('/api/by-bidang/{bidang}', [MentorController::class, 'getByBidang'])->name('api.by-bidang');
        Route::get('/direktori', [MentorController::class, 'direktori'])->name('direktori');
    });

    // Daftar Tugas Mentor
    Route::prefix('tasks')->name('tasks.')->group(function () {
        Route::get('/', [\App\Http\Controllers\MentorTaskController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\MentorTaskController::class, 'create'])->name('create');
        Route::get('/daftar-tugas', [\App\Http\Controllers\MentorTaskController::class, 'daftarTugas'])->name('daftar-tugas');
        Route::post('/daftar-tugas/store', [\App\Http\Controllers\MentorTaskController::class, 'store'])->name('store');
        Route::get('/tugas-siswa/{siswaId}', [\App\Http\Controllers\MentorTaskController::class, 'tugasSiswa'])->name('tugas-siswa');
        Route::get('/download-submission/{submissionId}', [\App\Http\Controllers\MentorTaskController::class, 'downloadSubmission'])->name('download-submission');
        Route::get('/api/task/{taskId}', [\App\Http\Controllers\MentorTaskController::class, 'getTask'])->name('api.get-task');
        Route::get('/api/badge-counts', [\App\Http\Controllers\MentorTaskController::class, 'badgeCounts'])->name('api.badge-counts');
        Route::put('/update/{taskId}', [\App\Http\Controllers\MentorTaskController::class, 'update'])->name('update');
        Route::delete('/{taskId}', [\App\Http\Controllers\MentorTaskController::class, 'destroy'])->name('destroy');
        
        // Missing task routes
        Route::get('/{taskId}/show', [\App\Http\Controllers\MentorTaskController::class, 'show'])->name('show');
        Route::get('/{taskId}/edit', [\App\Http\Controllers\MentorTaskController::class, 'edit'])->name('edit');
        Route::get('/{taskId}/submissions', [\App\Http\Controllers\MentorTaskController::class, 'getSubmissions'])->name('submissions');
        Route::post('/submission/{submissionId}/grade', [\App\Http\Controllers\MentorTaskController::class, 'gradeSubmission'])->name('submissions.grade');
    });

    // API endpoints
    Route::get('/api/students-by-bidang', [\App\Http\Controllers\ApiController::class, 'getStudentsByBidang'])->name('api.students-by-bidang');

    Route::get('/{id_sertifikat}', [SertifikatController::class, 'show'])->name('show');
    Route::get('/{id_sertifikat}/edit', [SertifikatController::class, 'edit'])->name('edit');
    Route::put('/{id_sertifikat}', [SertifikatController::class, 'update'])->name('update');
    Route::delete('/{id_sertifikat}', [SertifikatController::class, 'destroy'])->name('destroy');
    Route::get('/cetak-pdf/{sertifikat}', [SertifikatController::class, 'cetakPDF'])->name('cetak-pdf');



    // Perhatikan: URL-nya cukup '/attendance' karena prefix 'mentor' sudah ada
    Route::get('/attendance', [MentorAttendanceController::class, 'index'])->name('attendance.index');

    // API untuk data absensi
    Route::get('/api/attendance', [MentorAttendanceController::class, 'getAttendanceData'])->name('attendance.data');

    Route::get('/portofolio/data', [PortofolioController::class, 'getDataMentor'])->name('portofolio.data');

    // routes/web.php
    Route::get('/portofolio/detail/{id}', [PortofolioController::class, 'show'])->name('mentor.portofolio.detail');

    //route riwayat absensi di mentor
    Route::get('/attendance/history/{id_sertifikat}', [MentorAttendanceController::class, 'getAttendanceHistory'])->name('attendance.history');

});

    // Sekretariat routes
    Route::prefix('sekretariat')->name('sekretariat.')->middleware(['auth', 'roleProtection:sekretariat'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [SekretariatController::class, 'dashboard'])->name('dashboard');
        
        // Pengumuman
        Route::get('/pengumuman', [SekretariatController::class, 'pengumuman'])->name('pengumuman');

        // Antrian Sertifikat
        Route::get('/antrian-sertifikat', [SekretariatController::class, 'antrianSertifikat'])->name('antrian-sertifikat');
        
        // Master Data Instansi
        Route::get('/instansi', [SekretariatController::class, 'instansiIndex'])->name('instansi.index');
        Route::get('/instansi/create', [SekretariatController::class, 'instansiCreate'])->name('instansi.create');
        Route::post('/instansi', [SekretariatController::class, 'instansiStore'])->name('instansi.store');
        Route::get('/instansi/{id}/edit', [SekretariatController::class, 'instansiEdit'])->name('instansi.edit');
        Route::put('/instansi/{id}', [SekretariatController::class, 'instansiUpdate'])->name('instansi.update');
        Route::delete('/instansi/{id}', [SekretariatController::class, 'instansiDestroy'])->name('instansi.destroy');
        Route::post('/instansi/quick-add', [SekretariatController::class, 'instansiQuickAdd'])->name('instansi.quick-add');
        
        // Data Bidang
        Route::get('/bidang/{bidang}', [SekretariatController::class, 'viewField'])->name('view-field');
        
        // Data Mentor
        Route::get('/mentor', [SekretariatController::class, 'dataMentor'])->name('data-mentor');
        
        // Data Siswa Magang
        Route::get('/siswa', [SekretariatController::class, 'dataSiswa'])->name('data-siswa');
        Route::get('/siswa/create', [SekretariatController::class, 'createSiswa'])->name('create-siswa');
        Route::post('/siswa', [SekretariatController::class, 'storeSiswa'])->name('store-siswa');
        Route::get('/siswa/{id}/edit', [SekretariatController::class, 'editSiswa'])->name('edit-siswa');
        Route::put('/siswa/{id}', [SekretariatController::class, 'updateSiswa'])->name('update-siswa');
        Route::delete('/siswa/{id}', [SekretariatController::class, 'deleteSiswa'])->name('delete-siswa');
        
        // Data Pengajuan Magang
        Route::get('/pengajuan', [SekretariatController::class, 'dataPengajuan'])->name('data-pengajuan');
        
        // Data Sertifikat
        Route::get('/sertifikat', [SekretariatController::class, 'dataSertifikat'])->name('data-sertifikat');
        Route::get('/sertifikat/create', [SekretariatController::class, 'createSertifikat'])->name('create-sertifikat');
        Route::post('/sertifikat', [SekretariatController::class, 'storeSertifikat'])->name('store-sertifikat');
        Route::get('/sertifikat/{id}/edit', [SekretariatController::class, 'editSertifikat'])->name('edit-sertifikat');
        Route::put('/sertifikat/{id}', [SekretariatController::class, 'updateSertifikat'])->name('update-sertifikat');
        Route::delete('/sertifikat/{id}', [SekretariatController::class, 'deleteSertifikat'])->name('delete-sertifikat');
        
        // Certificate handover
        Route::post('/sertifikat/{id}/prepare-handover', [SekretariatController::class, 'prepareCertificateForHandover'])->name('prepare-handover');
        Route::post('/sertifikat/{id}/handover', [SekretariatController::class, 'handoverCertificateToMentor'])->name('handover-to-mentor');
        Route::get('/handovers/pending', [SekretariatController::class, 'pendingHandovers'])->name('pending-handovers');
        
        // Print certificate
        Route::get('/sertifikat/{id}/print', [SekretariatController::class, 'printCertificate'])->name('print-certificate');
        
        // Kalender Magang
        Route::get('/kalender', [SekretariatController::class, 'kalendarMagang'])->name('kalender');
    });

    // Dashboard Siswa - Data Management routes
    Route::prefix('dashboardsiswa')->name('dashboardsiswa.')->middleware(['auth', 'roleProtection:siswa'])->group(function () {
        Route::get('/siswaaktif', [SiswaDashboardController::class, 'siswaAktif'])->name('siswaaktif');
        Route::get('/menunggumagang', [SiswaDashboardController::class, 'menungguMagang'])->name('menunggumagang');
        Route::get('/sekolah', [SiswaDashboardController::class, 'sekolah'])->name('sekolah');
        Route::get('/siswalulus', [SiswaDashboardController::class, 'siswaLulus'])->name('siswalulus');
        Route::get('/statistik-magang', [SiswaDashboardController::class, 'statistikMagang'])->name('statistik.magang');
       
    });

    // Siswa routes
    Route::prefix('siswa')->name('siswa.')->middleware(['auth', 'roleProtection:siswa'])->group(function () {
    Route::get('/dashboard', [SiswaDashboardController::class, 'dashboard'])->name('dashboard');

    // Route untuk profil
    Route::get('/profil', [SiswaDashboardController::class, 'profil'])->name('profil');
    Route::get('/profil/{id}', [SiswaDashboardController::class, 'profilById'])->name('profil.show');
    Route::post('/upload-photo', [SiswaDashboardController::class, 'uploadPhoto'])->name('uploadPhoto');
    Route::get('/pdfs/individual', [SiswaDashboardController::class, 'getIndividualPdfs']);
    Route::get('/nilai', [SiswaDashboardController::class, 'nilai'])->name('nilai');
    Route::get('/nilai/{id}', [SiswaDashboardController::class, 'nilaiById'])->name('nilai.show');

    // Route cetak sertifikat untuk siswa (bypass policy mentor)
    Route::get('/cetak-sertifikat/{id_sertifikat}', [SertifikatController::class, 'cetakUntukSiswa'])->name('cetak-sertifikat');

    // Route untuk portofolio siswa
    Route::get('/portofolio', [\App\Http\Controllers\PortofolioController::class, 'index'])->name('portofolio.index');

    // Route Absensi Harian Siswa (Hanya Absen Masuk)
    Route::get('/absensi', [\App\Http\Controllers\AttendanceController::class, 'siswaIndex'])->name('absensi.index');
    Route::post('/absensi', [\App\Http\Controllers\AttendanceController::class, 'siswaStore'])->name('absensi.store');

    // Route Logbook Kegiatan Siswa
    Route::get('/logbook', [\App\Http\Controllers\LogbookController::class, 'siswaIndex'])->name('logbook.index');
    Route::post('/logbook', [\App\Http\Controllers\LogbookController::class, 'siswaStore'])->name('logbook.store');
    Route::put('/logbook/{id}', [\App\Http\Controllers\LogbookController::class, 'siswaUpdate'])->name('logbook.update');
    Route::delete('/logbook/{id}', [\App\Http\Controllers\LogbookController::class, 'siswaDestroy'])->name('logbook.destroy');

    // 📌 Tasks siswa
    Route::prefix('tasks')->name('tasks.')->group(function () {
        Route::get('/', [StudentTaskController::class, 'index'])->name('index');
        Route::get('/{id}', [StudentTaskController::class, 'show'])->name('show');
        Route::post('/{id}/submit', [StudentTaskController::class, 'submit'])->name('submit');
        Route::delete('/submission/{id}/file', [StudentTaskController::class, 'deleteFile'])->name('deleteFile');
    });
});

     // Route detail nilai - bisa diakses mentor dan siswa
    Route::get('/siswa/{id}/nilai', [PenilaianController::class, 'detailNilai'])->name('siswa.shownilai')->middleware('auth');

     // Pengajuan Magang routes - SISWA ONLY
    Route::prefix('pengajuan')->name('pengajuan.')->middleware(['auth', 'roleProtection:siswa'])->group(function () {
        Route::get('/form', [\App\Http\Controllers\PengajuanMagangController::class, 'showForm'])->name('form');
        Route::post('/store', [\App\Http\Controllers\PengajuanMagangController::class, 'store'])->name('store');
        Route::get('/detail', [\App\Http\Controllers\PengajuanMagangController::class, 'detailSiswa'])->name('detailSiswa');
    });

    // Mentor routes untuk approval pengajuan
    Route::prefix('mentor')->name('mentor.')->middleware('auth', 'roleProtection:mentor,sekretariat')->group(function () {
        Route::get('/pengajuan', [\App\Http\Controllers\PengajuanMagangController::class, 'listPengajuan'])->name('pengajuan.list');
        Route::get('/pengajuan/verifikasi', [\App\Http\Controllers\PengajuanMagangController::class, 'verifikasiPengajuan'])->name('verifikasi-pengajuan');
        Route::get('/pengajuan/{id}/detail', [\App\Http\Controllers\PengajuanMagangController::class, 'detailPengajuan'])->name('detail-pengajuan');
        Route::post('/pengajuan/{id}/approve', [\App\Http\Controllers\PengajuanMagangController::class, 'approve'])->name('pengajuan.approve');
        Route::post('/pengajuan/{id}/reject', [\App\Http\Controllers\PengajuanMagangController::class, 'reject'])->name('pengajuan.reject');
        Route::get('/pengajuan/{id}/dokumen', [\App\Http\Controllers\PengajuanMagangController::class, 'downloadDokumen'])->name('pengajuan.download');
    });

    // API endpoint untuk count pengajuan pending (accessible untuk mentor)
    Route::middleware('auth')->prefix('api')->group(function () {
        Route::get('/pengajuan-pending-count', [PengajuanMagangController::class, 'countPending'])->name('api.pengajuan-pending-count');
    });

     
    // Route::get('/siswa/dashboard', 'SiswaDashboardController@dashboard')->middleware('cekrole:siswa');

    // Route::get('/mentor/dashboard', 'SertifikatController@dashboard')->middleware('cekrole:mentor');

    // Sertifikat routes
    Route::prefix('sertifikat')->name('sertifikat.')->middleware('auth')->group(function () {
        Route::get('/{sertifikat}/cetak', [SertifikatController::class, 'cetak'])->name('cetak');
        Route::get('/{id_sertifikat}/cetak-view', [SertifikatController::class, 'cetakGabungview'])->name('cetak-view');
        Route::get('/{id_sertifikat}/cetak-gabungan', [SertifikatController::class, 'cetakGabung'])->name('cetak-gabungan');

        // PDF Viewer routes - Fitur baru
        Route::get('/{id_sertifikat}/pdf-viewer', [SertifikatController::class, 'showPdfViewer'])->name('pdf-viewer');
        Route::get('/{id_sertifikat}/cetak-gabungan-v2', [SertifikatController::class, 'cetakGabungV2'])->name('cetak-gabungan-v2');
        Route::get('/{id_sertifikat}/cetak-gabungan-v3', [SertifikatController::class, 'cetakGabungV3'])->name('cetak-gabungan-v3');
        Route::get('/{id_sertifikat}/cetak-gabungan-v4', [SertifikatController::class, 'cetakGabungV4'])->name('cetak-gabungan-v4');
        Route::get('/{id_sertifikat}/cetak-gabungan-v5', [SertifikatController::class, 'cetakGabungV5'])->name('cetak-gabungan-v5');
        Route::get('/{id_sertifikat}/cetak-gabungan-v6', [SertifikatController::class, 'cetakGabungV6'])->name('cetak-gabungan-v6');
        Route::get('/{id_sertifikat}/cetak-gabungan-v7', [SertifikatController::class, 'cetakGabungV7'])->name('cetak-gabungan-v7');
        Route::get('/{id_sertifikat}/cetak-gabungan-v8', [SertifikatController::class, 'cetakGabungV8'])->name('cetak-gabungan-v8');
        Route::get('/{id_sertifikat}/cetak-gabungan-raw', [SertifikatController::class, 'cetakGabungRaw'])->name('cetak-gabungan-raw');
        Route::get('/{id_sertifikat}/cetak-gabungan-download', [SertifikatController::class, 'cetakGabungDownload'])->name('cetak-gabungan-download');
    });

    // User resource
    Route::resource('user', UserController::class)->except(['show'])->middleware('auth');

    // Penilaian resource
    Route::resource('penilaian', PenilaianController::class)->except(['show'])->middleware('auth');
    Route::get('/penilaian/cetak/{id_sertifikat}', [PenilaianController::class, 'cetak'])->name('penilaian.cetak')->middleware('auth');
    Route::get('/export-pdf/{id_sertifikat}', [PenilaianController::class, 'exportToPDF'])->name('export.pdf')->middleware('auth');
    Route::post('/upload-pdf', [PenilaianController::class, 'uploadIndividual'])->name('upload-pdf')->middleware('auth');
    Route::post('/upload-pdf-individual', [PenilaianController::class, 'uploadIndividual'])->name('pdf.upload.individual')->middleware('auth');
    Route::get('api/pdfs/individual', [SiswaDashboardController::class, 'getIndividualPdfs'])->middleware('auth');
    Route::delete('api/pdfs/{Pdfid}', [SiswaDashboardController::class, 'destroy'])->middleware('auth');


    Route::get('/calendar/full', [DashboardController::class, 'full'])->name('calendar.full');
    Route::post('api/tasks/assign', [DashboardController::class, 'assignTask'])->name('tasks.assign')->middleware('auth');
    Route::get('api/tasks/student/{id?}', [DashboardController::class, 'getTasksForStudent'])->name('api.tasks.student')->middleware('auth');
    Route::get('api/all-students', [DashboardController::class, 'getAllStudents'])->name('api.all.students')->middleware('auth');
    Route::get('api/messages/student', [SiswaDashboardController::class, 'getMessagesForStudent']);
    Route::post('api/tasks/{task_id}/mark-as-read', [SiswaDashboardController::class, 'markTaskAsRead']);

    // Route untuk AJAX operations pengumuman mentor (di dalam auth middleware)
    Route::get('/dashboard/pengumuman/get', [DashboardController::class, 'getAnnouncements'])->name('announcements.get');
    Route::post('/dashboard/pengumuman/store', [DashboardController::class, 'simpanpengumuman'])->name('announcements.store');
    Route::put('/dashboard/pengumuman/update/{id}', [DashboardController::class, 'updatepengumuman'])->name('announcements.update');
    Route::delete('/dashboard/pengumuman/delete/{id}', [DashboardController::class, 'deletepengumuman'])->name('announcements.delete');

    // Monitoring Presensi untuk Mentor & Sekretariat
    Route::get('/presensi/monitoring', [PresensiMonitoringController::class, 'index'])->name('presensi.monitoring');
    Route::get('/presensi/monitoring/{idSertifikat}/detail', [PresensiMonitoringController::class, 'detail'])->name('presensi.monitoring.detail');
});
Route::get('api/internship-students', [DashboardController::class, 'getInternshipStudentsByDate']);


Route::prefix('attendance')->middleware('auth')->group(function () {
    // Ganti 'SiswaDashboardController::class' dengan 'AttendanceController::class'
    // jika Anda tidak memindahkan metodenya
    Route::post('/get', [SiswaDashboardController::class, 'get']);
    Route::post('/save', [SiswaDashboardController::class, 'save']);
    Route::get('/history', [SiswaDashboardController::class, 'history']);
    Route::get('attendance/full-history', [SiswaDashboardController::class, 'getFullHistory']);
    Route::post('/stats', [SiswaDashboardController::class, 'stats']);
    Route::get('/test', [SiswaDashboardController::class, 'testConnection']);
});

Route::get('/getAttendance', [SiswaDashboardController::class, 'getAttendance'])->middleware('auth');
Route::get('/attendance/full-history', [SiswaDashboardController::class, 'getFullHistory'])->middleware('auth');
Route::get('/attendance/history', [SiswaDashboardController::class, 'history'])->middleware('auth');

Route::get('verifikasi/{id}',[SertifikatController::class, 'verifikasi'])->name('verifikasi');

// API Routes for Quota Management (Mentor and Sekretariat)
Route::middleware(['auth'])->prefix('api')->group(function () {
    // Update quota
    Route::put('/kuota-bidang/{id}', [\App\Http\Controllers\LandingCalendarController::class, 'updateQuota'])->middleware('roleProtection:mentor,sekretariat');
    // Add new quota field
    Route::post('/kuota-bidang', [\App\Http\Controllers\LandingCalendarController::class, 'addQuota'])->middleware('roleProtection:mentor,sekretariat');
    // Delete quota field
    Route::delete('/kuota-bidang/{id}', [\App\Http\Controllers\LandingCalendarController::class, 'deleteQuota'])->middleware('roleProtection:mentor,sekretariat');
});
