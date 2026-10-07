<?php

namespace App\Constants;

class BidangConstants
{
    // Valid bidang choices
    const BIDANG_SEKRETARIAT = 'Sekretariat';
    const BIDANG_KOMUNIKASI_PUBLIK = 'Bidang Informasi dan Komunikasi Publik';
    const BIDANG_INFORMATIKA = 'Bidang Informatika';
    const BIDANG_STATISTIK = 'Bidang Statistik dan Persandian';
    const BIDANG_LPPL = 'Lembaga Penyiaran Publik Lokal';
    const BIDANG_KEPENTINGAN_PUBLIK = 'Bidang Informasi dan Kepentingan Publik';

    /**
     * Get list of valid bidang
     */
    public static function getValidBidang(): array
    {
        return [
            self::BIDANG_SEKRETARIAT,
            self::BIDANG_KOMUNIKASI_PUBLIK,
            self::BIDANG_INFORMATIKA,
            self::BIDANG_STATISTIK,
            self::BIDANG_LPPL,
            self::BIDANG_KEPENTINGAN_PUBLIK,
        ];
    }

    /**
     * Get list of official bidang for Dashboard Sekretariat table display
     * Includes "Sekretariat" solely for historical student data display.
     */
    public static function getDashboardBidangList(): array
    {
        return [
            self::BIDANG_SEKRETARIAT,
            self::BIDANG_KOMUNIKASI_PUBLIK,
            self::BIDANG_INFORMATIKA,
            self::BIDANG_STATISTIK,
        ];
    }

    /**
     * Check if bidang is valid
     */
    public static function isValidBidang(?string $bidang): bool
    {
        return in_array($bidang, self::getValidBidang(), true);
    }

    /**
     * Get bidang information with icon and description for landing page
     */
    public static function getBidangInfo(): array
    {
        return [
            [
                'id' => 'KomunikasiPublik',
                'icon' => 'fa-bullhorn text-warning',
                'title' => self::BIDANG_KOMUNIKASI_PUBLIK,
                'desc' => 'Menyampaikan informasi publik, publikasi kegiatan, dan komunikasi pemerintahan kepada masyarakat.'
            ],
            [
                'id' => 'Informatika',
                'icon' => 'fa-laptop-code text-info',
                'title' => self::BIDANG_INFORMATIKA,
                'desc' => 'Mengelola sistem informasi, aplikasi, dan infrastruktur teknologi informasi di lingkungan Diskominfo.'
            ],
            [
                'id' => 'Statistik',
                'icon' => 'fa-chart-line text-light',
                'title' => self::BIDANG_STATISTIK,
                'desc' => 'Mengelola data statistik, keamanan informasi, dan persandian di wilayah kerja Diskominfo.'
            ]
        ];
    }

    // Data Pejabat Permanen Diskominfo Cilacap
    const KADIS_NAMA = 'BUDDY HARYANTO, S.Sos., M.M.';
    const KADIS_NIP = '19711203 199803 1 002';
    const KADIS_JABATAN = 'Kepala Dinas Komunikasi dan Informatika';

    /**
     * Dapatkan data permanen Kepala Dinas
     */
    public static function getPejabatKadis(): array
    {
        return [
            'nama' => self::KADIS_NAMA,
            'nip' => self::KADIS_NIP,
            'jabatan' => self::KADIS_JABATAN,
        ];
    }

    /**
     * Dapatkan data Kepala Bidang sesuai bidang magang siswa
     */
    public static function getKabidByBidang(?string $bidang): array
    {
        $b = strtolower(trim($bidang ?? ''));

        if (str_contains($b, 'informatika')) {
            return [
                'nama' => 'M. HAYKEL RAKHMATULLOH TAUFIQ, S.Kom., M.M.',
                'nip' => '198503232010011028',
                'jabatan' => 'Kepala Bidang Informatika',
                'pejabat_ttd' => 'Kabid Bidang Informatika',
            ];
        }

        if (str_contains($b, 'statistik') || str_contains($b, 'persandian')) {
            return [
                'nama' => 'IRPAN SETIAWAN, S.Kom.',
                'nip' => '19760908 200501 1 011',
                'jabatan' => 'Kepala Bidang Statistik dan Persandian',
                'pejabat_ttd' => 'Kabid Bidang Statistik dan Persandian',
            ];
        }

        if (str_contains($b, 'informasi') || str_contains($b, 'komunikasi') || str_contains($b, 'ikp') || str_contains($b, 'penyiaran') || str_contains($b, 'lppl')) {
            return [
                'nama' => 'PRIATMOKO, S.Sos.',
                'nip' => '19770225 199703 1 004',
                'jabatan' => 'Kabid Informasi dan Komunikasi Publik',
                'pejabat_ttd' => 'Kabid Bidang Informasi dan Komunikasi Publik',
            ];
        }

        if (str_contains($b, 'sekretariat')) {
            return [
                'nama' => 'YUDDY HERMAWAN, S.Sos., M.M.',
                'nip' => '19780204 200312 1 008',
                'jabatan' => 'Kasubbag Umum dan Kepegawaian',
                'pejabat_ttd' => 'Kasubbag Umum dan Kepegawaian',
            ];
        }

        // Fallback default ke Informatika
        return [
            'nama' => 'M. HAYKEL RAKHMATULLOH TAUFIQ, S.Kom., M.M.',
            'nip' => '198503232010011028',
            'jabatan' => 'Kepala Bidang Informatika',
            'pejabat_ttd' => 'Kabid Bidang Informatika',
        ];
    }
}
