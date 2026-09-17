# BUKU PANDUAN PENGGUNA
# WEBSITE SIMGANG
# (Sistem Informasi Magang)

**BAGIAN 4 — PANDUAN UNTUK ROLE SEKRETARIAT**

**Sistem Pelayanan Manajemen Magang Berbasis Website**  
**Dinas Komunikasi dan Informatika Kabupaten Cilacap**

[Logo Diskominfo Kabupaten Cilacap]

**2026**

---

# DAFTAR ISI

**Catatan:** Untuk versi digital (Markdown), gunakan fitur pencarian (Ctrl+F) atau scroll untuk navigasi antar bagian.

---

**BAGIAN 1 — PENDAHULUAN**
- 1.1 Tentang Role Sekretariat
- 1.2 Cara Login sebagai Sekretariat
- 1.3 Fitur yang Tersedia untuk Sekretariat

**BAGIAN 2 — PANDUAN FITUR SEKRETARIAT**
- 2.1 Dashboard Sekretariat
- 2.2 Antrian Sertifikat
- 2.3 Verifikasi Pengajuan Magang
- 2.4 Data Siswa Magang
- 2.5 Lembaga Pendidikan
- 2.6 Data Lulus Magang
- 2.7 Data Mentor
- 2.8 Manajemen Pengguna
- 2.9 Fitur Lainnya (Sama dengan Mentor)

**PENUTUP**

---

# BAGIAN 1 — PENDAHULUAN

## 1.1 Tentang Role Sekretariat

**Role Sekretariat** adalah pengguna sistem SIMGANG dengan akses paling lengkap untuk mengelola seluruh aspek administrasi magang di Dinas Komunikasi dan Informatika Kabupaten Cilacap. Sekretariat berperan sebagai koordinator dan supervisor yang mengawasi semua proses magang lintas bidang.


**Perbedaan dengan Role Mentor:**
- **Mentor**: Fokus pada pembimbingan siswa di bidang tertentu (Aplikasi/Infrastruktur/Multimedia)
- **Sekretariat**: Akses ke SEMUA data lintas bidang, fokus pada administrasi dan koordinasi sistem

Sebagai Sekretariat, Anda dapat:
- Mengakses data semua siswa magang dari semua bidang
- Memverifikasi pengajuan magang
- Mengelola antrian sertifikat
- Mengelola data lembaga pendidikan
- Mengelola akun mentor dan pengguna sistem
- Memonitor statistik keseluruhan
- Mencetak sertifikat untuk semua bidang
- Koordinasi antar mentor dan bidang

## 1.2 Cara Login sebagai Sekretariat

