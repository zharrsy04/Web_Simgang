# BUKU PANDUAN PENGGUNA
# WEBSITE SIMGANG
# (Sistem Informasi Magang)

**Sistem Pelayanan Manajemen Magang Berbasis Website**  
**Dinas Komunikasi dan Informatika Kabupaten Cilacap**

[Logo Diskominfo Kabupaten Cilacap]

**2026**

---

# DAFTAR ISI

**Catatan:** Untuk versi digital (Markdown), gunakan fitur pencarian (Ctrl+F) atau scroll untuk navigasi antar bagian.

---

**BAGIAN 1 — PENDAHULUAN**
- 1.1 Tentang SIMGANG
- 1.2 Alur Sistem Secara Umum
- 1.3 Jenis Pengguna Sistem

**BAGIAN 2 — PANDUAN UNTUK PENGUNJUNG (TANPA LOGIN)**
- 2.1 Halaman Beranda (Landing Page)
- 2.2 Halaman Riwayat / History
- 2.3 Halaman Kalender Magang
- 2.4 Halaman Contact / Hubungi Kami
- 2.5 Halaman Login
- 2.6 Halaman Register / Pendaftaran Akun

**PENUTUP**

---

# BAGIAN 1 — PENDAHULUAN

## 1.1 Tentang SIMGANG

**SIMGANG (Sistem Informasi Magang)** adalah sistem pelayanan manajemen magang berbasis website yang dikelola oleh Dinas Komunikasi dan Informatika (Diskominfo) Kabupaten Cilacap. Website ini digunakan oleh:

- **Siswa/Mahasiswa** untuk mengajukan permohonan magang, melakukan absensi, mengerjakan tugas, mengelola portofolio, dan memantau penilaian.
- **Mentor** untuk mengelola data siswa/mahasiswa magang, melakukan penilaian, memberikan tugas, dan mencetak sertifikat.
- **Sekretariat** untuk memverifikasi pengajuan magang, mengelola kuota bidang, mencetak sertifikat, dan monitoring data keseluruhan.

Buku panduan ini disusun untuk membantu pengguna memahami cara menggunakan seluruh fitur yang tersedia di website SIMGANG, baik sebagai pengunjung umum maupun pengguna yang sudah login.

**Bagian ini (Bagian 2)** khusus membahas fitur-fitur yang dapat diakses oleh **pengunjung umum tanpa perlu login**.

## 1.2 Alur Sistem Secara Umum

Secara garis besar, alur penggunaan sistem SIMGANG adalah sebagai berikut:

1. **Calon Peserta Magang** membuka website SIMGANG dan melihat informasi umum di halaman Beranda.
2. **Calon Peserta** melakukan **Registrasi Akun** sebagai Siswa/Mahasiswa.
3. **Siswa/Mahasiswa** melakukan **Login** ke dalam sistem.
4. **Siswa/Mahasiswa** mengisi **Form Pengajuan Magang** dengan melengkapi data pribadi dan dokumen persyaratan.
5. **Sekretariat** memverifikasi pengajuan magang (Diterima/Ditolak).
6. Jika diterima, **Siswa/Mahasiswa** ditempatkan ke **bidang magang** tertentu (Aplikasi/Infrastruktur/Multimedia).
7. **Siswa/Mahasiswa** melaksanakan magang, melakukan absensi harian, mengerjakan tugas dari Mentor, dan mengelola portofolio.
8. **Mentor** memberikan tugas, memantau progres, dan melakukan penilaian.
9. Setelah masa magang selesai, **Mentor** memberikan penilaian akhir dan mencetak sertifikat.
10. **Siswa/Mahasiswa** dapat mengunduh sertifikat dan melihat riwayat magang.

## 1.3 Jenis Pengguna Sistem

- **Siswa/Mahasiswa** — dapat mendaftar akun, mengajukan permohonan magang, melakukan absensi, mengerjakan tugas, mengelola portofolio, melihat nilai, dan mengunduh sertifikat.
- **Mentor** — dapat mengelola data siswa/mahasiswa bimbingan, memberikan tugas, melakukan penilaian, mencetak sertifikat, dan mengelola data absensi.
- **Sekretariat** — dapat memverifikasi pengajuan magang, mengelola kuota bidang, memonitor data seluruh peserta magang, mengelola akun pengguna, dan mencetak sertifikat.
- **Pengunjung Umum (tanpa login)** — dapat melihat informasi umum, riwayat alumni magang, kalender ketersediaan slot magang, informasi kontak, serta melakukan login dan registrasi.

