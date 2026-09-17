# 🧪 PANDUAN TESTING: Feedback Mentor di Tugas Siswa

## 📋 TUJUAN TESTING
Memverifikasi bahwa feedback mentor yang diberikan saat menilai tugas siswa **TAMPIL DENGAN BENAR** di halaman "Tugas Saya" siswa.

---

## ⚠️ PRASYARAT
1. ✅ Laragon harus berjalan (MySQL + Apache/Nginx)
2. ✅ Database `simmagang_cilacapkab_go_id_pkl` sudah ada
3. ✅ Ada minimal 1 akun **Mentor** dan 1 akun **Siswa**
4. ✅ Ada minimal 1 **tugas** yang sudah dikumpulkan siswa (status `submitted`)

---

## 🔧 LANGKAH TESTING

### BAGIAN 1: MENTOR MEMBERIKAN NILAI & FEEDBACK

1. **Login sebagai Mentor**
   - URL: http://127.0.0.1:8000/login
   - Gunakan akun mentor

2. **Buka halaman "Daftar Tugas"**
   - URL: http://127.0.0.1:8000/mentor/tasks/daftar-tugas
   - Pilih salah satu siswa yang sudah mengumpulkan tugas

3. **Klik "Beri Nilai" pada tugas yang sudah dikumpulkan**
   - Status tugas harus "Sudah Dikumpulkan" (badge hijau)
   - Akan muncul modal "Beri Nilai"

4. **Isi Form Penilaian:**
   - **Nilai:** Isi dengan angka 0-100 (contoh: 85)
   - **Feedback/Catatan:** Isi dengan teks feedback (contoh: "Pekerjaan bagus! Namun perlu ditingkatkan di bagian dokumentasi.")
   - Klik **"Simpan Nilai"**

5. **Verifikasi Penyimpanan:**
   - ✅ Harus muncul notifikasi sukses
   - ✅ Status tugas berubah menjadi "Selesai" dengan badge hijau + nilai
   - ✅ **PENTING:** Cek log Laravel untuk memastikan data tersimpan

---

### BAGIAN 2: SISWA MELIHAT FEEDBACK

1. **Logout dari akun Mentor**

2. **Login sebagai Siswa**
   - URL: http://127.0.0.1:8000/login
   - Gunakan akun siswa yang tugasnya baru saja dinilai

3. **Buka halaman "Tugas Saya"**
   - URL: http://127.0.0.1:8000/siswa/tasks
   - Cari tugas yang sudah dinilai (badge hijau "Selesai")

4. **Klik "Lihat" pada tugas tersebut**
   - URL: http://127.0.0.1:8000/siswa/tasks/{id}

5. **VERIFIKASI TAMPILAN FEEDBACK:**
   - ✅ **Status:** Badge hijau "Selesai Dinilai"
   - ✅ **Nilai:** Harus tampil (contoh: "85")
   - ✅ **Dinilai Pada:** Harus ada tanggal & waktu
   - ✅ **Feedback Mentor:** Harus tampil dengan alert biru berisi teks feedback yang diberikan mentor

---

### BAGIAN 3: CEK LOG LARAVEL (Jika Feedback Tidak Tampil)

1. **Buka file log Laravel:**
   - Path: `storage/logs/laravel.log`
   - Atau jalankan: `tail -f storage/logs/laravel.log` (Linux/Mac)
   - Windows: Buka file dengan Notepad++/VSCode

2. **Cari log dengan keyword:**
   ```
   Submission data untuk task_id=
   ```

3. **Verifikasi data log:**
   ```
   [timestamp] local.INFO: Submission data untuk task_id=15 {
       "submission_id": 1,
       "status": "graded",
       "grade": 85,
       "mentor_feedback": "Pekerjaan bagus! ...",
       "graded_at": "2026-08-11 13:45:30"
   }
   ```

4. **Jika feedback NULL di log:**
   - ❌ **MASALAH:** Data tidak tersimpan ke database
   - **Solusi:** Cek console browser saat mentor klik "Simpan Nilai", mungkin ada error JavaScript

---

## 🐛 TROUBLESHOOTING

### Masalah 1: Feedback Tidak Tampil Meskipun Mentor Sudah Beri Nilai

**Diagnosa:**
```bash
# Cek langsung di database
php artisan tinker
>>> DB::table('task_submissions')->where('task_id', 15)->first();
```

**Cek kolom:**
- `status` = 'graded' ✅
- `grade` = (ada nilai) ✅
- `mentor_feedback` = (ada teks) ✅ atau NULL ❌
- `graded_at` = (ada timestamp) ✅

**Jika mentor_feedback = NULL:**
- Mentor lupa mengisi field feedback (opsional)
- ATAU ada bug di JavaScript form submission

---

### Masalah 2: Error "No connection to database"

**Solusi:**
1. Pastikan Laragon berjalan
2. Cek MySQL service aktif
3. Test koneksi: `php artisan migrate:status`

---

### Masalah 3: Label "Feedback Mentor" Tampil Tapi Isinya Kosong

**Diagnosa:**
- Cek apakah `@if($submission->mentor_feedback)` berfungsi
- Cek log Laravel (lihat BAGIAN 3 di atas)
- Jika log menunjukkan `mentor_feedback: null` → Data memang kosong di database

**Solusi:**
- Mentor harus ULANG proses penilaian
- Pastikan field "Feedback/Catatan" diisi (meskipun opsional, untuk testing harus diisi)

---

## ✅ HASIL EXPECTED (SUKSES)

1. ✅ Mentor dapat memberikan nilai & feedback
2. ✅ Data tersimpan ke database (cek log Laravel)
3. ✅ Siswa dapat melihat:
   - Badge "Selesai Dinilai" (hijau)
   - Nilai angka (contoh: 85)
   - Tanggal dinilai
   - **Feedback mentor dalam alert biru** (jika mentor mengisi feedback)

---

## 📝 CATATAN PENTING

1. **Field "Feedback/Catatan" di form mentor bersifat OPSIONAL**
   - Jika mentor tidak mengisi, feedback akan NULL
   - Label "Feedback Mentor" TIDAK AKAN TAMPIL di siswa (karena `@if($submission->mentor_feedback)`)

2. **Status submission harus 'graded'**
   - Jika masih 'submitted' → belum dinilai
   - Jika 'revision' → butuh revisi (feedback untuk revisi)

3. **Log debugging sudah ditambahkan**
   - Setiap kali siswa buka halaman tugas, data submission akan dicatat di log
   - Gunakan untuk troubleshooting jika feedback tidak tampil

---

## 🚀 NEXT STEPS

Setelah testing berhasil:
1. ✅ Konfirmasi feedback mentor tampil dengan benar
2. ✅ Hapus file `check_feedback_data.php` (tidak diperlukan lagi)
3. ✅ Lanjut ke **Task 2: Perbaikan Nilai Sertifikat di halaman Nilai Siswa**
