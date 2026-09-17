<?php

namespace Database\Seeders;

use App\Models\Instansi;
use Illuminate\Database\Seeder;

class InstansiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nama' => 'Universitas Jenderal Soedirman (UNSOED)',
                'logo' => 'logos/unsoed.png',
                'file_sumber' => 'unsoed.png',
            ],
            [
                'nama' => 'Universitas Sebelas Maret (UNS)',
                'logo' => 'logos/uns.jpg',
                'file_sumber' => 'uns.jpg',
            ],
            [
                'nama' => 'Universitas Gajah Mada (UGM)',
                'logo' => 'logos/ugm.jpg',
                'file_sumber' => 'ugm.jpg',
            ],
            [
                'nama' => 'SMK Negeri 1 Cilacap',
                'logo' => 'logos/smkn1cilacap.jpg',
                'file_sumber' => 'smkn1cilacap.jpg',
            ],
            [
                'nama' => 'SMK Negeri 2 Cilacap',
                'logo' => 'logos/smkn2cilacap.jpg',
                'file_sumber' => 'smkn2cilacap.jpg',
            ],
            [
                'nama' => 'SMK Negeri 1 Kawunganten',
                'logo' => 'logos/smkn1kawunganten.png',
                'file_sumber' => 'smkn1kawunganten.png',
            ],
            [
                'nama' => 'Politeknik Negeri Cilacap (PNC)',
                'logo' => 'logos/pnc.png',
                'file_sumber' => 'pnc.png',
            ],
            [
                'nama' => 'Universitas Telkom',
                'logo' => 'logos/univtelkom.png',
                'file_sumber' => 'univtelkom.png',
            ],
            [
                'nama' => 'SMK Muhammadiyah 1 Ajibarang',
                'logo' => 'logos/smkmuhi1ajibarang.jpg',
                'file_sumber' => 'smkmuhi1ajibarang.jpg',
            ],
            [
                'nama' => 'SMK Telkom Purwokerto',
                'logo' => 'logos/smktelkom.png',
                'file_sumber' => 'smktelkom.png',
            ],
            [
                'nama' => 'SMK Negeri 1 Wanareja',
                'logo' => 'logos/smkn1wanareja.jpg',
                'file_sumber' => 'smkn1wanareja.jpg',
            ],
            [
                'nama' => 'Universitas Tidar (UNTIDAR)',
                'logo' => 'logos/untidar.png',
                'file_sumber' => 'untidar.png',
            ],
            [
                'nama' => 'Universitas Muhammadiyah Yogyakarta (UMY)',
                'logo' => 'logos/umy.webp',
                'file_sumber' => 'umy.webp',
            ],
        ];

        // Pastikan folder untuk menyimpan logo di storage publik dan folder sumber di public/images/logos sudah ada
        $storageLogosPath = storage_path('app/public/logos');
        if (!file_exists($storageLogosPath)) {
            mkdir($storageLogosPath, 0755, true);
        }

        $publicLogosPath = public_path('images/logos');
        if (!file_exists($publicLogosPath)) {
            mkdir($publicLogosPath, 0755, true);
        }

        // Gunakan logo default simgang jika file belum di-upload di public/images/logos
        $fallbackLogoSource = public_path('images/logo-simgang.png');

        foreach ($data as $item) {
            $sourceFile = public_path('images/logos/' . $item['file_sumber']);
            $destinationFile = storage_path('app/public/' . $item['logo']);
            $logoPath = null;

            if (file_exists($sourceFile)) {
                // Jika file kustom ada di public/images/logos/, salin ke storage/
                $logoPath = $item['logo'];
                copy($sourceFile, $destinationFile);
                $this->command->info("Berhasil menyalin logo resmi untuk: {$item['nama']}");
            } else {
                // Jika tidak ada di public/images/logos, cek apakah file fisik sudah ada sebelumnya di storage/
                if (file_exists($destinationFile)) {
                    $logoPath = $item['logo'];
                    $this->command->info("Menggunakan logo fisik yang sudah ada di storage untuk: {$item['nama']}");
                } else {
                    // Jika tidak ada di mana pun, biarkan NULL di database (akan memicu fallback logo pendidikan di views)
                    $logoPath = null;
                    $this->command->warn("Logo resmi untuk '{$item['nama']}' belum ditaruh di [public/images/logos/{$item['file_sumber']}]. Menggunakan default pendidikan.");
                }
            }

            Instansi::updateOrCreate(
                ['nama' => $item['nama']],
                ['logo' => $logoPath]
            );
        }

        // Sync existing student data with the newly seeded instances
        Instansi::syncInstansiIds();
    }
}
