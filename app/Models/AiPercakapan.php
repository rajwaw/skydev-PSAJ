<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiPercakapan extends Model
{
    protected $table = 'ai_percakapan';
    protected $primaryKey = 'id_percakapan';
    protected $fillable = ['id_pasien', 'user_id', 'judul'];

    public function pesan()
    {
        return $this->hasMany(AiPesan::class, 'id_percakapan', 'id_percakapan')->orderBy('created_at');
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien', 'id_pasien');
    }
}