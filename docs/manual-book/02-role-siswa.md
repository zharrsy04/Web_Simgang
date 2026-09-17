# BUKU PANDUAN PENGGUNA
# WEBSITE SIMGANG
# (Sistem Informasi Magang)

**BAGIAN 2 — PANDUAN UNTUK ROLE SISWA/MAHASISWA**

**Sistem Pelayanan Manajemen Magang Berbasis Website**  
**Dinas Komunikasi dan Informatika Kabupaten Cilacap**

[Logo Diskominfo Kabupaten Cilacap]

**2026**

---

# DAFTAR ISI

**Catatan:** Untuk versi digital (Markdown), gunakan fitur pencarian (Ctrl+F) atau scroll untuk navigasi antar bagian.

---

**BAGIAN 1 — PENDAHULUAN**
- 1.1 Tentang Role Siswa/Mahasiswa
- 1.2 Cara Login sebagai Siswa
- 1.3 Fitur yang Tersedia untuk Siswa

**BAGIAN 2 — PANDUAN FITUR SISWA/MAHASISWA**
- 2.1 Dashboard Siswa
- 2.2 Formulir Pengajuan Magang
- 2.3 Halaman Menunggu Verifikasi
- 2.4 Detail Pengajuan Magang
- 2.5 Halaman Profil Siswa
- 2.6 Halaman Nilai/Penilaian
- 2.7 Presensi/Absensi Harian
- 2.8 Daftar Tugas (Tasks)
- 2.9 Detail Tugas dan Pengumpulan

**PENUTUP**

---

# BAGIAN 1 — PENDAHULUAN

## 1.1 Tentang Role Siswa/Mahasiswa

**Role Siswa/Mahasiswa** adalah pengguna utama sistem SIMGANG yang sedang atau akan melaksanakan kegiatan magang di Dinas Komunikasi dan Informatika Kabupaten Cilacap. 

Sebagai Siswa/Mahasiswa, Anda dapat:
- Mengajukan permohonan magang secara online
- Memonitor status pengajuan magang
- Melakukan presensi/absensi harian
- Menerima dan mengerjakan tugas dari Mentor
- Melihat nilai/penilaian kinerja magang
- Mengelola profil dan dokumen pribadi
- Mengunduh sertifikat magang setelah selesai

## 1.2 Cara Login sebagai Siswa

