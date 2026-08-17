<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan data statistik dan antrean hari ini di dashboard.
     */
    public function index()
    {
        $today = now()->toDateString();

        // 1. Ringkasan statistik hari ini
        $pasienHariIni = DB::table('pendaftaran')
            ->whereDate('tgl_daftar', $today)
            ->count();

        $antreanMenunggu = DB::table('pendaftaran')
            ->whereDate('tgl_daftar', $today)
            ->where('status_kunjungan', 'Menunggu')
            ->count();

        $sudahDiperiksa = DB::table('pendaftaran')
            ->whereDate('tgl_daftar', $today)
            ->whereIn('status_kunjungan', ['Selesai', 'Sudah Diperiksa'])
            ->count();

        $totalPasien = Pasien::count();

        // 2. Daftar antrean pasien hari ini
        $antreanHariIni = DB::table('pendaftaran')
            ->join('pasien', 'pendaftaran.id_pasien', '=', 'pasien.id_pasien')
            ->whereDate('pendaftaran.tgl_daftar', $today)
            ->select(
                'pendaftaran.*',
                'pasien.nama_lengkap',
                'pasien.nik'
            )
            ->orderBy('pendaftaran.no_antrean', 'asc')
            ->get();

        // 3. Pasien terbaru yang terdaftar
        $pasienTerbaru = Pasien::orderByDesc('id_pasien')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'pasienHariIni',
            'antreanMenunggu',
            'sudahDiperiksa',
            'totalPasien',
            'antreanHariIni',
            'pasienTerbaru'
        ));
    }
}