---

# BAGIAN 2 — PANDUAN UNTUK PENGUNJUNG (TANPA LOGIN)

## 2.1 Halaman Beranda (Landing Page)

**Halaman Beranda** adalah halaman pertama yang tampil saat mengakses alamat website SIMGANG. Halaman ini dapat diakses oleh siapa saja tanpa perlu login dan berfungsi sebagai portal informasi umum tentang program magang di Diskominfo Kabupaten Cilacap.

### Informasi yang Ditampilkan

Pada halaman Beranda, pengunjung dapat melihat:

- **Statistik Real-time:**
  - Jumlah siswa/mahasiswa yang **sedang aktif magang** (currently ongoing)
  - Jumlah siswa/mahasiswa yang **menunggu magang** (sudah diterima tapi belum mulai)
  - Jumlah **institusi pendidikan** (sekolah/universitas) yang pernah mengirim peserta
  - Jumlah siswa/mahasiswa yang **sudah lulus/selesai** magang
  - **Sisa kuota** tempat magang yang tersedia
  
- **Grafik Visual:** Chart batang yang menampilkan distribusi peserta magang per bidang (Aplikasi, Infrastruktur, Multimedia).

- **Informasi Bidang Magang:** Kartu informasi yang menjelaskan bidang-bidang magang yang tersedia:
  - **Bidang Aplikasi** — pengembangan software dan aplikasi
  - **Bidang Infrastruktur** — jaringan, server, dan sistem IT
  - **Bidang Multimedia** — desain grafis, video, dan konten digital

- **Menu Navigasi:** Navigation bar dengan link ke:
  - Beranda (Home)
  - Riwayat (History)
  - Kalender Magang
  - Contact
  - Login
  - Daftar (Register)

### Langkah Penggunaan

1. Pengunjung membuka website SIMGANG dengan mengakses URL: `http://simgang.cilacapkab.go.id` (atau URL sesuai deployment).
2. Sistem otomatis menampilkan halaman Beranda dengan data statistik terkini yang diambil dari database.
3. Pengunjung dapat:
   - Membaca informasi umum tentang program magang
   - Melihat statistik dan grafik peserta magang secara real-time
   - Memahami bidang-bidang magang yang tersedia
   - Klik menu navigasi untuk mengakses halaman lain

[SS Halaman Beranda]

### File Terkait

**Controller:**
- `app/Http/Controllers/LandingController.php`
  - Line 13-68: `home()` — Menampilkan halaman beranda dengan statistik real-time
  - Logic utama:
    - Query siswa aktif, menunggu, lulus (line 16-37)
    - Hitung sisa kuota (line 41-47)
    - Query statistik per bidang untuk chart (line 50-56)

**View:**
- `resources/views/landing/home.blade.php` — Template halaman beranda (full page)
- `resources/views/layouts/landing.blade.php` — Layout utama halaman publik (navbar, footer)

**Constants:**
- `app/Constants/BidangConstants.php` — Definisi konstanta bidang magang (Aplikasi, Infrastruktur, Multimedia)

**Model:**
- `app/Models/SertifikatPKL.php`
  - Line 9-13: Definisi tabel dan primary key
  - Line 16-51: Fillable fields
  - Line 134-162: Scope methods untuk query (aktif, search, dll)

### Tabel Database Terkait

**1. Tabel: `tbl_sertifikat_pkl`**
   
Tabel utama yang menyimpan data siswa magang dan sertifikat mereka.

| Kolom Utama     | Tipe         | Keterangan                                             | Line di Model |
|-----------------|--------------|--------------------------------------------------------|---------------|
| id_sertifikat   | INT (PK)     | ID unik sertifikat                                     | Line 13       |
| nama_lengkap    | VARCHAR      | Nama siswa/mahasiswa                                   | Line 18       |
| asal_sekolah    | VARCHAR      | Nama sekolah/universitas                               | Line 19       |
| nim_nis         | VARCHAR      | NIM/NIS siswa                                          | Line 21       |
| bidang          | VARCHAR      | Bidang magang (Aplikasi/Infrastruktur/Multimedia)      | Line 31       |
| tgl_mulai       | DATE         | Tanggal mulai magang                                   | Line 25       |
| tgl_selesai     | DATE         | Tanggal selesai magang                                 | Line 26       |
| sts_diambil     | TINYINT      | Status sertifikat sudah diambil (0=belum, 1=sudah)     | Line 38       |