1. Buka website SIMGANG (http://simgang.cilacapkab.go.id)
2. Klik tombol **"Login"** pada navigation bar
3. Masukkan **Email** dan **Password** yang Anda daftarkan saat registrasi
4. Klik tombol **"Login"**
5. Sistem akan mengarahkan Anda ke **Dashboard Siswa**

**Catatan:** 
- Jika belum memiliki akun, lakukan **Registrasi** terlebih dahulu di halaman Register
- Akun Siswa/Mahasiswa dapat dibuat sendiri (self-registration)
- Pastikan menggunakan email yang valid untuk proses verifikasi


## 1.3 Fitur yang Tersedia untuk Siswa

Setelah login, Siswa/Mahasiswa dapat mengakses fitur-fitur berikut:

| No | Fitur | Deskripsi |
|----|-------|-----------|
| 1 | Dashboard | Melihat ringkasan status magang, pengumuman, statistik |
| 2 | Pengajuan Magang | Mengisi formulir pengajuan magang baru |
| 3 | Status Pengajuan | Memonitor status verifikasi pengajuan |
| 4 | Profil | Mengelola data pribadi, upload foto profil |
| 5 | Presensi | Melakukan absensi masuk dan pulang setiap hari |
| 6 | Daftar Tugas | Melihat tugas yang diberikan oleh Mentor |
| 7 | Pengumpulan Tugas | Mengumpulkan hasil pekerjaan tugas |
| 8 | Nilai | Melihat hasil penilaian dari Mentor |
| 9 | Logout | Keluar dari sistem |

---

# BAGIAN 2 — PANDUAN FITUR SISWA/MAHASISWA

## 2.1 Dashboard Siswa

**Dashboard Siswa** adalah halaman utama yang tampil pertama kali setelah Siswa/Mahasiswa berhasil login. Halaman ini menampilkan ringkasan informasi penting terkait magang dan merupakan pusat navigasi ke fitur-fitur lainnya.

### Informasi yang Ditampilkan

Dashboard Siswa menampilkan beberapa panel informasi:


1. **Panel Selamat Datang**
   - Nama lengkap Siswa
   - Informasi status magang (Aktif/Menunggu/Selesai)
   - Periode magang (tanggal mulai - selesai)

2. **Statistik Pribadi**
   - Jumlah hari hadir (presensi)
   - Jumlah tugas yang harus dikerjakan
   - Jumlah tugas yang sudah dikumpulkan
   - Nilai rata-rata (jika sudah ada penilaian)

3. **Pengumuman Terbaru**
   - Pengumuman dari Mentor atau Sekretariat
   - Pemberitahuan tugas baru
   - Informasi penting lainnya

4. **Status Pengajuan Magang**
   - Jika pengajuan masih pending: Menampilkan status "Menunggu Verifikasi"
   - Jika sudah approve: Menampilkan info bidang magang dan mentor pembimbing
   - Jika ditolak: Menampilkan alasan penolakan

5. **Quick Actions (Aksi Cepat)**
   - Tombol untuk Presensi Masuk/Pulang
   - Tombol ke halaman Tugas
   - Tombol ke halaman Profil

### Langkah Penggunaan

1. Setelah login, sistem otomatis menampilkan Dashboard Siswa
2. Baca informasi penting yang ditampilkan (pengumuman, status)
3. Gunakan menu sidebar kiri untuk navigasi ke fitur lain:
   - Klik **"Dashboard"** untuk kembali ke halaman ini
   - Klik **"Profil"** untuk mengelola data pribadi
   - Klik **"Presensi"** untuk absensi
   - Klik **"Tugas"** untuk melihat daftar tugas
   - Klik **"Nilai"** untuk melihat penilaian
4. Gunakan tombol Quick Actions untuk akses cepat fitur utama

<!-- SS Dashboard Siswa -->

### File Terkait

**Controller:**
- `app/Http/Controllers/SiswaDashboardController.php`
  - `dashboard()` — Menampilkan halaman dashboard dengan data statistik siswa

**View:**
- `resources/views/siswa/dashboard.blade.php`

**Model:**
- `app/Models/User.php`
- `app/Models/SertifikatPKL.php`
- `app/Models/PengajuanMagang.php`
- `app/Models/Attendance.php`
- `app/Models/Task.php`

### Tabel Database Terkait
- `users` — Data akun siswa
- `tbl_sertifikat_pkl` — Data sertifikat dan info magang siswa
- `pengajuan_magang` — Status pengajuan magang
- `attendances` — Data presensi harian
- `tasks` — Daftar tugas yang diberikan mentor

---


## 2.2 Formulir Pengajuan Magang

**Formulir Pengajuan Magang** adalah form untuk mengajukan permohonan magang ke Dinas Komunikasi dan Informatika Kabupaten Cilacap. Setiap siswa/mahasiswa yang ingin magang harus mengisi form ini terlebih dahulu.

### Informasi yang Harus Diisi

Form pengajuan terdiri dari beberapa bagian:

1. **Data Pribadi**
   - Nama lengkap
   - NIM/NIS
   - Tempat, tanggal lahir
   - Email
   - Nomor telepon (opsional)

2. **Data Akademik**
   - Asal sekolah/universitas
   - Jurusan/program studi
   - Semester/tingkat kelas

3. **Data Magang**
   - Bidang magang pilihan (Aplikasi/Infrastruktur/Multimedia)
   - Tanggal mulai magang (yang diinginkan)
   - Tanggal selesai magang (yang diinginkan)
   - Alasan mengajukan magang

4. **Dokumen Persyaratan**
   - Upload surat pengantar dari sekolah/universitas
   - Upload proposal magang (opsional)
   - Format file: PDF, maksimal 5MB

### Langkah Mengisi Formulir Pengajuan

1. Login ke sistem dengan akun siswa
2. Jika belum pernah mengajukan, sistem akan otomatis menampilkan form pengajuan
3. Atau klik menu **"Pengajuan Magang"** di sidebar
4. Isi semua field yang wajib (*):
   - Data pribadi lengkap
   - Data akademik
   - Pilih bidang magang sesuai minat
   - Tentukan periode magang (minimal 1 bulan)
5. Upload dokumen persyaratan:
   - Klik tombol **"Pilih File"**
   - Pilih file surat pengantar (PDF)
   - Maksimal ukuran 5MB
6. Review semua data yang sudah diisi
7. Klik tombol **"Kirim Pengajuan"**
8. Sistem akan menyimpan pengajuan dan mengirim notifikasi ke Mentor/Sekretariat
9. Status pengajuan akan berubah menjadi **"Menunggu Verifikasi"**

### Validasi Form

Sistem akan melakukan validasi:
- Semua field wajib (*) harus diisi
- Format tanggal harus valid
- Tanggal selesai harus lebih besar dari tanggal mulai
- Dokumen wajib diupload
- Format dokumen harus PDF
- Ukuran file maksimal 5MB

Jika ada yang tidak valid, sistem akan menampilkan pesan error dan form tidak bisa disubmit.

<!-- SS Form Pengajuan Magang -->

### File Terkait

**Controller:**
- `app/Http/Controllers/PengajuanMagangController.php`
  - `showForm()` — Menampilkan form pengajuan
  - `store()` — Menyimpan data pengajuan

**View:**
- `resources/views/siswa/form-pengajuan.blade.php`

**Model:**
- `app/Models/PengajuanMagang.php`
- `app/Models/User.php`

### Tabel Database Terkait
- `pengajuan_magang` — Data pengajuan magang
- `users` — Data akun siswa yang mengajukan

---

## 2.3 Halaman Menunggu Verifikasi

**Halaman Menunggu Verifikasi** adalah halaman yang tampil setelah siswa mengajukan permohonan magang. Halaman ini menampilkan status pengajuan dan informasi bahwa pengajuan sedang dalam proses review oleh Mentor/Sekretariat.

### Informasi yang Ditampilkan

1. **Status Pengajuan**
   - Badge/label "Menunggu Verifikasi"
   - Icon loading/pending

2. **Ringkasan Pengajuan**
   - Nama lengkap
   - Bidang magang yang dipilih
   - Periode magang (tanggal mulai - selesai)
   - Tanggal pengajuan

3. **Informasi Proses**
   - Estimasi waktu verifikasi: 1-3 hari kerja
   - Penjelasan bahwa Mentor/Sekretariat akan mereview pengajuan
   - Notifikasi akan dikirim jika ada keputusan (Approve/Reject)

4. **Tombol Aksi**
   - **"Lihat Detail Pengajuan"** — Melihat data pengajuan lengkap
   - **"Edit Pengajuan"** — Mengubah data pengajuan (jika masih pending)
   - **"Batalkan Pengajuan"** — Membatalkan pengajuan (jika berubah pikiran)

### Langkah Penggunaan

1. Setelah submit pengajuan, sistem otomatis redirect ke halaman ini
2. Atau bisa diakses dengan klik menu **"Status Pengajuan"** di sidebar
3. Baca informasi status pengajuan
4. Tunggu notifikasi dari sistem (email/dashboard) untuk mengetahui hasil verifikasi
5. Jika ingin mengubah data pengajuan, klik **"Edit Pengajuan"**
6. Jika ingin membatalkan, klik **"Batalkan Pengajuan"**

### Status Pengajuan yang Mungkin

- **Pending** — Menunggu verifikasi (status awal)
- **Approved** — Pengajuan disetujui (bisa mulai magang)
- **Rejected** — Pengajuan ditolak (lihat alasan penolakan)

<!-- SS Status Pending -->

### File Terkait

**Controller:**
- `app/Http/Controllers/SiswaDashboardController.php`
  - `checkPengajuanStatus()` — Cek status pengajuan
- `app/Http/Controllers/PengajuanMagangController.php`
  - `detailSiswa()` — Detail pengajuan siswa

**View:**
- `resources/views/siswa/status-pending.blade.php`

**Model:**
- `app/Models/PengajuanMagang.php`

### Tabel Database Terkait
- `pengajuan_magang` — Status dan detail pengajuan

---

## 2.4 Detail Pengajuan Magang

**Halaman Detail Pengajuan** menampilkan informasi lengkap tentang pengajuan magang yang sudah disubmit, termasuk data yang diisi dan dokumen yang diupload.

### Informasi yang Ditampilkan

1. **Status Pengajuan**
   - Badge status (Pending/Approved/Rejected)
   - Tanggal pengajuan
   - Tanggal diverifikasi (jika sudah)

2. **Data Pengajuan Lengkap**
   - Semua data pribadi yang diisi
   - Data akademik
   - Bidang magang pilihan
   - Periode magang

3. **Dokumen yang Diupload**
   - Link download surat pengantar
   - Link download proposal (jika ada)

4. **Hasil Verifikasi** (jika sudah diverifikasi)
   - Nama mentor/sekretariat yang memverifikasi
   - Tanggal verifikasi
   - Catatan/komentar (jika ada)
   - **Alasan penolakan** (jika rejected)

### Langkah Penggunaan

1. Buka halaman **"Status Pengajuan"**
2. Klik tombol **"Lihat Detail"**
3. Sistem menampilkan detail lengkap pengajuan
4. Download dokumen jika ingin mereview file yang diupload
5. Jika pengajuan ditolak, baca alasan penolakan dengan teliti
6. Jika ingin mengajukan ulang, perbaiki sesuai alasan penolakan

<!-- SS Detail Pengajuan -->

### File Terkait

**Controller:**
- `app/Http/Controllers/PengajuanMagangController.php`
  - `detailSiswa()` — Detail pengajuan untuk siswa

**View:**
- `resources/views/siswa/detail-pengajuan.blade.php`

**Model:**
- `app/Models/PengajuanMagang.php`
- `app/Models/User.php`

### Tabel Database Terkait
- `pengajuan_magang` — Data lengkap pengajuan
- `users` — Data verifikator (mentor/sekretariat)

---

## 2.5 Halaman Profil Siswa

**Halaman Profil** adalah tempat untuk melihat dan mengelola data profil siswa/mahasiswa, termasuk foto profil, data pribadi, dan data akademik.

### Informasi yang Ditampilkan

1. **Foto Profil**
   - Foto profil siswa (jika sudah diupload)
   - Placeholder default (jika belum ada foto)
   - Tombol **"Ganti Foto"**

2. **Data Pribadi**
   - Nama lengkap
   - NIM/NIS
   - Tempat, tanggal lahir
   - Email
   - Nomor telepon

3. **Data Akademik**
   - Asal sekolah/universitas
   - Jurusan/program studi
   - Semester/tingkat kelas

4. **Data Magang** (jika sudah approved)
   - Bidang magang
   - Periode magang
   - Nama mentor pembimbing
   - Status magang (Aktif/Selesai)

### Fitur yang Tersedia

1. **Upload/Ganti Foto Profil**
   - Format: JPG, PNG
   - Maksimal ukuran: 2MB
   - Foto akan di-crop otomatis menjadi persegi

2. **Edit Profil**
   - Update data pribadi (nama, telepon)
   - Email tidak bisa diubah (karena untuk login)

3. **Lihat Dokumen**
   - Download surat pengantar
   - Download dokumen lain yang pernah diupload

### Langkah Upload Foto Profil

1. Klik menu **"Profil"** di sidebar
2. Klik tombol **"Ganti Foto"** atau ikon kamera pada foto profil
3. Pilih file foto dari komputer
4. Sistem akan melakukan crop otomatis
5. Klik **"Simpan"**
6. Foto profil berhasil diupdate

### Langkah Edit Profil

1. Buka halaman Profil
2. Klik tombol **"Edit Profil"**
3. Update data yang ingin diubah
4. Klik **"Simpan Perubahan"**
5. Data profil berhasil diupdate

<!-- SS Profil Siswa -->

### File Terkait

**Controller:**
- `app/Http/Controllers/SiswaDashboardController.php`
  - `profil()` — Menampilkan halaman profil
  - `uploadPhoto()` — Upload foto profil
  - `profilById($id)` — Detail profil by ID

**View:**
- `resources/views/siswa/profil.blade.php`

**Model:**
- `app/Models/User.php`
- `app/Models/SertifikatPKL.php`
- `app/Models/PengajuanMagang.php`

### Tabel Database Terkait
- `users` — Data akun dan foto profil siswa
- `tbl_sertifikat_pkl` — Data magang siswa
- `pengajuan_magang` — Data pengajuan

---

## 2.6 Halaman Nilai/Penilaian

**Halaman Nilai** menampilkan hasil penilaian kinerja magang yang diberikan oleh Mentor pembimbing. Nilai ini merupakan indikator pencapaian selama melaksanakan magang.

### Informasi yang Ditampilkan

1. **Ringkasan Nilai**
   - Nilai rata-rata keseluruhan
   - Predikat (Sangat Baik/Baik/Cukup/Kurang)
   - Status penilaian (Sudah Dinilai/Belum)

2. **Detail Penilaian per Aspek**
   - **Kedisiplinan** (0-100)
   - **Tanggung Jawab** (0-100)
   - **Kerjasama** (0-100)
   - **Inisiatif** (0-100)
   - **Keterampilan Teknis** (0-100)

3. **Catatan/Feedback dari Mentor**
   - Komentar mentor tentang kinerja siswa
   - Saran perbaikan
   - Apresiasi/kelebihan yang dimiliki

4. **Grafik Visual** (opsional)
   - Chart radar menampilkan nilai per aspek
   - Visualisasi untuk memudahkan memahami kekuatan dan kelemahan

### Langkah Melihat Nilai

1. Klik menu **"Nilai"** di sidebar
2. Sistem menampilkan hasil penilaian (jika sudah ada)
3. Jika belum dinilai, akan ada notifikasi:
   > "Penilaian belum tersedia. Mentor akan memberikan penilaian setelah Anda menyelesaikan magang."
4. Review nilai per aspek
5. Baca catatan/feedback dari mentor dengan teliti
6. Gunakan feedback untuk evaluasi diri dan perbaikan

### Catatan Penting

- Penilaian biasanya diberikan di akhir periode magang atau secara periodik (misal: bulanan)
- Nilai akan tampil di sertifikat magang (untuk sertifikat gabungan)
- Jika ada nilai yang kurang memuaskan, diskusikan dengan mentor untuk perbaikan

<!-- SS Halaman Nilai -->

### File Terkait

**Controller:**
- `app/Http/Controllers/SiswaDashboardController.php`
  - `nilai()` — Menampilkan nilai siswa
  - `nilaiById($id)` — Nilai by ID sertifikat

**View:**
- `resources/views/siswa/nilai.blade.php`

**Model:**
- `app/Models/Penilaian.php`
- `app/Models/SertifikatPKL.php`

### Tabel Database Terkait
- `penilaian` — Data nilai siswa per aspek
- `tbl_sertifikat_pkl` — Relasi ke data siswa

---

## 2.7 Presensi/Absensi Harian

**Halaman Presensi** adalah fitur untuk melakukan absensi masuk dan pulang setiap hari selama magang. Presensi ini penting untuk monitoring kehadiran dan kedisiplinan siswa.

### Informasi yang Ditampilkan

1. **Status Presensi Hari Ini**
   - **Belum Absen** — Tampil tombol "Absen Masuk"
   - **Sudah Absen Masuk** — Tampil waktu absen masuk + tombol "Absen Pulang"
   - **Sudah Lengkap** — Tampil waktu masuk dan pulang

2. **Statistik Presensi Bulan Ini**
   - Jumlah hari hadir
   - Jumlah hari tidak hadir (alpha)
   - Jumlah hari izin/sakit
   - Persentase kehadiran

3. **Riwayat Presensi**
   - Tabel riwayat presensi per hari
   - Tanggal
   - Jam masuk
   - Jam pulang
   - Durasi
   - Status (Hadir/Alpha/Izin/Sakit)

4. **Grafik Kehadiran**
   - Chart batang atau kalender visual menampilkan kehadiran per bulan

### Cara Melakukan Presensi

**A. Absen Masuk (Pagi/Tiba di kantor)**
1. Buka halaman **"Presensi"** di sidebar
2. Klik tombol **"Absen Masuk"**
3. Sistem mencatat waktu saat tombol diklik
4. Notifikasi sukses: "Presensi masuk berhasil dicatat pada [waktu]"
5. Tombol berubah menjadi **"Absen Pulang"** (disabled sampai waktunya pulang)

**B. Absen Pulang (Sore/Selesai kerja)**
1. Buka halaman **"Presensi"**
2. Klik tombol **"Absen Pulang"** (aktif setelah jam tertentu)
3. Sistem mencatat waktu pulang dan menghitung durasi kerja
4. Notifikasi sukses: "Presensi pulang berhasil dicatat pada [waktu]. Durasi kerja: [X] jam"
5. Status hari ini berubah menjadi "Sudah Lengkap"

### Aturan Presensi

- Absen masuk: Sebelum jam 09:00 (tepat waktu), setelah jam 09:00 (terlambat)
- Absen pulang: Minimal setelah jam 15:00 (sesuai jam kerja)
- Jika tidak absen sama sekali dalam sehari: Status "Alpha" (tidak hadir)
- Jika ada keperluan izin/sakit: Hubungi mentor terlebih dahulu

### Catatan Penting

- Pastikan melakukan presensi setiap hari saat magang
- Presensi tidak bisa dimanipulasi atau diubah setelah dicatat
- Jika lupa absen atau ada kendala teknis, segera hubungi mentor
- Kehadiran akan menjadi salah satu faktor penilaian

<!-- SS Presensi Masuk -->
<!-- SS Presensi Pulang -->
<!-- SS Riwayat Presensi -->

### File Terkait

**Controller:**
- `app/Http/Controllers/SiswaDashboardController.php`
  - `index()` — Halaman presensi dengan status hari ini
  - `get()` — Get presensi hari ini
  - `save()` — Simpan presensi (masuk/pulang)
  - `stats()` — Statistik presensi bulan ini
  - `history()` — Riwayat presensi
  - `getFullHistory()` — Riwayat lengkap semua bulan

**View:**
- `resources/views/siswa/dashboard.blade.php` (widget presensi)
- View presensi embedded dalam dashboard atau halaman terpisah

**Model:**
- `app/Models/Attendance.php`
- `app/Models/SertifikatPKL.php`
- `app/Models/User.php`

### Tabel Database Terkait
- `attendances` — Data presensi harian siswa
  - `user_id` — ID siswa
  - `sertifikat_pkl_id` — ID sertifikat/magang
  - `tanggal` — Tanggal presensi
  - `jam_masuk` — Waktu absen masuk
  - `jam_pulang` — Waktu absen pulang
  - `durasi` — Durasi kerja (dalam jam)
  - `status` — Status (hadir/alpha/izin/sakit)

---

## 2.8 Daftar Tugas (Tasks)

**Halaman Daftar Tugas** menampilkan semua tugas yang diberikan oleh Mentor pembimbing. Siswa dapat melihat detail tugas, deadline, dan status pengerjaan.

### Informasi yang Ditampilkan

1. **Statistik Tugas**
   - Total jugas
   - Tugas pending (belum dikerjakan)
   - Tugas submitted (sudah dikumpulkan, menunggu penilaian)
   - Tugas graded (sudah dinilai)

2. **Daftar Tugas**
   Setiap tugas menampilkan:
   - Judul tugas
   - Deskripsi singkat
   - Deadline (tenggat waktu)
   - Status pengerjaan:
     - **Pending** — Belum dikerjakan (badge merah)
     - **Submitted** — Sudah dikumpulkan (badge kuning)
     - **Graded** — Sudah dinilai (badge hijau)
   - Tombol **"Lihat Detail"**

3. **Filter Status**
   - Filter tugas berdasarkan status (All/Pending/Submitted/Graded)

### Langkah Melihat Daftar Tugas

1. Klik menu **"Tugas"** di sidebar
2. Sistem menampilkan daftar semua tugas
3. Lihat statistik tugas di bagian atas
4. Browse daftar tugas atau gunakan filter untuk menyaring
5. Perhatikan deadline tugas yang mendekat (akan diberi highlight)
6. Klik **"Lihat Detail"** untuk membuka halaman detail tugas

### Indikator Deadline

- **Hijau** — Deadline masih >7 hari
- **Kuning** — Deadline 3-7 hari
- **Merah** — Deadline <3 hari (segera kerjakan!)
- **Abu-abu** — Deadline sudah lewat (terlambat)

<!-- SS Daftar Tugas -->

### File Terkait

**Controller:**
- `app/Http/Controllers/StudentTaskController.php`
  - `index()` — Menampilkan daftar tugas siswa

**View:**
- `resources/views/siswa/tasks/index.blade.php`

**Model:**
- `app/Models/Task.php`
- `app/Models/TaskSubmission.php`
- `app/Models/SertifikatPKL.php`

### Tabel Database Terkait
- `tasks` — Data tugas dari mentor
  - `sertifikat_pkl_id` — ID siswa yang ditugaskan
  - `title` — Judul tugas
  - `description` — Deskripsi lengkap
  - `due_date` — Deadline tugas
  - `assigned_by` — ID mentor yang memberi tugas
- `task_submissions` — Status pengumpulan tugas
  - `task_id` — ID tugas
  - `student_id` — ID siswa
  - `status` — Status (pending/submitted/graded)

---

## 2.9 Detail Tugas dan Pengumpulan

**Halaman Detail Tugas** menampilkan informasi lengkap tentang sebuah tugas dan form untuk mengumpulkan hasil pekerjaan.

### Informasi yang Ditampilkan

1. **Detail Tugas**
   - Judul tugas
   - Deskripsi lengkap tugas
   - Instruksi pengerjaan
   - Deadline (tenggat waktu)
   - File attachment dari mentor (jika ada)
   - Tanggal tugas diberikan
   - Nama mentor yang memberi tugas

2. **Form Pengumpulan**
   - Upload file hasil pekerjaan
   - Catatan/komentar (opsional)
   - Tombol **"Kumpulkan Tugas"**

3. **Status Submission** (jika sudah dikumpulkan)
   - Tanggal dikumpulkan
   - File yang diupload
   - Catatan yang ditulis
   - Nilai (jika sudah dinilai)
   - Feedback dari mentor (jika sudah dinilai)

### Langkah Mengerjakan dan Mengumpulkan Tugas

1. Buka **"Daftar Tugas"** dan klik **"Lihat Detail"** pada tugas yang ingin dikerjakan
2. Baca deskripsi dan instruksi tugas dengan teliti
3. Download file attachment dari mentor (jika ada) untuk referensi
4. Kerjakan tugas sesuai instruksi
5. Setelah selesai, kembali ke halaman detail tugas
6. Scroll ke bagian **"Pengumpulan Tugas"**
7. Klik tombol **"Pilih File"** untuk upload hasil pekerjaan:
   - Format file: Semua jenis file (PDF, Word, ZIP, gambar, dll.)
   - Maksimal ukuran: 50MB
   - Bisa upload multiple files (jika diperlukan)
8. Tulis catatan/komentar (opsional):
   - Jelaskan hasil pekerjaan Anda
   - Kendala yang dihadapi
   - Pertanyaan untuk mentor
9. Klik tombol **"Kumpulkan Tugas"**
10. Konfirmasi pengumpulan → Klik **"Ya, Kumpulkan"**
11. Sistem menyimpan submission dan mengirim notifikasi ke mentor
12. Status tugas berubah menjadi **"Submitted"**
13. Tunggu mentor mereview dan memberi nilai

### Catatan Penting

- **Deadline**: Pastikan mengumpulkan sebelum deadline. Setelah deadline, sistem masih bisa menerima submission tapi akan ditandai "Terlambat"
- **Edit Submission**: Setelah dikumpulkan, Anda bisa edit/update submission sebelum mentor memberi nilai
- **File**: Pastikan file yang diupload sudah final dan tidak corrupt
- **Backup**: Simpan file hasil pekerjaan di komputer Anda sebagai backup

### Tombol Aksi yang Tersedia

- **"Download Attachment"** — Download file dari mentor
- **"Kumpulkan Tugas"** — Submit hasil pekerjaan (jika belum dikumpulkan)
- **"Update Submission"** — Edit hasil yang sudah dikumpulkan (jika belum dinilai)
- **"Lihat Nilai"** — Lihat nilai dan feedback (jika sudah dinilai)

<!-- SS Detail Tugas -->
<!-- SS Form Pengumpulan -->
<!-- SS Status Submitted -->

### File Terkait

**Controller:**
- `app/Http/Controllers/StudentTaskController.php`
  - `show($id)` — Detail tugas dan status submission
  - `submit(Request $request, $id)` — Submit/kumpulkan tugas
  - `deleteFile($id)` — Hapus file submission (sebelum dinilai)

**View:**
- `resources/views/siswa/tasks/show.blade.php`

**Model:**
- `app/Models/Task.php`
- `app/Models/TaskSubmission.php`
- `app/Models/SertifikatPKL.php`
- `app/Models/User.php`

### Tabel Database Terkait
- `tasks` — Data tugas
- `task_submissions` — Data pengumpulan tugas
  - `task_id` — ID tugas
  - `student_id` — ID siswa yang mengumpulkan
  - `notes` — Catatan dari siswa
  - `file_paths` — Array path file yang diupload
  - `status` — Status (pending/submitted/graded)
  - `submitted_at` — Waktu pengumpulan
  - `grade` — Nilai (jika sudah dinilai)
  - `feedback` — Feedback dari mentor

---

# PENUTUP

Demikian **Buku Panduan Pengguna Website SIMGANG - Bagian 2: Panduan untuk Role Siswa/Mahasiswa** ini disusun untuk membantu siswa/mahasiswa dalam menggunakan sistem magang di Dinas Komunikasi dan Informatika Kabupaten Cilacap.

Dengan mengikuti panduan ini, diharapkan siswa/mahasiswa dapat:
- Mengajukan permohonan magang dengan benar
- Melakukan presensi harian secara konsisten
- Mengerjakan dan mengumpulkan tugas tepat waktu
- Memahami penilaian kinerja magang
- Memanfaatkan semua fitur yang tersedia untuk memaksimalkan pengalaman magang

Apabila terdapat kendala dalam penggunaan sistem, silakan menghubungi:
- **Mentor Pembimbing** (melalui fitur chat/pengumuman di dashboard)
- **Sekretariat SIMGANG** (melalui halaman Contact)

Selamat melaksanakan magang dan semoga sukses! 🎓

---

**Dinas Komunikasi dan Informatika**  
**Kabupaten Cilacap**

**Alamat:** [Alamat lengkap Diskominfo Cilacap]  
**Telepon:** [Nomor telepon]  
**Email:** [Email resmi]  
**Website:** simgang.cilacapkab.go.id

---

**Terakhir Diperbarui:** 6 Agustus 2026  
**Versi Dokumen:** 1.0
