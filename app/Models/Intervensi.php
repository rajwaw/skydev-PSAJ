<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Intervensi extends Model
{
    protected $table = 'intervensi';

    protected $primaryKey = 'id_intervensi';

    protected $fillable = [
        'id_rekam_medis',
        'rencana_tindakan',
        'diagnosa_awal',
        'faktor_terkait',
        'prioritas_diagnosa',
        'target',
        'keterangan',
    ];

    public function rekamMedis()
    {
        return $this->belongsTo(RekamMedis::class, 'id_rekam_medis', 'id_rekam_medis');
    }
}