**Query yang digunakan di halaman Beranda:**
- Siswa aktif: `WHERE sts_diambil = 0 AND tgl_mulai <= today AND tgl_selesai >= today`
- Siswa menunggu: `WHERE tgl_mulai > today`
- Siswa lulus: `WHERE sts_diambil = 1 AND tgl_selesai < today`
- Statistik per bidang: `GROUP BY bidang`

Lihat query detail di: `LandingController.php` line 16-56

---

## 2.2 Halaman Riwayat / History

**Halaman Riwayat** menampilkan daftar alumni atau peserta magang yang pernah melaksanakan magang di Diskominfo Cilacap. Halaman ini berfungsi sebagai **galeri/showcase** peserta magang yang telah menyelesaikan program dan dapat diakses oleh masyarakat umum tanpa perlu login.

### Informasi yang Ditampilkan

Setiap data riwayat alumni mencakup:
- **Nama Lengkap** siswa/mahasiswa
- **Asal Sekolah/Universitas**
- **Jurusan** atau program studi
- **Bidang Magang** (Aplikasi/Infrastruktur/Multimedia)
- **Periode Magang** (tanggal mulai sampai selesai)
- **Status**: Lulus/Selesai

### Fitur yang Tersedia

1. **Pencarian (Search):**  
   Pengunjung dapat mencari alumni tertentu berdasarkan nama, sekolah, atau bidang magang menggunakan kolom pencarian.

2. **Filter:**  
   Sistem menyediakan filter berdasarkan:
   - Asal sekolah/universitas
   - Bidang magang
   - Periode (tahun)

3. **Export Data:**  
   Pengunjung dapat mengunduh daftar riwayat alumni dalam format Excel atau PDF melalui tombol "Export".

4. **Download Form Sertifikat:**  
   Link untuk mengunduh formulir permintaan sertifikat bagi alumni yang ingin meminta sertifikat fisik.

5. **Validasi Sertifikat:**  
   Pengunjung dapat memvalidasi keaslian sertifikat dengan memasukkan nomor sertifikat pada form validasi.

### Langkah Penggunaan

1. Klik menu **"Riwayat"** pada navigation bar halaman Beranda.
2. Sistem redirect ke halaman `/riwayat`.
3. Sistem menampilkan daftar alumni magang yang telah lulus (status `sts_diambil = true`).
4. Pengunjung dapat:
   - Scroll/browse daftar alumni
   - Menggunakan kolom **Search** untuk mencari alumni tertentu
   - Menggunakan **Filter** untuk menyaring data berdasarkan kriteria
   - Klik tombol **"Export"** untuk mengunduh daftar dalam format Excel/PDF
   - Klik **"Download Form Sertifikat"** untuk mengunduh formulir permintaan sertifikat
   - Menggunakan fitur **"Validasi Sertifikat"** dengan memasukkan nomor sertifikat

[SS Halaman Riwayat Public]

### File Terkait

**Controller:**
- `app/Http/Controllers/HistoryController.php`
  - Line 17-80: `index(Request $request)` — Menampilkan daftar riwayat alumni magang publik
    - Query: Ambil data alumni yang sudah lulus (sts_diambil = 1)
    - Filter: Search by nama/sekolah/bidang (line 27-31)
    - Pagination: 20 per halaman (line 73)
  - Line 308-389: `export(Request $request)` — Export data riwayat ke Excel/PDF
  - Line 82-88: `downloadCertificateForm()` — Download form permintaan sertifikat (PDF)
  - Line 90-116: `validateAndDownloadCertificate(Request $request)` — Validasi nomor sertifikat dan download

**View:**
- `resources/views/landing/history.blade.php` — Template halaman riwayat publik (line 1-200+)
  - Card list alumni dengan informasi lengkap
  - Search form dan filter
  - Export buttons (Excel/PDF)
  - Download certificate form link
  
- `resources/views/landing/download-certificate.blade.php` — Halaman validasi dan download sertifikat

