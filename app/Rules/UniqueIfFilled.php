<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class UniqueIfFilled implements ValidationRule
{
    protected $table;
    protected $column;
    protected $exceptId;
    protected $exceptColumn;

    public function __construct($table, $column, $exceptId = null, $exceptColumn = 'id')
    {
        $this->table = $table;
        $this->column = $column;
        $this->exceptId = $exceptId;
        $this->exceptColumn = $exceptColumn;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Jika value kosong atau strip '-', skip validasi
        if (empty($value) || trim((string)$value) === '' || trim((string)$value) === '-') {
            return;
        }

        // Cek apakah value sudah ada di database
        $query = DB::table($this->table)->where($this->column, $value);

        // Jika ada exceptId, exclude record tertentu
        if ($this->exceptId !== null) {
            $query->where($this->exceptColumn, '!=', $this->exceptId);
        }

        if ($query->exists()) {
            $fail("Nomor sertifikat sudah digunakan.");
        }
    }
}
