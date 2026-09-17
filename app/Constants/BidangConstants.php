<?php

namespace App\Constants;

class BidangConstants
{
    // Valid bidang choices
    const BIDANG_SEKRETARIAT = 'Sekretariat';
    const BIDANG_KOMUNIKASI_PUBLIK = 'Bidang Informasi dan Komunikasi Publik';
    const BIDANG_INFORMATIKA = 'Bidang Informatika';
    const BIDANG_STATISTIK = 'Bidang Statistik dan Persandian';

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
}