**Model:**
- `app/Models/SertifikatPKL.php`
  - Line 9-13: Definisi tabel (`tbl_sertifikat_pkl`) dan primary key (`id_sertifikat`)
  - Line 16-51: Fillable fields (kolom yang dapat diisi)
  - Line 117-132: Scope methods:
    - `scopeDiambil()` — Filter sertifikat yang sudah diambil
    - `scopeSearch()` — Pencarian berdasarkan nama/sekolah/bidang/NIS

### Tabel Database Terkait

**1. Tabel: `tbl_sertifikat_pkl`**

Tabel utama yang menyimpan data siswa magang dan sertifikat mereka. Untuk halaman Riwayat, hanya menampilkan data alumni yang sudah lulus.

| Kolom Utama         | Tipe         | Keterangan                                              | Line di Model |
|---------------------|--------------|--------------------------------------------------------|---------------|
| id_sertifikat       | INT (PK)     | ID unik sertifikat                                     | Line 13       |
| nama_lengkap        | VARCHAR      | Nama siswa/mahasiswa                                   | Line 18       |
| asal_sekolah        | VARCHAR      | Nama sekolah/universitas                               | Line 19       |
| nim_nis             | VARCHAR      | NIM/NIS siswa                                          | Line 21       |
| jurusan             | VARCHAR      | Jurusan/program studi                                  | Line 24       |
| bidang              | VARCHAR      | Bidang magang (Aplikasi/Infrastruktur/Multimedia)      | Line 31       |
| tgl_mulai           | DATE         | Tanggal mulai magang                                   | Line 25       |
| tgl_selesai         | DATE         | Tanggal selesai magang                                 | Line 26       |
| tgl_sertifikat      | DATE         | Tanggal penerbitan sertifikat                          | Line 27       |
| no_sertifikat       | VARCHAR      | Nomor sertifikat unik                                  | Line 32       |
| sts_diambil         | TINYINT      | Status sertifikat sudah diambil (0=belum, 1=sudah/lulus) | Line 38    |
| nm_pembimbing       | VARCHAR      | Nama mentor pembimbing                                 | Line 28       |
| nik_pembimbing      | VARCHAR      | NIK pembimbing                                         | Line 29       |
| aprove_pembimbing   | TINYINT      | Status approval pembimbing (0=belum, 1=sudah)          | Line 36       |
| approve_kadis       | TINYINT      | Status approval kepala dinas (0=belum, 1=sudah)        | Line 37       |

**Query yang digunakan di halaman Riwayat:**

```sql
-- Query dasar (lihat HistoryController.php line 21-73)
SELECT * FROM tbl_sertifikat_pkl 
WHERE sts_diambil = 1  -- Filter hanya alumni yang sudah lulus
ORDER BY tgl_selesai DESC

-- Dengan Search (line 27-31)
WHERE sts_diambil = 1 
  AND (nama_lengkap LIKE '%keyword%' 
    OR asal_sekolah LIKE '%keyword%'
    OR bidang LIKE '%keyword%')
```

**Relasi:**
- `instansi()` → Relasi ke tabel `instansi` (untuk logo sekolah/universitas)
- `penilaians()` → Relasi ke tabel `penilaian` (untuk nilai-nilai magang)

Lihat query detail di: `HistoryController.php` line 17-80

---

## 2.3 Halaman Kalender Magang

**Halaman Kalender Magang** adalah fitur untuk melihat jadwal dan ketersediaan slot magang di Diskominfo Cilacap secara visual menggunakan kalender interaktif. Halaman ini memberikan **transparansi** kepada calon peserta magang tentang ketersediaan tempat dan waktu pelaksanaan magang.

### Informasi yang Ditampilkan

1. **Kalender Visual (Interaktif):**
   - Menampilkan event magang per periode (tanggal mulai sampai selesai)
   - Setiap event ditandai dengan **warna berbeda** sesuai bidang magang:
     - **Biru** — Bidang Aplikasi
     - **Hijau** — Bidang Infrastruktur
     - **Oranye** — Bidang Multimedia
   - Pengunjung dapat klik pada event untuk melihat detail (nama peserta, sekolah, periode)

2. **Legend/Keterangan Warna:**
   Panel legend yang menjelaskan warna-warna event di kalender sesuai bidang magang.

3. **Info Kuota per Bidang (Real-time):**
   Panel dashboard yang menampilkan:
   - **Nama Bidang**
   - **Kuota Maksimal** (total slot yang tersedia)
   - **Terisi** (jumlah peserta aktif saat ini)
   - **Sisa Slot** (kuota - terisi)
   - **Persentase Terisi** (progress bar visual)