1. Buka website SIMGANG (http://simgang.cilacapkab.go.id)
2. Klik tombol **"Login"** pada navigation bar
3. Masukkan **Username** dan **Password** yang diberikan oleh Admin
4. Klik tombol **"Login"**
5. Sistem akan mengarahkan Anda ke **Dashboard Sekretariat**

**Catatan:** 
- Akun Sekretariat dibuat oleh Admin sistem
- Hanya staff tertentu yang diberi akses role Sekretariat
- Hubungi Admin jika lupa password

## 1.3 Fitur yang Tersedia untuk Sekretariat

Sekretariat memiliki akses ke semua fitur Mentor PLUS fitur khusus:

| No | Fitur | Deskripsi | Akses Data |
|----|-------|-----------|------------|
| 1 | Dashboard | Statistik lengkap semua bidang | Lintas bidang |
| 2 | Antrian Sertifikat | Kelola antrian pencetakan sertifikat | Semua bidang |
| 3 | Verifikasi Pengajuan | Approve/reject pengajuan | Semua bidang |
| 4 | Data Siswa | Kelola siswa magang | Semua bidang |
| 5 | Lembaga Pendidikan | Kelola data sekolah/universitas | Semua |
| 6 | Data Lulus | Alumni magang | Semua bidang |
| 7 | Data Mentor | Kelola akun mentor | Semua bidang |
| 8 | Manajemen Pengguna | Kelola akun sistem | Semua |
| 9 | Cetak Sertifikat | Generate sertifikat | Semua bidang |
| 10 | Penilaian | Input/lihat nilai | Semua bidang |
| 11 | Tugas | Kelola tugas siswa | Semua bidang |
| 12 | Pengumuman | Buat pengumuman | Semua siswa |
| 13 | Kalender & Kuota | Kelola jadwal magang | Semua bidang |

---

# BAGIAN 2 — PANDUAN FITUR SEKRETARIAT

## 2.1 Dashboard Sekretariat

**Dashboard Sekretariat** menampilkan overview lengkap sistem magang dengan data agregat dari semua bidang (Aplikasi, Infrastruktur, Multimedia).

### Informasi yang Ditampilkan

1. **Statistik Keseluruhan**
   - Total siswa aktif magang (semua bidang)
   - Total pengajuan menunggu verifikasi
   - Total siswa yang sudah lulus
   - Total lembaga pendidikan
   - Sisa kuota per bidang

2. **Grafik Dashboard**
   - Chart distribusi siswa per bidang
   - Chart jumlah siswa per lembaga pendidikan
   - Timeline magang (kalender)
   - Trend pengajuan per bulan

3. **Widget Penting**
   - **Antrian Sertifikat** — Jumlah sertifikat yang menunggu cetak
   - **Pengajuan Pending** — Notifikasi pengajuan baru
   - **Handover Pending** — Sertifikat yang menunggu diserahkan ke mentor

4. **Quick Actions**
   - Tombol **"Verifikasi Pengajuan"**
   - Tombol **"Antrian Sertifikat"**
   - Tombol **"Tambah Siswa"**
   - Tombol **"Buat Pengumuman"**

### Perbedaan dengan Dashboard Mentor

**Dashboard Mentor:**
- Data hanya siswa di bidang mentor tersebut
- Statistik per bidang

**Dashboard Sekretariat:**
- Data SEMUA siswa dari SEMUA bidang
- Statistik agregat keseluruhan sistem
- Widget khusus: Antrian Sertifikat, Handover Pending

### Langkah Penggunaan

1. Login sebagai Sekretariat
2. Sistem otomatis menampilkan Dashboard dengan data terbaru
3. Review statistik keseluruhan
4. Perhatikan notifikasi/alert jika ada:
   - Pengajuan baru yang perlu diverifikasi
   - Antrian sertifikat yang menumpuk
   - Handover yang pending
5. Gunakan Quick Actions atau sidebar untuk navigasi ke fitur lain

<!-- SS Dashboard Sekretariat -->

### File Terkait

**Controller:**
- `app/Http/Controllers/SekretariatController.php`
  - `dashboard()` — Dashboard dengan data agregat semua bidang

**View:**
- `resources/views/sekretariat/dashboard.blade.php`

**Model:**
- `app/Models/SertifikatPKL.php`
- `app/Models/PengajuanMagang.php`
- `app/Models/KuotaBidang.php`
- `app/Models/SertifikatHandover.php`

### Tabel Database Terkait
- `tbl_sertifikat_pkl` — Data siswa magang
- `pengajuan_magang` — Pengajuan yang menunggu
- `sertifikat_handovers` — Antrian handover sertifikat
- `kuota_bidang` — Kuota per bidang

---

## 2.2 Antrian Sertifikat

**Halaman Antrian Sertifikat** adalah fitur khusus Sekretariat untuk mengelola alur pencetakan sertifikat. Fitur ini memfasilitasi proses dari pembuatan sertifikat sampai penyerahan ke mentor/siswa.

### Konsep Antrian Sertifikat

Alur sertifikat:
1. **Siswa selesai magang** → Mentor membuat entry sertifikat
2. **Sekretariat cetak sertifikat** → Sertifikat dicetak secara fisik
3. **Sertifikat diserahkan ke Mentor** → Mentor menerima sertifikat fisik
4. **Mentor serahkan ke Siswa** → Siswa mengambil sertifikat

Halaman ini mengelola langkah 2-3 (bagian Sekretariat).

### Informasi yang Ditampilkan

1. **Tab Status**
   - **Menunggu Cetak** — Sertifikat yang siap dicetak
   - **Sudah Dicetak** — Sertifikat yang sudah dicetak, menunggu handover
   - **Selesai** — Sudah diserahkan ke mentor/siswa

2. **Daftar Sertifikat per Tab**
   Setiap entry menampilkan:
   - Nama siswa
   - Bidang magang
   - Nama mentor pembimbing
   - Tanggal sertifikat
   - Status
   - Aksi (Cetak/Tandai Selesai/Detail)

### Langkah Kelola Antrian

**A. Cetak Sertifikat**
1. Buka halaman **"Antrian Sertifikat"**
2. Tab default: **"Menunggu Cetak"**
3. Pilih sertifikat yang akan dicetak (bisa batch/multiple)
4. Klik tombol **"Cetak PDF"**
5. Sistem generate PDF sertifikat
6. Download dan cetak PDF menggunakan printer
7. Setelah dicetak fisik, klik **"Tandai Sudah Dicetak"**
8. Sertifikat pindah ke tab **"Sudah Dicetak"**

**B. Handover ke Mentor**
1. Buka tab **"Sudah Dicetak"**
2. Pilih sertifikat yang akan diserahkan ke mentor
3. Klik **"Serahkan ke Mentor"**
4. Sistem mencatat tanggal penyerahan
5. Sertifikat pindah ke tab **"Selesai"**
6. Mentor akan menerima notifikasi

**C. Monitor Antrian**
1. Gunakan filter untuk menyaring data:
   - Filter by bidang
   - Filter by mentor
   - Filter by tanggal
2. Gunakan pencarian untuk cari siswa tertentu
3. Export data ke Excel untuk laporan

### Fitur Batch Operations

- **Cetak Multiple**: Pilih beberapa sertifikat → Cetak sekaligus dalam 1 PDF
- **Handover Batch**: Serahkan beberapa sertifikat ke mentor sekaligus

<!-- SS Antrian Sertifikat - Tab Menunggu -->
<!-- SS Antrian Sertifikat - Tab Sudah Dicetak -->

### File Terkait

**Controller:**
- `app/Http/Controllers/SekretariatController.php`
  - `antrianSertifikat()` — Halaman antrian
  - `cetakBatch()` — Cetak multiple sertifikat
  - `handoverToMentor()` — Serahkan ke mentor

**View:**
- `resources/views/sekretariat/antrian-sertifikat.blade.php`

**Model:**
- `app/Models/SertifikatPKL.php`
- `app/Models/SertifikatHandover.php`
- `app/Models/User.php`

### Tabel Database Terkait
- `tbl_sertifikat_pkl` — Data sertifikat
- `sertifikat_handovers` — Tracking handover sertifikat
  - `id_sertifikat` — ID sertifikat
  - `handed_to_mentor_id` — ID mentor penerima
  - `handed_over_at` — Tanggal penyerahan
  - `status` — Status (pending/completed)

---

## 2.3 Verifikasi Pengajuan Magang

**Sama seperti "2.2 Verifikasi Pengajuan Magang" di Role Mentor (lihat file 03-role-mentor.md).**

**Perbedaan:**
- Sekretariat dapat verifikasi pengajuan untuk **SEMUA bidang** (Aplikasi, Infrastruktur, Multimedia)
- Mentor hanya verifikasi pengajuan untuk bidangnya sendiri
- Fitur approve, reject, dan workflow identik dengan yang ada di role Mentor

<!-- SS Verifikasi Pengajuan Sekretariat -->

### File Terkait

**Controller:**
- `app/Http/Controllers/PengajuanMagangController.php`
  - Method yang sama dengan Mentor

**View:**
- `resources/views/sekretariat/data-pengajuan.blade.php`

**Model:**
- `app/Models/PengajuanMagang.php`

### Tabel Database Terkait
- `pengajuan_magang`

---

## 2.4 Data Siswa Magang

**Sama seperti "2.3 Peserta Magang (Data Siswa PKL Aktif)" di Role Mentor (lihat file 03-role-mentor.md).**

**Perbedaan:**
- Sekretariat melihat data siswa dari **SEMUA bidang**
- Mentor hanya melihat siswa bimbingannya
- Fitur CRUD (Create, Read, Update, Delete) identik
- Sekretariat dapat:
  - Edit siswa dari bidang mana pun
  - Tambah siswa untuk bidang mana pun
  - Export data seluruh siswa

<!-- SS Data Siswa Sekretariat -->

### File Terkait

**Controller:**
- `app/Http/Controllers/SekretariatController.php`
  - `dataSiswa()` — Daftar siswa semua bidang
  - `createSiswa()` — Form tambah siswa
  - `storeSiswa()` — Simpan siswa baru
  - `editSiswa($id)` — Form edit siswa
  - `updateSiswa($id)` — Update data siswa
  - `deleteSiswa($id)` — Hapus siswa

**View:**
- `resources/views/sekretariat/data-siswa.blade.php`
- `resources/views/sekretariat/create-siswa.blade.php`
- `resources/views/sekretariat/edit-siswa.blade.php`

**Model:**
- `app/Models/SertifikatPKL.php`
- `app/Models/User.php`

### Tabel Database Terkait

**Tabel: `tbl_sertifikat_pkl`**

Tabel utama yang menyimpan data siswa magang dan sertifikat mereka.

| Kolom Utama         | Tipe         | Keterangan                                              | Line di Model |
|---------------------|--------------|--------------------------------------------------------|---------------|
| id_sertifikat       | INT (PK)     | ID unik sertifikat                                     | Line 13       |
| nama_lengkap        | VARCHAR      | Nama siswa/mahasiswa                                   | Line 18       |
| asal_sekolah        | VARCHAR      | Nama sekolah/universitas                               | Line 19       |
| nim_nis             | VARCHAR      | NIM/NIS siswa                                          | Line 21       |
| bidang              | VARCHAR      | Bidang magang (Aplikasi/Infrastruktur/Multimedia)      | Line 31       |
| tgl_mulai           | DATE         | Tanggal mulai magang                                   | Line 25       |
| tgl_selesai         | DATE         | Tanggal selesai magang                                 | Line 26       |
| sts_diambil         | TINYINT      | Status sertifikat sudah diambil (0=belum, 1=sudah)     | Line 38       |

---

## 2.5 Lembaga Pendidikan

**Sama seperti "2.4 Lembaga Pendidikan (Data Sekolah/Universitas)" di Role Mentor (lihat file 03-role-mentor.md).**

**Perbedaan:**
- Tidak ada perbedaan signifikan
- Baik Mentor maupun Sekretariat dapat mengelola data lembaga pendidikan
- Fitur tambah, edit, hapus, upload logo identik

<!-- SS Lembaga Pendidikan Sekretariat -->

### File Terkait

**Controller:**
- `app/Http/Controllers/SekretariatController.php`
  - `instansiIndex()` — Daftar lembaga pendidikan
  - `instansiCreate()` — Form tambah lembaga
  - `instansiStore()` — Simpan lembaga baru
  - `instansiEdit($id)` — Form edit lembaga
  - `instansiUpdate($id)` — Update data lembaga
  - `instansiDestroy($id)` — Hapus lembaga

**View:**
- `resources/views/sekretariat/instansi/index.blade.php`
- `resources/views/sekretariat/instansi/create.blade.php`
- `resources/views/sekretariat/instansi/edit.blade.php`

**Model:**
- `app/Models/Instansi.php`

### Tabel Database Terkait
- `instansi`

---

## 2.6 Data Lulus Magang

**Sama seperti "2.5 Data Lulus Magang" di Role Mentor (lihat file 03-role-mentor.md).**

**Perbedaan:**
- Sekretariat melihat alumni dari **SEMUA bidang**
- Mentor hanya melihat alumni yang pernah dibimbingnya
- Fitur export, cetak ulang sertifikat identik

<!-- SS Data Lulus Sekretariat -->

### File Terkait

**Controller:**
- `app/Http/Controllers/SekretariatController.php`
  - Method filter siswa dengan `sts_diambil = 1`

**View:**
- `resources/views/sekretariat/data-siswa.blade.php` (dengan filter)

**Model:**
- `app/Models/SertifikatPKL.php`

### Tabel Database Terkait
- `tbl_sertifikat_pkl` (WHERE sts_diambil = 1)

---

## 2.7 Data Mentor

**Sama seperti "2.7 Data Mentor" di Role Mentor (lihat file 03-role-mentor.md).**

**Perbedaan:**
- Tidak ada perbedaan signifikan
- Baik Mentor (senior) maupun Sekretariat dapat mengelola akun mentor
- Fitur tambah, edit, hapus, reset password identik
- Sekretariat biasanya yang membuat akun mentor baru

<!-- SS Data Mentor Sekretariat -->

### File Terkait

**Controller:**
- `app/Http/Controllers/MentorController.php`

**View:**
- `resources/views/sekretariat/data-mentor.blade.php`
- View lain sama dengan mentor

**Model:**
- `app/Models/User.php`
- `app/Models/MentorSiswa.php`

### Tabel Database Terkait
- `tbl_login` (WHERE role = 'mentor')
- `mentor_siswa`

---

## 2.8 Manajemen Pengguna

**Sama seperti "2.15 Manajemen Pengguna" di Role Mentor (lihat file 03-role-mentor.md).**

**Perbedaan:**
- Sekretariat memiliki akses penuh ke manajemen pengguna
- Dapat mengelola akun Mentor, Sekretariat lain, dan Siswa
- Fitur:
  - Tambah pengguna (semua role)
  - Edit pengguna
  - Hapus pengguna
  - Reset password
  - Change role (ubah role pengguna)
  - Aktifkan/nonaktifkan akun

### File Terkait

**Controller:**
- `app/Http/Controllers/UserController.php`

**View:**
- `resources/views/users/index.blade.php`

**Model:**
- `app/Models/User.php`

### Tabel Database Terkait
- `tbl_login`

---

## 2.9 Fitur Lainnya (Sama dengan Mentor)

Fitur-fitur berikut **identik dengan yang ada di Role Mentor**, dengan perbedaan utama bahwa Sekretariat dapat mengakses data **lintas semua bidang**:

### A. Direktori Mentor
**Referensi:** Lihat "2.8 Direktori Mentor" di file 03-role-mentor.md

**Perbedaan:** Tidak ada perbedaan, baik Mentor maupun Sekretariat melihat direktori yang sama.

---

### B. Manajemen Tugas
**Referensi:** Lihat "2.9 Manajemen Tugas" di file 03-role-mentor.md

**Perbedaan:**
- Sekretariat dapat melihat **semua tugas** yang diberikan semua mentor
- Mentor hanya melihat tugas yang dia berikan sendiri
- Sekretariat dapat membuat tugas untuk siswa di bidang mana pun
- Fitur buat, edit, hapus, review submission identik

**Controller:** `app/Http/Controllers/MentorTaskController.php`  
**View:** `resources/views/sekretariat/tasks/*`  
**Model:** `app/Models/Task.php`, `app/Models/TaskSubmission.php`

---

### C. Penilaian Siswa
**Referensi:** Lihat "2.10 Penilaian Siswa" di file 03-role-mentor.md

**Perbedaan:**
- Sekretariat dapat input/melihat nilai **semua siswa** dari semua bidang
- Mentor hanya input nilai untuk siswa bimbingannya
- Komponen penilaian (Kedisiplinan, Tanggung Jawab, dll.) identik

**Controller:** `app/Http/Controllers/PenilaianController.php`  
**View:** `resources/views/penilaian/*`  
**Model:** `app/Models/Penilaian.php`

---

### D. Cetak Sertifikat
**Referensi:** Lihat "2.11 Cetak Sertifikat" di file 03-role-mentor.md

**Perbedaan:**
- Sekretariat dapat cetak sertifikat untuk **siswa dari bidang mana pun**
- Mentor hanya cetak sertifikat siswa bimbingannya
- Jenis sertifikat (PKL, Gabungan) identik
- Fitur preview, download PDF identik

**Controller:** `app/Http/Controllers/SertifikatController.php`  
**View:** `resources/views/sekretariat/print-certificate.blade.php`  
**Model:** `app/Models/SertifikatPKL.php`, `app/Models/Penilaian.php`

---

### E. Pengumuman
**Referensi:** Lihat "2.12 Pengumuman" di file 03-role-mentor.md

**Perbedaan:**
- Sekretariat dapat membuat pengumuman untuk **semua siswa** atau per bidang
- Mentor membuat pengumuman untuk siswa bimbingannya
- Fitur buat, edit, hapus, pin pengumuman identik

**Controller:** `app/Http/Controllers/AnnouncementController.php`  
**View:** `resources/views/announcements/*`  
**Model:** `app/Models/Announcement.php`

---

### F. Kalender Magang & Kelola Kuota
**Referensi:** Lihat "2.13 Kalender Magang & Kelola Kuota" di file 03-role-mentor.md

**Perbedaan:**
- Tidak ada perbedaan signifikan
- Baik Mentor maupun Sekretariat dapat kelola kuota per bidang
- View kalender dengan jadwal magang semua bidang identik

**Controller:** `app/Http/Controllers/LandingCalendarController.php`, `app/Http/Controllers/SertifikatController.php`  
**View:** `resources/views/sekretariat/kalender-magang.blade.php`  
**Model:** `app/Models/KuotaBidang.php`, `app/Models/SertifikatPKL.php`

---

### G. Profil
**Referensi:** Lihat "2.14 Profil Mentor" di file 03-role-mentor.md

**Perbedaan:**
- Tidak ada perbedaan
- Fitur update profil, ganti password, upload foto identik

**Controller:** `app/Http/Controllers/SekretariatController.php`  
**View:** `resources/views/sekretariat/profile.blade.php`  
**Model:** `app/Models/User.php`

---

# PENUTUP

Demikian **Buku Panduan Pengguna Website SIMGANG - Bagian 4: Panduan untuk Role Sekretariat** ini disusun untuk membantu Sekretariat dalam mengelola administrasi magang di Dinas Komunikasi dan Informatika Kabupaten Cilacap.

**Ringkasan Perbedaan Role Sekretariat vs Mentor:**

| Aspek | Mentor | Sekretariat |
|-------|--------|-------------|
| **Akses Data** | Hanya bidangnya | SEMUA bidang |
| **Siswa** | Siswa bimbingannya | Semua siswa |
| **Pengajuan** | Bidangnya saja | Semua bidang |
| **Tugas** | Tugas yang dia buat | Semua tugas |
| **Penilaian** | Siswa bimbingannya | Semua siswa |
| **Sertifikat** | Siswa bimbingannya | Semua siswa |
| **Fitur Khusus** | - | Antrian Sertifikat |

Sekretariat berperan sebagai **administrator dan koordinator** yang memiliki pandangan lengkap terhadap seluruh sistem magang, sehingga dapat melakukan monitoring, evaluasi, dan pengambilan keputusan strategis terkait program magang.

Apabila terdapat kendala dalam penggunaan sistem, silakan menghubungi **Tim Teknis SIMGANG** atau **Admin Sistem**.

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
