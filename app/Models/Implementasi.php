<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Implementasi extends Model
{
    protected $table = 'implementasi';

    protected $primaryKey = 'id_implementasi';

    protected $fillable = [
        'id_rekam_medis',
        'tindakan_dilakukan',
        'resep_obat',
    ];

    public function rekamMedis()
    {
        return $this->belongsTo(RekamMedis::class, 'id_rekam_medis', 'id_rekam_medis');
    }
}
