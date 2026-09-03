<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiPesan extends Model
{
    protected $table = 'ai_pesan';
    protected $primaryKey = 'id_pesan';
    protected $fillable = ['id_percakapan', 'role', 'isi'];
}