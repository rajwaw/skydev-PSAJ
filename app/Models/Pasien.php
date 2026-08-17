<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pasien extends Model
{
    protected $table = 'pasien';

    protected $primaryKey = 'id_pasien';

    protected $fillable = [
        'nik',
        'nama_lengkap',
        'tgl_lahir',
        'jk',
        'alamat',
        'no_telp',
        'golongan_darah',
    ];

    /**
     * Format tanggal lahir ke bahasa Indonesia (aman tanpa ekstensi intl).
     */
    public function getFormattedTglLahirAttribute()
    {
        if (!$this->tgl_lahir) {
            return '-';
        }

        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        try {
            $timestamp = strtotime($this->tgl_lahir);
            $day = date('j', $timestamp);
            $monthNum = (int) date('n', $timestamp);
            $month = $months[$monthNum] ?? date('F', $timestamp);
            $year = date('Y', $timestamp);

            return "{$day} {$month} {$year}";
        } catch (\Exception $e) {
            return $this->tgl_lahir;
        }
    }

    /**
     * Format jenis kelamin (L -> Laki-laki, P -> Perempuan).
     */
    public function getFormattedJkAttribute()
    {
        $jk = strtoupper(trim((string) $this->jk));
        if ($jk === 'L' || $jk === 'LAKI-LAKI') {
            return 'Laki-laki';
        } elseif ($jk === 'P' || $jk === 'PEREMPUAN') {
            return 'Perempuan';
        }

        return $this->jk ?: '-';
    }

    /**
     * Relasi ke data pendaftaran pasien.
     */
    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class, 'id_pasien', 'id_pasien');
    }
}