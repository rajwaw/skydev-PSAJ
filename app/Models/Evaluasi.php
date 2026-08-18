<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluasi extends Model
{
    protected $table = 'evaluasi';

    protected $primaryKey = 'id_evaluasi';

    protected $fillable = [
        'id_rekam_medis',
        'status_kondisi',
        'status_evaluasi',
        'keluhan_setelah_tindakan',
        'respon_pasien',
        'hasil_evaluasi',
        'rencana_selanjutnya',
        'catatan_soap',
    ];

    public function rekamMedis()
    {
        return $this->belongsTo(RekamMedis::class, 'id_rekam_medis', 'id_rekam_medis');
    }
}