### Manfaat untuk Calon Peserta Magang

- Mengetahui **kapan** waktu yang tepat untuk mendaftar (berdasarkan ketersediaan slot)
- Melihat **bidang mana** yang masih memiliki kuota kosong
- Memperkirakan **tingkat persaingan** berdasarkan jumlah peserta aktif

### Langkah Penggunaan

1. Klik menu **"Kalender Magang"** pada navigation bar.
2. Sistem redirect ke halaman `/kalender-magang`.
3. Sistem memuat data magang dari database dan menampilkan:
   - Kalender dengan event per periode magang (warna sesuai bidang)
   - Panel info kuota real-time per bidang
4. Pengunjung dapat:
   - **Browse kalender** dengan klik panah kiri/kanan (ganti bulan) atau pilih tahun
   - **Klik event** di kalender untuk melihat detail periode magang tersebut
   - **Lihat info kuota** pada panel dashboard untuk mengetahui sisa slot per bidang
   - **Membuat keputusan** kapan mendaftar berdasarkan ketersediaan

### Perhitungan Data Real-time

- **Terisi** dihitung otomatis berdasarkan jumlah siswa dengan status aktif (`sts_diambil = false`) yang periode magangnya mencakup tanggal hari ini (tgl_mulai ≤ today ≤ tgl_selesai).
- **Sisa Kuota** = Kuota Maksimal - Terisi
- Jika sisa kuota bernilai negatif (overload), sistem tetap menampilkan 0 untuk menghindari kebingungan.

[SS Kalender Magang Public]

### File Terkait

**Controller:**
- `app/Http/Controllers/LandingCalendarController.php`
  - `index()` — tampilan kalender publik
  - `getInternshipData()` — API endpoint untuk data kalender (JSON)

**View:**
- `resources/views/landing/calendar.blade.php`
- `resources/views/landing/kalender-magang.blade.php`

**Catatan:** Untuk fitur **Manajemen Kuota** (tambah/edit/hapus kuota bidang), hanya dapat diakses oleh **Mentor & Sekretariat** setelah login.

### Tabel Database Terkait
- `tbl_sertifikat_pkl` (data magang untuk kalender dan hitung terisi)
- `kuota_bidang` (data kuota maksimal per bidang magang)

---

## 2.4 Halaman Contact / Hubungi Kami

**Halaman Contact** menampilkan informasi kontak resmi Dinas Komunikasi dan Informatika Kabupaten Cilacap untuk calon peserta magang atau masyarakat umum yang ingin menghubungi pihak Diskominfo terkait program magang.

### Informasi yang Ditampilkan

- **Alamat Kantor:** Alamat lengkap kantor Diskominfo Cilacap
- **Nomor Telepon:** Nomor telepon resmi yang dapat dihubungi
- **Email:** Alamat email resmi untuk korespondensi
- **Jam Pelayanan:** Hari dan jam operasional kantor (misal: Senin-Jumat, 08:00-16:00 WIB)
- **Peta Lokasi:** Google Maps embed untuk memudahkan pengunjung menemukan lokasi kantor
- **Media Sosial:** Link ke akun media sosial resmi Diskominfo Cilacap (jika ada)

### Langkah Penggunaan

1. Klik menu **"Contact"** pada navigation bar landing page.
2. Sistem redirect ke halaman `/contact`.
3. Pengunjung dapat:
   - Melihat informasi kontak lengkap
   - Menggunakan informasi tersebut untuk menghubungi Diskominfo (telepon/email/datang langsung)
   - Klik pada **peta lokasi** untuk membuka Google Maps dan mendapatkan petunjuk arah

[SS Halaman Contact]

### File Terkait

**Controller:**
- `app/Http/Controllers/LandingController.php` (method `contact()`)

**View:**
- `resources/views/landing/contact.blade.php`

### Tabel Database Terkait
- Tidak ada (halaman statis berisi informasi kontak)

---

## 2.5 Halaman Login

**Halaman Login** adalah gerbang masuk ke sistem SIMGANG untuk pengguna yang sudah memiliki akun. Halaman ini dapat diakses oleh siapa saja, namun hanya dapat digunakan oleh pengguna dengan akun yang valid.

### Jenis Pengguna yang Dapat Login

