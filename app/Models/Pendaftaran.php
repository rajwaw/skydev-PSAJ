<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    protected $table = 'pendaftaran';

    protected $primaryKey = 'id_pendaftaran';

    protected $fillable = [
        'id_pasien',
        'tgl_daftar',
        'no_antrean',
        'status_kunjungan',
    ];

    protected $casts = [
        'tgl_daftar' => 'datetime',
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien', 'id_pasien');
    }

    public function rekamMedis()
    {
        return $this->hasOne(RekamMedis::class, 'id_pendaftaran', 'id_pendaftaran');
    }
}
