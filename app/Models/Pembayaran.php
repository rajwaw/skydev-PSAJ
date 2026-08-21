<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'id_pendaftaran',
        'biaya_tindakan',
        'biaya_obat',
        'status_bayar',
        'metode_pembayaran',
        'uang_dibayar',
        'kembalian',
        'catatan',
        'rincian_obat',
        'rincian_tindakan',
    ];

    protected $casts = [
        'biaya_tindakan' => 'float',
        'biaya_obat' => 'float',
        'total_bayar' => 'float',
        'uang_dibayar' => 'float',
        'kembalian' => 'float',
        'rincian_obat' => 'array',
        'rincian_tindakan' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'formatted_total_bayar',
        'formatted_biaya_obat',
        'formatted_biaya_tindakan',
        'formatted_uang_dibayar',
        'formatted_kembalian',
        'formatted_tgl',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'id_pendaftaran', 'id_pendaftaran');
    }

    public function getFormattedTotalBayarAttribute()
    {
        return 'Rp ' . number_format((float) ($this->total_bayar ?? 0), 0, ',', '.');
    }

    public function getFormattedBiayaObatAttribute()
    {
        return 'Rp ' . number_format((float) ($this->biaya_obat ?? 0), 0, ',', '.');
    }

    public function getFormattedBiayaTindakanAttribute()
    {
        return 'Rp ' . number_format((float) ($this->biaya_tindakan ?? 0), 0, ',', '.');
    }

    public function getFormattedUangDibayarAttribute()
    {
        return 'Rp ' . number_format((float) ($this->uang_dibayar ?? 0), 0, ',', '.');
    }

    public function getFormattedKembalianAttribute()
    {
        return 'Rp ' . number_format((float) ($this->kembalian ?? 0), 0, ',', '.');
    }

    public function getFormattedTglAttribute()
    {
        return $this->created_at ? Carbon::parse($this->created_at)->translatedFormat('d M Y, H:i') : '-';
    }
}
