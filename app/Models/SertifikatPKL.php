<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Constants\BidangConstants;

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
        'nip_jabatan_pegawai',
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
     * Accessor untuk nama Kepala Dinas permanen (BUDDY HARYANTO, S.Sos., M.M.).
     */
    public function getNmKadisAttribute()
    {
        $raw = $this->attributes['nm_kadis'] ?? '';
        if (str_contains(strtolower($raw), 'supriyanto')) {
            return $raw;
        }
        return BidangConstants::KADIS_NAMA;
    }

    public function getNamaKadisAttribute()
    {
        return $this->nm_kadis;
    }

    /**
     * Accessor untuk NIP Kepala Dinas permanen.
     */
    public function getNipKadisAttribute()
    {
        $raw = $this->attributes['nm_kadis'] ?? '';
        if (str_contains(strtolower($raw), 'supriyanto')) {
            return $this->attributes['nip_kadis'] ?? '19650825 199403 1 009';
        }
        return BidangConstants::KADIS_NIP;
    }

    /**
     * Accessor untuk label jabatan penandatangan di sisi kiri bawah sertifikat / daftar nilai.
     */
    public function getJabatanKabidAttribute()
    {
        if (request()->filled('jabatan_kabid')) {
            return request('jabatan_kabid');
        }

        $mapped = BidangConstants::getPenandatanganByJabatan($this->jabatan_pegawai);
        if ($mapped) {
            return $mapped['label'];
        }

        return 'Kepala Bidang';
    }

    /**
     * Accessor untuk nama penandatangan di sisi kiri bawah sertifikat sesuai pilihan Jabatan Pegawai.
     */
    public function getNamaKabidAttribute()
    {
        // 1. Cek parameter override dari request jika ada
        if (request()->filled('nama_kabid')) {
            return request('nama_kabid');
        }

        // 2. Cek pemetaan berdasarkan Jabatan Pegawai yang dipilih di form
        $mapped = BidangConstants::getPenandatanganByJabatan($this->jabatan_pegawai);
        if ($mapped) {
            return $mapped['nama'];
        }

        // 3. Fallback untuk data lama: cek di tabel unit_kerja berdasarkan bidang siswa
        $bidang = trim($this->bidang ?? '');
        if (!empty($bidang)) {
            $unitKerja = DB::table('unit_kerja')
                ->where('name_unit_kerja', $bidang)
                ->first();
            if ($unitKerja && !empty($unitKerja->nama_kepala)) {
                return $unitKerja->nama_kepala;
            }
        }

        // 4. Fallback pemetaan resmi dari BidangConstants
        $kabidData = BidangConstants::getKabidByBidang($bidang);
        return $kabidData['nama'];
    }

    /**
     * Accessor untuk NIP penandatangan di sisi kiri bawah sertifikat.
     * Mengambil nilai dari input nip_jabatan_pegawai; jika kosong pada jabatan termapping, tampilkan '-'.
     */
    public function getNipKabidAttribute()
    {
        if (request()->filled('nip_kabid')) {
            return request('nip_kabid');
        }

        $nipInput = trim($this->nip_jabatan_pegawai ?? '');

        // Jika Jabatan Pegawai termasuk dalam pemetaan khusus, wajib gunakan nip_jabatan_pegawai (atau '-' jika kosong)
        $mapped = BidangConstants::getPenandatanganByJabatan($this->jabatan_pegawai);
        if ($mapped) {
            return !empty($nipInput) ? $nipInput : '-';
        }

        // Jika NIP diisi manual meskipun jabatan non-mapping
        if (!empty($nipInput)) {
            return $nipInput;
        }

        // Fallback untuk data lama sebelum kolom nip_jabatan_pegawai tersedia
        $bidang = trim($this->bidang ?? '');
        if (!empty($bidang)) {
            $unitKerja = DB::table('unit_kerja')
                ->where('name_unit_kerja', $bidang)
                ->first();
            if ($unitKerja && !empty($unitKerja->nip)) {
                return $unitKerja->nip;
            }
        }

        $kabidData = BidangConstants::getKabidByBidang($bidang);
        return $kabidData['nip'];
    }

    /**
     * Helper untuk memeriksa apakah nama pembimbing adalah nama asli (bukan role/dummy akun/Kepala Bidang/Kepala Dinas).
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

        // Tidak boleh sama dengan nama Kepala Bidang atau Kepala Dinas
        if ($kabidName) {
            $cleanKabid = trim(strtolower(preg_replace('/[^a-zA-Z]/', '', $kabidName)));
            $cleanName = trim(strtolower(preg_replace('/[^a-zA-Z]/', '', $name)));
            if ($cleanName === $cleanKabid) {
                return false;
            }
        }

        if (str_contains($clean, 'haykel') || str_contains($clean, 'priatmoko') || str_contains($clean, 'irpan') || str_contains($clean, 'buddy')) {
            return false;
        }

        return true;
    }

    /**
     * Accessor untuk Pembimbing Lapangan / Mentor.
     * Mengambil langsung dari inputan / database nm_pembimbing siswa secara otomatis.
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

        // 2. Prioritas utama: Ambil data dari kolom nm_pembimbing di database
        if (!empty($this->nm_pembimbing) && trim($this->nm_pembimbing) !== '-' && trim($this->nm_pembimbing) !== '') {
            return $this->nm_pembimbing;
        }

        // 3. Cek atribut/kolom kustom pembimbing_lapangan di database jika ada
        if (!empty($this->attributes['pembimbing_lapangan']) && trim($this->attributes['pembimbing_lapangan']) !== '-') {
            return $this->attributes['pembimbing_lapangan'];
        }

        // 4. Cek dari relasi mentor_siswa (nama lengkap asli pembimbing dari tbl_login)
        if ($this->mentorSiswa && $this->mentorSiswa->mentor && !empty($this->mentorSiswa->mentor->nama)) {
            return $this->mentorSiswa->mentor->nama;
        }

        // 5. Cek relasi penempatan_bidang siswa
        if ($this->penempatan && $this->penempatan->user_id) {
            $penempatanUser = User::find($this->penempatan->user_id);
            if ($penempatanUser && !empty($penempatanUser->nama)) {
                return $penempatanUser->nama;
            }
        }

        // 6. Cek user pembuat jika merupakan mentor
        if ($this->mentor && ($this->mentor->role ?? '') === 'mentor' && !empty($this->mentor->nama)) {
            return $this->mentor->nama;
        }

        // 7. Cek handover mentor
        if ($this->handoverMentor && ($this->handoverMentor->role ?? '') === 'mentor' && !empty($this->handoverMentor->nama)) {
            return $this->handoverMentor->nama;
        }

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

        if (!empty($this->nik_pembimbing) && trim($this->nik_pembimbing) !== '') {
            return $this->nik_pembimbing;
        }

        if (!empty($this->attributes['nip_pembimbing_lapangan'])) {
            return $this->attributes['nip_pembimbing_lapangan'];
        }

        return '-';
    }

    /**
     * Relasi ke Logbook Kegiatan Siswa
     */
    public function logbooks()
    {
        return $this->hasMany(\App\Models\Logbook::class, 'id_sertifikat', 'id_sertifikat');
    }
}

