<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsuhanMedis extends Model
{
    protected $table = 'asuhan_medis';

    protected $primaryKey = 'id_asuhan_medis';

    protected $fillable = [
        'id_rekam_medis',
        'keluhan_utama',
        'riwayat_keluhan',
        'kondisi_umum',
        'kesadaran',
        'tekanan_darah',
        'suhu_tubuh',
        'nadi',
        'rr',
        'spo2',
        'tinggi_badan',
        'berat_badan',
    ];

    protected $appends = [
        'imt',
    ];

    protected $casts = [
        'tinggi_badan' => 'float',
        'berat_badan' => 'float',
    ];

    /**
     * Menghitung Indeks Massa Tubuh (IMT / BMI) otomatis jika TB dan BB tersedia.
     */
    public function getImtAttribute()
    {
        if ($this->tinggi_badan && $this->berat_badan && $this->tinggi_badan > 0) {
            $tbMeter = $this->tinggi_badan / 100;
            return round($this->berat_badan / ($tbMeter * $tbMeter), 1);
        }
        return null;
    }

    public function rekamMedis()
    {
        return $this->belongsTo(RekamMedis::class, 'id_rekam_medis', 'id_rekam_medis');
    }
}
