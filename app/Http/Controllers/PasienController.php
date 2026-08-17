<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PasienController extends Controller
{
    /**
     * Menampilkan daftar pasien dan data statistik.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        // 1. Hitung ringkasan statistik (Summary Cards)
        $totalPasien = Pasien::count();

        // Hitung pasien baru bulan ini
        $pasienBaru = Pasien::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Jika created_at belum terisi atau 0, gunakan pendaftaran bulan ini sebagai referensi
        if ($pasienBaru === 0 && $totalPasien > 0) {
            $pasienBaru = DB::table('pendaftaran')
                ->whereMonth('tgl_daftar', now()->month)
                ->whereYear('tgl_daftar', now()->year)
                ->distinct('id_pasien')
                ->count('id_pasien');
        }

        // Kunjungan hari ini
        $kunjunganHariIni = DB::table('pendaftaran')
            ->whereDate('tgl_daftar', now()->toDateString())
            ->count();

        // 2. Query data pasien beserta tanggal kunjungan terakhir dari tabel pendaftaran
        $query = Pasien::query()
            ->select('pasien.*')
            ->selectSub(function ($q) {
                $q->select('tgl_daftar')
                    ->from('pendaftaran')
                    ->whereColumn('pendaftaran.id_pasien', 'pasien.id_pasien')
                    ->orderByDesc('tgl_daftar')
                    ->limit(1);
            }, 'kunjungan_terakhir');

        // Filter pencarian berdasarkan nama, NIK, telepon, atau alamat
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('no_telp', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        // Urutkan dari pasien terbaru (ID terbesar) & paginate 10 per halaman
        $pasiens = $query->orderByDesc('id_pasien')
            ->paginate(10)
            ->withQueryString();

        return view('pasien', compact(
            'pasiens',
            'totalPasien',
            'pasienBaru',
            'kunjunganHariIni',
            'search'
        ));
    }
}
