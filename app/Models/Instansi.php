<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instansi extends Model
{
    use HasFactory;

    protected $table = 'instansi';

    protected $fillable = [
        'nama',
        'logo',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($instansi) {
            self::syncInstansiIds();
        });
    }

    public function getLogoUrl()
    {
        if ($this->logo && file_exists(public_path('storage/' . $this->logo))) {
            return asset('storage/' . $this->logo);
        }
        return null;
    }

    public static function normalizeSchoolName($name)
    {
        if (empty($name)) return '';

        // Lowercase
        $name = strtolower($name);

        // Replace common symbols/punctuation with spaces
        $name = preg_replace('/[^a-z0-9]/', ' ', $name);

        // Normalize SMKN/SMK Negeri
        $name = preg_replace('/\bsmkn\b/', 'smk n', $name);
        $name = preg_replace('/\bsman\b/', 'sma n', $name);
        $name = preg_replace('/\bsmpn\b/', 'smp n', $name);
        $name = preg_replace('/\bsdn\b/', 'sd n', $name);
        $name = preg_replace('/\bnegeri\b/', 'n', $name);
        $name = preg_replace('/\buniversitas\b/', 'univ', $name);

        // Remove extra spaces
        $name = preg_replace('/\s+/', ' ', $name);
        
        return trim($name);
    }

    public static function getSpecificKeywords($normalized_name)
    {
        $words = explode(' ', $normalized_name);
        $generic = [
            'smk', 'sma', 'smp', 'sd', 'n', 'univ', 'sekolah', 'negeri', 'swasta',
            'dan', 'di', 'ke', 'dari'
        ];
        return array_values(array_filter($words, function($word) use ($generic) {
            return !in_array($word, $generic) && (is_numeric($word) || strlen($word) >= 2);
        }));
    }

    public static function findMatchingInstansi($asal_sekolah)
    {
        if (empty($asal_sekolah)) return null;

        $normalized_input = self::normalizeSchoolName($asal_sekolah);
        $input_keywords = self::getSpecificKeywords($normalized_input);

        $instansis = self::all();
        
        // 1. Exact normalized match
        foreach ($instansis as $inst) {
            if (self::normalizeSchoolName($inst->nama) === $normalized_input) {
                return $inst;
            }
        }

        // 2. Parentheses match (e.g. "UNS" or "UNSOED" alias match)
        foreach ($instansis as $inst) {
            if (preg_match('/\(([^)]+)\)/', $inst->nama, $matches)) {
                $alias = self::normalizeSchoolName($matches[1]);
                if ($alias === $normalized_input) {
                    return $inst;
                }
            }
        }

        // 3. Keyword-based matching with Type constraint
        if (empty($input_keywords)) return null;

        foreach ($instansis as $inst) {
            $normalized_inst = self::normalizeSchoolName($inst->nama);
            
            // Type boundary/conflict check
            $types = ['smk', 'sma', 'smp', 'sd', 'univ', 'politeknik', 'ma', 'mts', 'mi'];
            $input_types = array_intersect(explode(' ', $normalized_input), $types);
            $inst_types = array_intersect(explode(' ', $normalized_inst), $types);
            
            if (!empty($input_types) && !empty($inst_types)) {
                if (empty(array_intersect($input_types, $inst_types))) {
                    continue; // Skip conflicting types
                }
            }

            $inst_keywords = self::getSpecificKeywords($normalized_inst);

            // Check if all input keywords are present in the instansi keywords (containment)
            $match = true;
            foreach ($input_keywords as $kw) {
                if (!in_array($kw, $inst_keywords)) {
                    $match = false;
                    break;
                }
            }
            if ($match) {
                return $inst;
            }

            // Also check reverse
            if (!empty($inst_keywords)) {
                $reverse_match = true;
                foreach ($inst_keywords as $kw) {
                    if (!in_array($kw, $input_keywords)) {
                        $reverse_match = false;
                        break;
                    }
                }
                if ($reverse_match) {
                    return $inst;
                }
            }
        }

        return null;
    }

    public static function syncInstansiIds()
    {
        // Sync SertifikatPKL
        $unlinkedSertifikat = SertifikatPKL::whereNull('instansi_id')->get();
        foreach ($unlinkedSertifikat as $item) {
            $instansi = self::findMatchingInstansi($item->asal_sekolah);
            if ($instansi) {
                $item->update(['instansi_id' => $instansi->id]);
            }
        }

        // Sync PengajuanMagang
        $unlinkedPengajuan = PengajuanMagang::whereNull('instansi_id')->get();
        foreach ($unlinkedPengajuan as $item) {
            $instansi = self::findMatchingInstansi($item->asal_sekolah);
            if ($instansi) {
                $item->update(['instansi_id' => $instansi->id]);
            }
        }
    }

    public function pengajuans()
    {
        return $this->hasMany(PengajuanMagang::class, 'instansi_id');
    }

    public function sertifikats()
    {
        return $this->hasMany(SertifikatPKL::class, 'instansi_id');
    }
}
