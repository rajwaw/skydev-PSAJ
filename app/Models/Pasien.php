<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    ];
}