- **Siswa/Mahasiswa** — Dapat mendaftar sendiri melalui halaman Register
- **Mentor** — Akun dibuat oleh Sekretariat/Admin
- **Sekretariat** — Akun dibuat oleh Admin

### Form Input

Halaman login memiliki 2 field input:
1. **Email / Username** — User dapat login dengan email atau username yang didaftarkan
2. **Password** — Password yang dibuat saat registrasi (untuk Siswa) atau diberikan oleh admin (untuk Mentor/Sekretariat)

### Fitur Tambahan

- **Statistik Chart:** Panel di sebelah kiri form menampilkan grafik "Jumlah Siswa Magang per Sekolah" sebagai visualisasi data (untuk menarik minat calon peserta).
- **Logo Universitas Partner:** Di bagian bawah form, terdapat logo universitas/institusi partner (PNC, UNNES, Telkom University) dengan label "Created by:".
- **Link ke Register:** Terdapat link "Belum punya akun? Daftar di sini" untuk pengunjung yang belum memiliki akun.

### Langkah Penggunaan

1. Klik tombol **"Login"** atau **"Masuk"** pada navigation bar halaman Beranda.
2. Sistem redirect ke halaman `/login`.
3. User memasukkan **Email/Username** dan **Password**.
4. User klik tombol **"Login"**.
5. Sistem melakukan validasi:
   - **Jika valid:** User diarahkan ke dashboard sesuai role:
     - Siswa/Mahasiswa → `/siswa/dashboard`
     - Mentor → `/mentor/dashboard`
     - Sekretariat → `/sekretariat/dashboard`
   - **Jika tidak valid:** Sistem menampilkan pesan error (Email/username atau password salah).
6. Jika user belum punya akun, klik link **"Daftar di sini"** untuk ke halaman Register.

### Keamanan (Security)

- **Password Hashing:** Password disimpan dalam bentuk hash di database (tidak plain text).
- **Session Management:** Setelah login berhasil, sistem membuat session untuk maintain status login.
- **CSRF Protection:** Form login dilindungi dengan CSRF token untuk mencegah serangan CSRF.
- **Remember Me (optional):** User dapat mencentang "Ingat Saya" untuk tetap login meski browser ditutup.

[SS Halaman Login]

### File Terkait

**Controller:**
- `app/Http/Controllers/LoginController.php`
  - `showLoginForm()` — tampilkan form login dengan data chart
  - `login()` atau `process()` — proses autentikasi
  - `keluar()` atau `logout()` — logout user

**View:**
- `resources/views/auth/login.blade.php`

**Middleware:**
- `app/Http/Middleware/RedirectIfAuthenticated.php` (redirect jika sudah login)

### Tabel Database Terkait
- `users` (tabel utama untuk autentikasi)
- `tbl_sertifikat_pkl` (untuk chart statistik di halaman login)

---

## 2.6 Halaman Register / Pendaftaran Akun

**Halaman Register** adalah form pendaftaran akun baru untuk **Siswa/Mahasiswa** yang ingin mengikuti program magang di Diskominfo Cilacap. Halaman ini dapat diakses oleh siapa saja tanpa perlu login.

### Penting untuk Diketahui

- **Siswa/Mahasiswa** dapat mendaftar sendiri melalui halaman ini
- **Mentor & Sekretariat** TIDAK dapat mendaftar sendiri — akun mereka dibuat oleh Admin/Sekretariat

Hal ini untuk memastikan validitas akun Mentor dan Sekretariat yang akan mengelola data penting sistem.

### Form Input

Halaman register memiliki 5 field input wajib:

1. **Nama Lengkap**  
   - Minimal 3 karakter
   - Digunakan untuk identitas user di sistem

2. **Email**  
   - Format email valid (contoh: nama@email.com)
   - Email harus unik (belum terdaftar di database)
   - Akan digunakan sebagai username untuk login

3. **Password**  
   - Minimal 8 karakter
   - Disarankan kombinasi huruf, angka, dan simbol untuk keamanan

4. **Konfirmasi Password**  
   - Harus sama persis dengan field Password
   - Untuk memastikan user tidak salah ketik password

5. **reCAPTCHA**  
   - Validasi anti-bot dari Google
   - User harus mencentang "I'm not a robot"

### Info Box "Mendaftar sebagai Siswa/Mahasiswa"

