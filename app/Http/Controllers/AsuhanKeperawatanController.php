<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\RekamMedis;
use App\Models\AsuhanMedis;
use App\Models\Intervensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AsuhanKeperawatanController extends Controller
{
    /**
     * Menampilkan halaman Asuhan Keperawatan dengan data pasien dari database.
     */
    public function index(Request $request)
    {
        $selectedId = $request->query('pasien_id') ?: $request->query('id');
        $search = $request->query('search');

        // Ambil daftar pasien untuk pencarian & dropdown
        $pasienQuery = Pasien::with(['pendaftaranTerbaru', 'rekamMedisTerbaru.asuhanMedis'])
            ->orderByDesc('id_pasien');

        if ($search) {
            $pasienQuery->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('no_telp', 'like', "%{$search}%");
            });
        }

        $daftarPasien = $pasienQuery->get();

        // Tentukan pasien yang terpilih
        $selectedPasien = null;
        if ($selectedId) {
            $selectedPasien = Pasien::with(['pendaftaranTerbaru', 'rekamMedis' => function ($q) {
                $q->with(['asuhanMedis', 'intervensi'])->orderByDesc('tgl_pemeriksaan');
            }, 'alergis'])->find($selectedId);
        }

        if (!$selectedPasien && $daftarPasien->isNotEmpty()) {
            $selectedPasien = Pasien::with(['pendaftaranTerbaru', 'rekamMedis' => function ($q) {
                $q->with(['asuhanMedis', 'intervensi'])->orderByDesc('tgl_pemeriksaan');
            }, 'alergis'])->find($daftarPasien->first()->id_pasien);
        }

        // Ambil data rekam medis terakhir jika ada
        $latestRekamMedis = $selectedPasien ? $selectedPasien->rekamMedis->first() : null;
        $latestAsuhan = $latestRekamMedis ? $latestRekamMedis->asuhanMedis : null;
        $latestIntervensi = $latestRekamMedis ? $latestRekamMedis->intervensi : collect();

        // Hitung statistik untuk informasi kartu ringkasan
        $totalTindakan = $latestIntervensi->count() ?: 2;

        return view('asuhan-keperawatan', compact(
            'daftarPasien',
            'selectedPasien',
            'latestRekamMedis',
            'latestAsuhan',
            'latestIntervensi',
            'totalTindakan',
            'search'
        ));
    }

    /**
     * Endpoint API/AJAX untuk mencari pasien dan mendapatkan data lengkapnya.
     */
    public function getPasienDetail($id)
    {
        $pasien = Pasien::with([
            'pendaftaranTerbaru',
            'rekamMedis' => function ($q) {
                $q->with(['asuhanMedis', 'intervensi'])->orderByDesc('tgl_pemeriksaan');
            },
            'alergis'
        ])->find($id);

        if (!$pasien) {
            return response()->json([
                'success' => false,
                'message' => 'Data pasien tidak ditemukan.'
            ], 404);
        }

        $latestRekam = $pasien->rekamMedis->first();
        $latestAsuhan = $latestRekam ? $latestRekam->asuhanMedis : null;
        $latestIntervensi = $latestRekam ? $latestRekam->intervensi : [];
        $latestPendaftaran = $pasien->pendaftaranTerbaru;

        $alergiText = $pasien->alergis->isNotEmpty() 
            ? $pasien->alergis->pluck('nama_obat')->join(', ') 
            : 'Tidak Ada';

        return response()->json([
            'success' => true,
            'pasien' => [
                'id_pasien' => $pasien->id_pasien,
                'nama_lengkap' => $pasien->nama_lengkap,
                'nik' => $pasien->nik,
                'no_rm' => $pasien->no_rm,
                'initials' => $pasien->initials,
                'formatted_tgl_lahir' => $pasien->formatted_tgl_lahir,
                'age' => $pasien->age,
                'formatted_jk' => $pasien->formatted_jk,
                'golongan_darah' => $pasien->golongan_darah ?: '-',
                'alergi' => $alergiText,
                'alamat' => $pasien->alamat ?: '-',
                'no_telp' => $pasien->no_telp ?: '-',
                'tgl_kunjungan' => $latestPendaftaran ? Carbon::parse($latestPendaftaran->tgl_daftar)->translatedFormat('d M Y') : Carbon::now()->translatedFormat('d M Y'),
                'status_kunjungan' => $latestPendaftaran ? $latestPendaftaran->status_kunjungan : 'Menunggu',
                'id_pendaftaran' => $latestPendaftaran ? $latestPendaftaran->id_pendaftaran : null,
            ],
            'asuhan' => $latestAsuhan,
            'intervensi' => $latestIntervensi,
        ]);
    }

    /**
     * Menyimpan atau memperbarui data Asuhan Keperawatan ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pasien' => 'required|exists:pasien,id_pasien',
            'keluhan_utama' => 'required|string',
            'riwayat_keluhan' => 'nullable|string',
            'kondisi_umum' => 'nullable|string|max:50',
            'kesadaran' => 'nullable|string|max:50',
            'tekanan_darah' => 'nullable|string|max:20',
            'nadi' => 'nullable|numeric',
            'suhu_tubuh' => 'nullable|numeric',
            'rr' => 'nullable|numeric',
            'spo2' => 'nullable|numeric',
            'diagnosa_awal' => 'nullable|string',
            'faktor_terkait' => 'nullable|string',
            'prioritas_diagnosa' => 'nullable|string',
            'rencana_tindakan' => 'nullable|array',
            'rencana_tindakan.*.tindakan' => 'nullable|string',
            'rencana_tindakan.*.target' => 'nullable|string',
            'rencana_tindakan.*.keterangan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $idPasien = $request->id_pasien;
            $pasien = Pasien::findOrFail($idPasien);

            // 1. Cari pendaftaran aktif pasien hari ini, atau buat baru jika belum ada
            $pendaftaran = Pendaftaran::where('id_pasien', $idPasien)
                ->whereDate('tgl_daftar', now()->toDateString())
                ->orderByDesc('id_pendaftaran')
                ->first();

            if (!$pendaftaran) {
                // Ambil pendaftaran terakhir pasien atau buat pendaftaran baru hari ini
                $pendaftaranTerakhir = Pendaftaran::where('id_pasien', $idPasien)
                    ->orderByDesc('id_pendaftaran')
                    ->first();

                if ($pendaftaranTerakhir && Carbon::parse($pendaftaranTerakhir->tgl_daftar)->isToday()) {
                    $pendaftaran = $pendaftaranTerakhir;
                } else {
                    $antreanHariIni = Pendaftaran::whereDate('tgl_daftar', now()->toDateString())->count();
                    $pendaftaran = Pendaftaran::create([
                        'id_pasien' => $idPasien,
                        'tgl_daftar' => now(),
                        'no_antrean' => $antreanHariIni + 1,
                        'status_kunjungan' => 'Sedang Diperiksa',
                    ]);
                }
            } else {
                $pendaftaran->update(['status_kunjungan' => 'Sedang Diperiksa']);
            }

            // 2. Buat atau perbarui record rekam_medis untuk kunjungan ini
            $rekamMedis = RekamMedis::where('id_pendaftaran', $pendaftaran->id_pendaftaran)
                ->where('id_pasien', $idPasien)
                ->first();

            if (!$rekamMedis) {
                $rekamMedis = RekamMedis::create([
                    'id_pendaftaran' => $pendaftaran->id_pendaftaran,
                    'id_pasien' => $idPasien,
                    'tgl_pemeriksaan' => now(),
                ]);
            } else {
                $rekamMedis->update(['tgl_pemeriksaan' => now()]);
            }

            // 3. Simpan / perbarui Asuhan Medis (Pengkajian & Tanda Vital)
            AsuhanMedis::updateOrCreate(
                ['id_rekam_medis' => $rekamMedis->id_rekam_medis],
                [
                    'keluhan_utama' => $request->keluhan_utama,
                    'riwayat_keluhan' => $request->riwayat_keluhan,
                    'kondisi_umum' => $request->kondisi_umum ?: 'Baik',
                    'kesadaran' => $request->kesadaran ?: 'Compos Mentis',
                    'tekanan_darah' => $request->tekanan_darah ?: '120/80',
                    'nadi' => $request->nadi ?: 80,
                    'suhu_tubuh' => $request->suhu_tubuh ?: 36.5,
                    'rr' => $request->rr ?: 20,
                    'spo2' => $request->spo2 ?: 98,
                ]
            );

            // 4. Simpan / perbarui Intervensi (Diagnosis & Rencana Tindakan)
            // Hapus intervensi lama untuk rekam medis ini lalu buat yang baru
            Intervensi::where('id_rekam_medis', $rekamMedis->id_rekam_medis)->delete();

            $rencanaList = $request->rencana_tindakan;
            if (!empty($rencanaList) && is_array($rencanaList)) {
                foreach ($rencanaList as $item) {
                    if (!empty($item['tindakan'])) {
                        Intervensi::create([
                            'id_rekam_medis' => $rekamMedis->id_rekam_medis,
                            'diagnosa_awal' => $request->diagnosa_awal ?: $request->keluhan_utama,
                            'faktor_terkait' => $request->faktor_terkait,
                            'prioritas_diagnosa' => $request->prioritas_diagnosa,
                            'rencana_tindakan' => $item['tindakan'],
                            'target' => $item['target'] ?? '-',
                            'keterangan' => $item['keterangan'] ?? '-',
                        ]);
                    }
                }
            } else {
                // Fallback default intervensi jika tidak ada baris tabel rencana
                Intervensi::create([
                    'id_rekam_medis' => $rekamMedis->id_rekam_medis,
                    'diagnosa_awal' => $request->diagnosa_awal ?: $request->keluhan_utama,
                    'faktor_terkait' => $request->faktor_terkait,
                    'prioritas_diagnosa' => $request->prioritas_diagnosa,
                    'rencana_tindakan' => 'Monitor tanda vital secara berkala',
                    'target' => 'Kondisi pasien stabil',
                    'keterangan' => 'Rutin',
                ]);
            }

            DB::commit();

            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => true,
                    'message' => 'Asuhan keperawatan untuk pasien ' . $pasien->nama_lengkap . ' berhasil disimpan!',
                    'id_rekam_medis' => $rekamMedis->id_rekam_medis,
                    'id_pasien' => $idPasien,
                ]);
            }

            return redirect()->route('asuhan-keperawatan', ['pasien_id' => $idPasien])
                ->with('success', 'Asuhan keperawatan untuk pasien ' . $pasien->nama_lengkap . ' berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan asuhan keperawatan: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage())->withInput();
        }
    }
}
