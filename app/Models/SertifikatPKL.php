<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SertifikatPKL extends Model
{
    use HasFactory;

    protected $table = 'tbl_sertifikat_pkl';
    protected $primaryKey = 'id_sertifikat';
    public $timestamps = false;

    protected $fillable = [
        'sts_kadis',
        'nama_lengkap',
        'asal_sekolah',
        'instansi_id',
        'nim_nis',
        'tempat_lahir',
        'tgl_lahir',
        'jurusan',
        'tgl_mulai',
        'tgl_selesai',
        'tgl_sertifikat',
        'nm_pembimbing',
        'nik_pembimbing',
        'jabatan_pembimbing',
        'jabatan_pegawai',
        'bidang',
        'no_sertifikat',
        'nm_kadis',
        'nip_kadis',
        'aprove_pembimbing',
        'approve_kadis',
        'sts_diambil',
        'created_id',
        'pejabat_ttd',
        'created_by_role',
        'sertifikat_status',
        'handed_to_mentor_id',
        'handed_over_at',
        'pembimbing_lapangan',
        'nip_pembimbing_lapangan'
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
        'tgl_mulai' => 'date',
        'tgl_selesai' => 'date',
        'tgl_sertifikat' => 'date',
        'aprove_pembimbing' => 'integer',
        'approve_kadis' => 'integer',
        'sts_kadis' => 'integer',
        'sts_diambil' => 'integer',
        'created_id' => 'integer'
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (empty($model->instansi_id) && !empty($model->asal_sekolah)) {
                $instansi = Instansi::findMatchingInstansi($model->asal_sekolah);
                if ($instansi) {
                    $model->instansi_id = $instansi->id;
                }
            } elseif ($model->isDirty('asal_sekolah') && !$model->isDirty('instansi_id')) {
                $instansi = Instansi::findMatchingInstansi($model->asal_sekolah);
                $model->instansi_id = $instansi ? $instansi->id : null;
            }
        });
    }

    // Mutators untuk memastikan format tanggal yang benar
    public function setTglLahirAttribute($value)
    {
        $this->attributes['tgl_lahir'] = $value ? date('Y-m-d', strtotime($value)) : null;
    }

    public function setTglMulaiAttribute($value)
    {
        $this->attributes['tgl_mulai'] = $value ? date('Y-m-d', strtotime($value)) : null;
    }

    public function setTglSelesaiAttribute($value)
    {
        $this->attributes['tgl_selesai'] = $value ? date('Y-m-d', strtotime($value)) : null;
    }

    public function setTglSertifikatAttribute($value)
    {
        $this->attributes['tgl_sertifikat'] = $value ? date('Y-m-d', strtotime($value)) : null;
    }

    // Accessors untuk format tampilan
    public function getTglLahirFormattedAttribute()
    {
        return $this->tgl_lahir ? $this->tgl_lahir->format('d-m-Y') : '';
    }

    public function getTglMulaiFormattedAttribute()
    {
        return $this->tgl_mulai ? $this->tgl_mulai->format('d-m-Y') : '';
    }

    public function getTglSelesaiFormattedAttribute()
    {
        return $this->tgl_selesai ? $this->tgl_selesai->format('d-m-Y') : '';
    }

    public function getTglSertifikatFormattedAttribute()
    {
        return $this->tgl_sertifikat ? $this->tgl_sertifikat->format('d-m-Y') : '';
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'username', 'nim_nis')
                    ->where('role', 'siswa');
    }

    public function userBySertifikat()
    {
        return $this->hasOne(User::class, 'id_sertifikat', 'id_sertifikat');
    }

    public function penilaians()
    {
        return $this->hasMany(Penilaian::class, 'id_sertifikat', 'id_sertifikat');
    }

    public function mentorSiswa()
    {
        return $this->hasOne(MentorSiswa::class, 'siswa_id', 'id_sertifikat')->latestOfMany('created_at');
    }

    public function scopeDiambil($query)
    {
        return $query->where('sts_diambil', 1);
    }

    public function scopeBelumDiambil($query)
    {
        return $query->where('sts_diambil', 0);
    }

    public function scopeApprovePembimbing($query)
    {
        return $query->where('aprove_pembimbing', 1);
    }

    public function scopeApproveKadis($query)
    {
        return $query->where('approve_kadis', 1);
    }

    public function scopeByBidang($query, $bidang)
    {
        return $query->where('bidang', $bidang);
    }

    public function scopeBySekolah($query, $sekolah)
    {
        return $query->where('asal_sekolah', 'LIKE', '%' . $sekolah . '%');
    }

    public function scopeAktif($query)
    {
        return $query->where('sts_diambil', 0)
                    ->where('tgl_selesai', '>=', now());
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('nama_lengkap', 'LIKE', '%' . $search . '%')
              ->orWhere('asal_sekolah', 'LIKE', '%' . $search . '%')
              ->orWhere('bidang', 'LIKE', '%' . $search . '%')
              ->orWhere('nim_nis', 'LIKE', '%' . $search . '%')
              ->orWhere('jurusan', 'LIKE', '%' . $search . '%');
        });
    }

    public function isDiambil()
    {
        return $this->sts_diambil == 1;
    }

    public function isApprovePembimbing()
    {
        return $this->aprove_pembimbing == 1;
    }

    public function isApproveKadis()
    {
        return $this->approve_kadis == 1;
    }

    public function isAktif()
    {
        return $this->tgl_selesai >= now() && $this->sts_diambil == 0;
    }

    public function getStatusAttribute()
    {
        if ($this->sts_diambil == 1) {
            return 'Sudah Diambil';
        } elseif ($this->tgl_selesai < now()) {
            return 'Selesai';
        } elseif ($this->tgl_mulai > now()) {
            return 'Belum Mulai';
        } else {
            return 'Sedang Berjalan';
        }
    }

    public function getStatusPembimbingAttribute()
    {
        return $this->aprove_pembimbing == 1 ? 'Sudah Approve' : 'Belum Approve';
    }

    public function getStatusKadisAttribute()
    {
        return $this->approve_kadis == 1 ? 'Sudah Approve' : 'Belum Approve';
    }


    public static function generateNoSertifikat()
    {
        // Method ini sudah tidak lagi digunakan karena no_sertifikat sekarang optional
        // Disimpan untuk backward compatibility jika diperlukan di masa depan
        $year = date('Y');
        $month = date('m');
        $lastNumber = self::whereYear('tgl_sertifikat', $year)
                         ->whereMonth('tgl_sertifikat', $month)
                         ->count();
        
        $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        
        return "PKL/{$year}/{$month}/{$nextNumber}";
    }


    public function portofolios()
    {
        return $this->hasMany(Portofolio::class, 'sertifikat_pkl_id', 'id_sertifikat');
    }

    // Relasi ke siswa (yang menerima sertifikat PKL)
    public function siswa()
    {
        return $this->hasOne(User::class, 'id_sertifikat', 'id_sertifikat')
                    ->where('role', 'siswa');
    }

    // Relasi ke mentor (yang membuat sertifikat)
    public function mentor()
    {
        return $this->belongsTo(User::class, 'created_id', 'id');
    }

    // Relasi ke handover records
    public function handovers()
    {
        return $this->hasMany(SertifikatHandover::class, 'id_sertifikat', 'id_sertifikat');
    }

    // Relasi ke mentor yang menerima handover
    public function handoverMentor()
    {
        return $this->belongsTo(User::class, 'handed_to_mentor_id', 'id');
    }

    // Relasi ke penempatan bidang
    public function penempatan()
    {
        return $this->hasOne(PenempatanBidang::class, 'id_sertifikat', 'id_sertifikat');
    }

    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }

    /**
     * Helper terpusat untuk mendapatkan data SertifikatPKL milik user (siswa).
     * Menggunakan strategi pencarian bertingkat yang konsisten di seluruh sistem.
     *
     * @param  \App\Models\User|null  $user
     * @return \App\Models\SertifikatPKL|null
     */
    public static function getByUser($user)
    {
        if (!$user) {
            return null;
        }

        // 1. Cari berdasarkan created_id == $user->id
        $sertifikat = self::where('created_id', $user->id)->first();

        // 2. Jika tidak ada, cari berdasarkan nim_nis == $user->username
        if (!$sertifikat && !empty($user->username)) {
            $sertifikat = self::where('nim_nis', $user->username)->first();
        }

        // 3. Jika ada pengajuan magang approved, cari dari nim_nis pengajuan
        if (!$sertifikat) {
            $pengajuan = \App\Models\PengajuanMagang::where('user_id', $user->id)
                ->where('status', 'approved')
                ->first();

            if ($pengajuan && !empty($pengajuan->nim_nis)) {
                $sertifikat = self::where('nim_nis', $pengajuan->nim_nis)->first();
            }
        }

        // 4. Jika user memiliki relasi id_sertifikat
        if (!$sertifikat && isset($user->id_sertifikat) && !empty($user->id_sertifikat)) {
            $sertifikat = self::where('id_sertifikat', $user->id_sertifikat)->first();
        }

        // 5. Pencarian pencocokan fleksibel jika masih belum ditemukan
        if (!$sertifikat && !empty($user->name)) {
            $sertifikat = self::where(function ($query) use ($user) {
                $query->where('created_id', $user->id)
                      ->orWhere('nim_nis', $user->username)
                      ->orWhere('nama_lengkap', 'like', '%' . $user->name . '%');
            })->first();
        }

        return $sertifikat;
    }

    /**
     * Accessor untuk nama Kepala Bidang.
     * Mengutamakan data yang diinput pada Form Langkah 4 (nm_kadis) dari database/form.
     * Jika kosong atau masih berupa nama Kepala Dinas lama (Buddy/Supriyanto),
     * maka menggunakan data default Kepala Bidang berdasarkan bidang siswa.
     */
    public function getNamaKabidAttribute()
    {
        // 1. Cek parameter override dari request (misal saat preview atau filter)
        if (request()->filled('nama_kabid')) {
            return request('nama_kabid');
        }
        if (request()->filled('nm_kadis')) {
            $reqKadis = strtolower(trim(request('nm_kadis')));
            if (!str_contains($reqKadis, 'buddy') && !str_contains($reqKadis, 'supriyanto')) {
                return request('nm_kadis');
            }
        }

        // 2. Prioritaskan data yang tersimpan di database dari inputan Form Langkah 4 (kolom nm_kadis)
        $nmKadis = $this->nm_kadis ?? $this->attributes['nm_kadis'] ?? null;
        if (!empty($nmKadis)) {
            $cleanKadis = strtolower(trim($nmKadis));
            // Jika BUKAN nama Kepala Dinas lama (Buddy Haryanto / Supriyanto)
            if (!str_contains($cleanKadis, 'buddy') && !str_contains($cleanKadis, 'supriyanto')) {
                return $nmKadis;
            }
        }

        // 3. Fallback: Default Kepala Bidang sesuai bidang penempatan siswa
        $bidang = trim(strtolower($this->bidang ?? ''));
        if (str_contains($bidang, 'informatika')) {
            return 'M HAYKEL RAKHMATTULLOH TAUFIQ, S.Kom., M.M.';
        } elseif (str_contains($bidang, 'statistik') || str_contains($bidang, 'persandian')) {
            return 'Dra. ERNY INDRIYANI, M.M.';
        } elseif (str_contains($bidang, 'informasi') || str_contains($bidang, 'komunikasi') || str_contains($bidang, 'ikp')) {
            return 'ARIS RAKHMANTO, S.Kom.';
        }

        return !empty($nmKadis) ? $nmKadis : 'Kepala Bidang';
    }

    /**
     * Accessor untuk NIP Kepala Bidang.
     * Mengutamakan data NIP yang diinput pada Form Langkah 4 (nip_kadis) dari database/form.
     * Jika kosong atau masih berupa NIP Kadis lama, gunakan default NIP sesuai bidang.
     */
    public function getNipKabidAttribute()
    {
        // 1. Cek parameter override dari request
        if (request()->filled('nip_kabid')) {
            return request('nip_kabid');
        }
        if (request()->filled('nip_kadis')) {
            $reqNip = trim(request('nip_kadis'));
            if (!str_contains($reqNip, '19711203') && !str_contains($reqNip, '19650825') && $reqNip !== '-') {
                return $reqNip;
            }
        }

        // 2. Prioritaskan data yang tersimpan di database dari inputan Form Langkah 4 (kolom nip_kadis)
        $nipKadis = $this->nip_kadis ?? $this->attributes['nip_kadis'] ?? null;
        if (!empty($nipKadis)) {
            $cleanNip = trim($nipKadis);
            if (!str_contains($cleanNip, '19711203') && !str_contains($cleanNip, '19650825') && $cleanNip !== '-') {
                return $nipKadis;
            }
        }

        // 3. Fallback: Default NIP Kepala Bidang sesuai bidang siswa
        $bidang = trim(strtolower($this->bidang ?? ''));
        if (str_contains($bidang, 'informatika')) {
            return '198503232010011028';
        } elseif (str_contains($bidang, 'statistik') || str_contains($bidang, 'persandian')) {
            return '19680515 199303 2 007';
        } elseif (str_contains($bidang, 'informasi') || str_contains($bidang, 'komunikasi') || str_contains($bidang, 'ikp')) {
            return '19790618 200501 1 008';
        }

        return !empty($nipKadis) ? $nipKadis : '-';
    }

    /**
     * Helper untuk memeriksa apakah nama pembimbing adalah nama asli (bukan role/dummy akun/Kepala Bidang).
     */
    public static function isRealMentorName(?string $name, ?string $kabidName = null): bool
    {
        if (empty($name)) {
            return false;
        }

        $clean = trim(strtolower($name));

        // Daftar role/username/dummy yang TIDAK boleh digunakan sebagai nama pembimbing
        $blacklisted = [
            'mentor administrator',
            'administrator',
            'admin',
            'user mame',
            'mame',
            'mentor_test',
            'user sekretariat',
            'sekretariat',
            'yo',
            'test',
            'guest',
            '-',
            'null',
        ];

        foreach ($blacklisted as $bad) {
            if ($clean === $bad || str_contains($clean, $bad)) {
                return false;
            }
        }

        // Tidak boleh sama dengan nama Kepala Bidang
        if ($kabidName) {
            $cleanKabid = trim(strtolower(preg_replace('/[^a-zA-Z]/', '', $kabidName)));
            $cleanName = trim(strtolower(preg_replace('/[^a-zA-Z]/', '', $name)));
            if ($cleanName === $cleanKabid || str_contains($cleanName, 'haykel')) {
                return false;
            }
        }

        return true;
    }

    /**
     * Accessor untuk Pembimbing Lapangan / Mentor agar tidak terjadi duplikasi dengan Kepala Bidang
     * dan mengambil dari inputan Langkah 3 Form Edit Siswa (nm_pembimbing) atau pembimbing asli.
     */
    public function getPembimbingLapanganAttribute()
    {
        // 1. Cek jika ada input request override (misal dari form atau parameter URL)
        if (request()->filled('pembimbing_lapangan')) {
            return request('pembimbing_lapangan');
        }
        if (request()->filled('nama_pembimbing')) {
            return request('nama_pembimbing');
        }

        $kabidNama = $this->nama_kabid;

        // 2. Prioritas utama: Ambil data dari inputan Langkah 3 Form Edit Siswa (nm_pembimbing)
        // jika nama pembimbing asli (BUKAN Pak Haykel dan bukan role dummy)
        if (!empty($this->nm_pembimbing) && self::isRealMentorName($this->nm_pembimbing, $kabidNama)) {
            return $this->nm_pembimbing;
        }

        // 3. Cek atribut/kolom kustom pembimbing_lapangan di database jika sudah diset
        if (!empty($this->attributes['pembimbing_lapangan']) && self::isRealMentorName($this->attributes['pembimbing_lapangan'], $kabidNama)) {
            return $this->attributes['pembimbing_lapangan'];
        }

        // 4. Cek dari relasi mentor_siswa (nama lengkap asli pembimbing dari tbl_login)
        if ($this->mentorSiswa && $this->mentorSiswa->mentor) {
            $mentorNama = $this->mentorSiswa->mentor->nama;
            if (self::isRealMentorName($mentorNama, $kabidNama)) {
                return $mentorNama;
            }
        }

        // 5. Cek relasi penempatan_bidang siswa
        if ($this->penempatan && $this->penempatan->user_id) {
            $penempatanUser = User::find($this->penempatan->user_id);
            if ($penempatanUser && self::isRealMentorName($penempatanUser->nama, $kabidNama)) {
                return $penempatanUser->nama;
            }
        }

        // 6. Cek user pembuat jika merupakan mentor dengan nama asli
        if ($this->mentor && ($this->mentor->role ?? '') === 'mentor' && self::isRealMentorName($this->mentor->nama, $kabidNama)) {
            return $this->mentor->nama;
        }

        // 7. Cek handover mentor
        if ($this->handoverMentor && ($this->handoverMentor->role ?? '') === 'mentor' && self::isRealMentorName($this->handoverMentor->nama, $kabidNama)) {
            return $this->handoverMentor->nama;
        }

        // 8. Ambil nama lengkap asli mentor dari tbl_login berdasarkan bidang siswa
        if (!empty($this->bidang)) {
            $realMentor = User::where('role', 'mentor')
                ->where('bidang', $this->bidang)
                ->where('nama', 'NOT LIKE', '%Administrator%')
                ->where('nama', 'NOT LIKE', '%Admin%')
                ->where('nama', 'NOT LIKE', '%mame%')
                ->where('nama', 'NOT LIKE', '%test%')
                ->where('username', 'NOT LIKE', '%test%')
                ->where('nama', '!=', $kabidNama)
                ->orderBy('id', 'asc')
                ->first();

            if ($realMentor && self::isRealMentorName($realMentor->nama, $kabidNama)) {
                return $realMentor->nama;
            }
        }

        // 9. Jika masih belum ada, cari sembarang mentor asli di tbl_login
        $anyRealMentor = User::where('role', 'mentor')
            ->where('nama', 'NOT LIKE', '%Administrator%')
            ->where('nama', 'NOT LIKE', '%Admin%')
            ->where('nama', 'NOT LIKE', '%mame%')
            ->where('nama', 'NOT LIKE', '%test%')
            ->where('nama', '!=', $kabidNama)
            ->first();

        if ($anyRealMentor && self::isRealMentorName($anyRealMentor->nama, $kabidNama)) {
            return $anyRealMentor->nama;
        }

        // 10. Fallback aman
        return 'Pembimbing Lapangan';
    }

    /**
     * Alias accessor $sertifikat->nama_pembimbing sesuai kebutuhan Daftar Nilai.
     */
    public function getNamaPembimbingAttribute()
    {
        return $this->pembimbing_lapangan;
    }

    /**
     * Alias accessor $sertifikat->nip_pembimbing sesuai kebutuhan Daftar Nilai.
     */
    public function getNipPembimbingAttribute()
    {
        return $this->nip_pembimbing_lapangan;
    }

    /**
     * Accessor untuk NIP Pembimbing Lapangan.
     */
    public function getNipPembimbingLapanganAttribute()
    {
        if (request()->filled('nip_pembimbing')) {
            return request('nip_pembimbing');
        }

        $nipKabid = $this->nip_kabid;

        // Ambil dari nik_pembimbing jika bukan NIP Kepala Bidang
        if (!empty($this->nik_pembimbing) && trim($this->nik_pembimbing) !== trim($nipKabid) && $this->nik_pembimbing !== '198503232010011028' && $this->nik_pembimbing !== '-') {
            return $this->nik_pembimbing;
        }

        if (!empty($this->attributes['nip_pembimbing_lapangan'])) {
            return $this->attributes['nip_pembimbing_lapangan'];
        }

        return '-';
    }
}

