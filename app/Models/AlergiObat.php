<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlergiObat extends Model
{
    protected $table = 'alergi_obat';

    protected $primaryKey = 'id_alergi';

    protected $fillable = [
        'id_pasien',
        'nama_obat',
        'keterangan',
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien', 'id_pasien');
    }
}
