<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekamMedis extends Model
{
    protected $table = 'rekam_medis';

    protected $primaryKey = 'id_rekam_medis';

    protected $fillable = [
        'id_pendaftaran',
        'id_pasien',
        'tgl_pemeriksaan',
    ];

    protected $casts = [
        'tgl_pemeriksaan' => 'datetime',
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien', 'id_pasien');
    }

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'id_pendaftaran', 'id_pendaftaran');
    }

    public function asuhanMedis()
    {
        return $this->hasOne(AsuhanMedis::class, 'id_rekam_medis', 'id_rekam_medis');
    }

    public function intervensi()
    {
        return $this->hasMany(Intervensi::class, 'id_rekam_medis', 'id_rekam_medis');
    }

    public function implementasi()
    {
        return $this->hasOne(Implementasi::class, 'id_rekam_medis', 'id_rekam_medis');
    }

    public function evaluasi()
    {
        return $this->hasOne(Evaluasi::class, 'id_rekam_medis', 'id_rekam_medis');
    }
}