Di bagian atas form terdapat info box berwarna biru yang menyatakan:
> 🎓 **Mendaftar sebagai Siswa / Mahasiswa**  
> Akun Mentor & Sekretariat dibuat oleh admin

Info box ini untuk memperjelas bahwa halaman register hanya untuk Siswa/Mahasiswa.

### Langkah Penggunaan

1. Klik link **"Daftar"** atau **"Daftar di sini"** pada halaman Login atau navigation bar.
2. Sistem redirect ke halaman `/register`.
3. User membaca info box "Mendaftar sebagai Siswa/Mahasiswa".
4. User mengisi form dengan data yang valid:
   - Nama lengkap (min. 3 karakter)
   - Email yang belum terdaftar
   - Password yang kuat (min. 8 karakter)
   - Konfirmasi password (harus sama dengan password)
5. User mencentang **reCAPTCHA** ("I'm not a robot").
6. User klik tombol **"Daftar Sekarang"**.
7. Sistem melakukan validasi:
   - **Server-side validation:** Laravel validation rules
   - **Client-side validation:** JavaScript validation (cek format, panjang, kecocokan password)
   - **reCAPTCHA verification:** Verifikasi dengan Google reCAPTCHA API
8. Jika semua validasi berhasil:
   - Sistem membuat akun baru dengan role "siswa"
   - Password di-hash sebelum disimpan ke database
   - User diarahkan ke halaman login dengan pesan sukses
   - User dapat langsung login dengan email dan password yang baru dibuat
9. Jika validasi gagal:
   - Sistem menampilkan pesan error spesifik per field
   - User memperbaiki input yang salah dan submit ulang

### Validasi yang Diterapkan

| Field | Validasi |
|-------|----------|
| Nama Lengkap | Required, minimal 3 karakter |
| Email | Required, format email valid, unique (belum terdaftar) |
| Password | Required, minimal 8 karakter |
| Konfirmasi Password | Required, harus sama dengan Password |
| reCAPTCHA | Required, valid token dari Google |

### Keamanan (Security)

- **Password Hashing:** Password di-hash menggunakan bcrypt sebelum disimpan (tidak plain text).
- **reCAPTCHA:** Mencegah spam dan pendaftaran otomatis oleh bot.
- **CSRF Token:** Form dilindungi dengan CSRF token untuk mencegah serangan CSRF.
- **Email Verification (optional):** Sistem dapat dikonfigurasi untuk mengirim email verifikasi setelah registrasi (tergantung konfigurasi sistem).

### Setelah Registrasi Berhasil

- User dapat langsung **login** menggunakan email dan password yang baru dibuat.
- User dengan role "siswa" akan diarahkan ke halaman **Dashboard Siswa** setelah login.
- User dapat melengkapi data profil dan mengajukan permohonan magang.

[SS Halaman Register]

### File Terkait

**Controller:**
- `app/Http/Controllers/RegisterController.php`
  - `showRegisterForm()` — tampilkan form register
  - `register()` atau `process()` — proses pendaftaran dan create user baru

**View:**
- `resources/views/auth/register.blade.php`

**Middleware:**
- `app/Http/Middleware/RedirectIfAuthenticated.php` (redirect jika sudah login)

**Validation Rules:**
- Validation rules untuk nama, email, dan password di-define di method `register()`/`process()` di RegisterController

### Tabel Database Terkait
- `users` (menyimpan data akun baru dengan role "siswa")

---

# PENUTUP

Demikian **Buku Panduan Pengguna Website SIMGANG - Bagian 2: Panduan untuk Pengunjung (Tanpa Login)** ini disusun agar dapat membantu masyarakat umum, calon peserta magang, dan pihak-pihak yang berkepentingan dalam menggunakan fitur-fitur publik yang tersedia di website SIMGANG.

Untuk panduan penggunaan fitur-fitur yang memerlukan login (Dashboard Siswa, Mentor, dan Sekretariat), silakan merujuk pada dokumen terpisah:
- **Bagian 3:** Panduan untuk Siswa/Mahasiswa (setelah login)
- **Bagian 4:** Panduan untuk Mentor (setelah login)
- **Bagian 5:** Panduan untuk Sekretariat (setelah login)

Apabila terdapat kendala dalam penggunaan sistem, silakan menghubungi **Dinas Komunikasi dan Informatika Kabupaten Cilacap** melalui informasi kontak yang tersedia pada **Halaman Contact**.

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
