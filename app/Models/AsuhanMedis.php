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
    ];

    public function rekamMedis()
    {
        return $this->belongsTo(RekamMedis::class, 'id_rekam_medis', 'id_rekam_medis');
    }
}
