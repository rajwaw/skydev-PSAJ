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

        // Filter pencarian seluruh database berdasarkan nama, NIK, telepon, atau alamat
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

        // Jika request via AJAX / Live search dari input
        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest' || $request->query('ajax')) {
            return response()->json([
                'tbody' => view('pasien.table', compact('pasiens', 'search'))->render(),
                'pagination' => view('pasien.pagination', compact('pasiens'))->render(),
                'total' => $pasiens->total(),
            ]);
        }

        return view('pasien', compact(
            'pasiens',
            'totalPasien',
            'pasienBaru',
            'kunjunganHariIni',
            'search'
        ));
    }

    /**
     * Menghapus data pasien dan riwayat pendaftaran serta rekam medisnya dari database.
     */
    public function destroy($id)
    {
        $pasien = Pasien::where('id_pasien', $id)->first();

        if (!$pasien) {
            if (request()->ajax() || request()->wantsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pasien tidak ditemukan.'
                ], 404);
            }
            return redirect()->route('pasien')->with('error', 'Data pasien tidak ditemukan.');
        }

        $nama = $pasien->nama_lengkap;

        try {
            // Gunakan database transaction untuk menghapus seluruh relasi data pasien
            DB::transaction(function () use ($id) {
                $pendaftaranIds = DB::table('pendaftaran')->where('id_pasien', $id)->pluck('id_pendaftaran');
                $rekamMedisIds = DB::table('rekam_medis')
                    ->where('id_pasien', $id)
                    ->orWhereIn('id_pendaftaran', $pendaftaranIds)
                    ->pluck('id_rekam_medis');

                if ($rekamMedisIds->isNotEmpty()) {
                    DB::table('asuhan_medis')->whereIn('id_rekam_medis', $rekamMedisIds)->delete();
                    DB::table('intervensi')->whereIn('id_rekam_medis', $rekamMedisIds)->delete();
                    DB::table('implementasi')->whereIn('id_rekam_medis', $rekamMedisIds)->delete();
                    DB::table('evaluasi')->whereIn('id_rekam_medis', $rekamMedisIds)->delete();
                    DB::table('rekam_medis')->whereIn('id_rekam_medis', $rekamMedisIds)->delete();
                }

                if ($pendaftaranIds->isNotEmpty()) {
                    DB::table('pembayaran')->whereIn('id_pendaftaran', $pendaftaranIds)->delete();
                    DB::table('pendaftaran')->whereIn('id_pendaftaran', $pendaftaranIds)->delete();
                }

                DB::table('alergi_obat')->where('id_pasien', $id)->delete();
                Pasien::where('id_pasien', $id)->delete();
            });

            // Hitung ulang total pasien setelah penghapusan
            $totalPasien = Pasien::count();

            if (request()->ajax() || request()->wantsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => true,
                    'message' => "Data pasien {$nama} berhasil dihapus dari sistem.",
                    'total' => $totalPasien
                ]);
            }

            return redirect()->route('pasien')->with('success', "Data pasien {$nama} berhasil dihapus dari sistem.");

        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => "Gagal menghapus data pasien: " . $e->getMessage()
                ], 500);
            }

            return redirect()->route('pasien')->with('error', "Gagal menghapus data pasien: " . $e->getMessage());
        }
    }
}
