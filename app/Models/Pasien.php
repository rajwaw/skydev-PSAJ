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

    protected $appends = [
        'formatted_tgl_lahir',
        'formatted_jk',
        'age',
        'no_rm',
        'initials',
    ];

    /**
     * Format nomor rekam medis otomatis (contoh: RM-2026-016).
     */
    public function getNoRmAttribute()
    {
        $year = $this->created_at ? Carbon::parse($this->created_at)->format('Y') : date('Y');
        return 'RM-' . $year . '-' . str_pad($this->id_pasien, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Inisial nama (contoh: Andi Pratama -> AP).
     */
    public function getInitialsAttribute()
    {
        $words = preg_split('/\s+/', trim((string) $this->nama_lengkap));
        $initials = '';
        foreach ($words as $w) {
            if (!empty($w)) {
                $initials .= mb_substr($w, 0, 1);
            }
        }
        return strtoupper(mb_substr($initials, 0, 2)) ?: 'PS';
    }

    /**
     * Hitung umur pasien berdasarkan tanggal lahir.
     */
    public function getAgeAttribute()
    {
        if (!$this->tgl_lahir) {
            return '-';
        }
        try {
            return Carbon::parse($this->tgl_lahir)->age . ' thn';
        } catch (\Exception $e) {
            return '-';
        }
    }

    /**
     * Format tanggal lahir ke bahasa Indonesia.
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

    public function pendaftaranTerbaru()
    {
        return $this->hasOne(Pendaftaran::class, 'id_pasien', 'id_pasien')->latestOfMany('id_pendaftaran');
    }

    /**
     * Relasi ke rekam medis.
     */
    public function rekamMedis()
    {
        return $this->hasMany(RekamMedis::class, 'id_pasien', 'id_pasien')->orderByDesc('tgl_pemeriksaan');
    }

    public function rekamMedisTerbaru()
    {
        return $this->hasOne(RekamMedis::class, 'id_pasien', 'id_pasien')->latestOfMany('id_rekam_medis');
    }

    /**
     * Relasi ke alergi obat.
     */
    public function alergis()
    {
        return $this->hasMany(AlergiObat::class, 'id_pasien', 'id_pasien');
    }
}