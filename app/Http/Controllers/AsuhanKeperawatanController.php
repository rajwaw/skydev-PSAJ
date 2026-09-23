<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\RekamMedis;
use App\Models\AsuhanMedis;
use App\Models\Intervensi;
use App\Models\Implementasi;
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

        // Ambil daftar pasien untuk pencarian & dropdown - Urutkan dari pasien yang paling baru mendaftar
        $pasienQuery = Pasien::with(['pendaftaranTerbaru', 'rekamMedisTerbaru.asuhanMedis', 'alergis'])
            ->select('pasien.*')
            ->selectSub(function ($q) {
                $q->select('id_pendaftaran')
                    ->from('pendaftaran')
                    ->whereColumn('pendaftaran.id_pasien', 'pasien.id_pasien')
                    ->orderByDesc('id_pendaftaran')
                    ->limit(1);
            }, 'latest_pendaftaran_id')
            ->orderByDesc('latest_pendaftaran_id')
            ->orderByDesc('pasien.id_pasien');

        if ($search) {
            $pasienQuery->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('no_telp', 'like', "%{$search}%");
            });
        }

        $daftarPasien = $pasienQuery->get();

        // Tentukan pasien yang terpilih (hanya jika ada ID yang diminta secara spesifik)
        $selectedPasien = null;
        if ($selectedId) {
            $selectedPasien = Pasien::with(['pendaftaranTerbaru', 'rekamMedis' => function ($q) {
                $q->with(['asuhanMedis', 'intervensi', 'implementasi'])->orderByDesc('tgl_pemeriksaan');
            }, 'alergis'])->find($selectedId);
        }

        // Ambil data rekam medis terakhir jika ada
        $latestRekamMedis = $selectedPasien ? $selectedPasien->rekamMedis->first() : null;
        $latestAsuhan = $latestRekamMedis ? $latestRekamMedis->asuhanMedis : null;
        $latestIntervensi = $latestRekamMedis ? $latestRekamMedis->intervensi : collect();
        $latestImplementasi = $latestRekamMedis ? $latestRekamMedis->implementasi : null;

        // Hitung statistik untuk informasi kartu ringkasan
        $totalTindakan = $latestIntervensi->count() ?: 1;

        return view('asuhan-keperawatan', compact(
            'daftarPasien',
            'selectedPasien',
            'latestRekamMedis',
            'latestAsuhan',
            'latestIntervensi',
            'latestImplementasi',
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
                $q->with(['asuhanMedis', 'intervensi', 'implementasi'])->orderByDesc('tgl_pemeriksaan');
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
        $latestImplementasi = $latestRekam ? $latestRekam->implementasi : null;
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
            'implementasi' => $latestImplementasi ? [
                'id_implementasi'    => $latestImplementasi->id_implementasi,
                'tindakan_dilakukan' => $latestImplementasi->tindakan_dilakukan,
                'resep_obat'         => $latestImplementasi->resep_obat,
            ] : null,
            'terakhir_update' => ($latestAsuhan && $latestAsuhan->updated_at)
                ? Carbon::parse($latestAsuhan->updated_at)->timezone('Asia/Jakarta')->translatedFormat('H:i')
                : null,
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
            'tinggi_badan' => 'nullable|numeric|min:0',
            'berat_badan' => 'nullable|numeric|min:0',
            'diagnosa_awal' => 'nullable|string',
            'faktor_terkait' => 'nullable|string',
            'daftar_diagnosa' => 'nullable',
            'prioritas_diagnosa' => 'nullable|string',
            'rencana_tindakan' => 'nullable|array',
            'rencana_tindakan.*.tindakan' => 'nullable|string',
            'rencana_tindakan.*.target' => 'nullable|string',
            'rencana_tindakan.*.keterangan' => 'nullable|string',
            'tindakan_dilakukan' => 'nullable|string',
            'resep_obat' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $idPasien = $request->id_pasien;
            $pasien = Pasien::findOrFail($idPasien);

            // 1. Cari pendaftaran aktif pasien hari ini, atau buat baru jika belum ada
            //    Sebuah pasien boleh punya beberapa kunjungan/kontrol dalam sehari.
            //    Kunjungan yang sudah "Selesai" (sudah dibayar/lunas) dianggap riwayat,
            //    sehingga kontrol berikutnya harus dibuatkan kunjungan baru (Pendaftaran + RekamMedis) terpisah.
            $pendaftaran = Pendaftaran::where('id_pasien', $idPasien)
                ->whereDate('tgl_daftar', now()->toDateString())
                ->where('status_kunjungan', '!=', 'Selesai')
                ->orderByDesc('id_pendaftaran')
                ->first();

            $butuhKunjunganBaru = false;

            if (!$pendaftaran) {
                // Ambil pendaftaran terakhir pasien
                $pendaftaranTerakhir = Pendaftaran::where('id_pasien', $idPasien)
                    ->orderByDesc('id_pendaftaran')
                    ->first();

                // Reuse hanya jika kunjungan terakhir hari ini masih aktif (belum selesai)
                if ($pendaftaranTerakhir
                    && Carbon::parse($pendaftaranTerakhir->tgl_daftar)->isToday()
                    && strtolower((string) $pendaftaranTerakhir->status_kunjungan) !== 'selesai') {
                    $pendaftaran = $pendaftaranTerakhir;
                } else {
                    $butuhKunjunganBaru = true;
                }
            }

            if ($butuhKunjunganBaru) {
                $antreanHariIni = Pendaftaran::whereDate('tgl_daftar', now()->toDateString())->count();
                $pendaftaran = Pendaftaran::create([
                    'id_pasien' => $idPasien,
                    'tgl_daftar' => now(),
                    'no_antrean' => $antreanHariIni + 1,
                    'status_kunjungan' => 'Sedang Diperiksa',
                ]);
            } elseif ($pendaftaran) {
                $pendaftaran->update(['status_kunjungan' => 'Sedang Diperiksa']);
            }

            // 2. Buat atau perbarui record rekam_medis untuk kunjungan ini.
            //    Untuk kunjungan baru, selalu buat RekamMedis baru agar setiap kontrol punya riwayat & pembayaran terpisah.
            $rekamMedis = $butuhKunjunganBaru
                ? null
                : RekamMedis::where('id_pendaftaran', $pendaftaran->id_pendaftaran)
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
                    'tekanan_darah' => $request->tekanan_darah,
                    'nadi' => $request->nadi,
                    'suhu_tubuh' => $request->suhu_tubuh,
                    'rr' => $request->rr,
                    'spo2' => $request->spo2,
                    'tinggi_badan' => $request->tinggi_badan,
                    'berat_badan' => $request->berat_badan,
                ]
            );

            // 4. Simpan / perbarui Intervensi (Diagnosis & Rencana Tindakan)
            // Parse daftar_diagnosa jika ada
            $diagnosaAwal = $request->diagnosa_awal;
            $faktorTerkait = $request->faktor_terkait;

            $daftarDiagnosa = $request->daftar_diagnosa;
            if (is_string($daftarDiagnosa) && (str_starts_with($daftarDiagnosa, '[') || str_starts_with($daftarDiagnosa, '{'))) {
                $daftarDiagnosa = json_decode($daftarDiagnosa, true);
            }

            if (!empty($daftarDiagnosa) && is_array($daftarDiagnosa)) {
                $diagnosaLines = [];
                $faktorLines = [];
                $no = 1;
                foreach ($daftarDiagnosa as $d) {
                    $masalah = trim(is_array($d) ? ($d['masalah'] ?? '') : $d);
                    $faktor = trim(is_array($d) ? ($d['faktor_terkait'] ?? '') : '');

                    if ($masalah !== '') {
                        if ($faktor !== '') {
                            $diagnosaLines[] = "{$no}. {$masalah} Berhubungan dengan {$faktor}";
                            $faktorLines[] = "{$no}. {$faktor}";
                        } else {
                            $diagnosaLines[] = "{$no}. {$masalah}";
                        }
                        $no++;
                    }
                }

                if (!empty($diagnosaLines)) {
                    $diagnosaAwal = implode("\n", $diagnosaLines);
                    $faktorTerkait = !empty($faktorLines) ? implode("\n", $faktorLines) : null;
                }
            }

            // Hapus intervensi lama untuk rekam medis ini lalu buat yang baru
            Intervensi::where('id_rekam_medis', $rekamMedis->id_rekam_medis)->delete();

            $rencanaList = $request->rencana_tindakan;
            $hasCreatedIntervensi = false;
            if (!empty($rencanaList) && is_array($rencanaList)) {
                foreach ($rencanaList as $item) {
                    if (!empty($item['tindakan'])) {
                        Intervensi::create([
                            'id_rekam_medis' => $rekamMedis->id_rekam_medis,
                            'diagnosa_awal' => $diagnosaAwal ?: $request->keluhan_utama,
                            'faktor_terkait' => $faktorTerkait,
                            'prioritas_diagnosa' => $request->prioritas_diagnosa,
                            'rencana_tindakan' => $item['tindakan'],
                            'target' => $item['target'] ?? null,
                            'keterangan' => $item['keterangan'] ?? null,
                        ]);
                        $hasCreatedIntervensi = true;
                    }
                }
            }

            if (!$hasCreatedIntervensi && ($diagnosaAwal || $faktorTerkait || $request->prioritas_diagnosa)) {
                Intervensi::create([
                    'id_rekam_medis' => $rekamMedis->id_rekam_medis,
                    'diagnosa_awal' => $diagnosaAwal ?: $request->keluhan_utama,
                    'faktor_terkait' => $faktorTerkait,
                    'prioritas_diagnosa' => $request->prioritas_diagnosa,
                    'rencana_tindakan' => null,
                    'target' => null,
                    'keterangan' => null,
                ]);
            }

            // 5. Simpan / perbarui Implementasi (Tindakan yang Dilakukan & Resep Obat)
            if ($request->filled('tindakan_dilakukan') || $request->filled('resep_obat') || Implementasi::where('id_rekam_medis', $rekamMedis->id_rekam_medis)->exists()) {
                Implementasi::updateOrCreate(
                    ['id_rekam_medis' => $rekamMedis->id_rekam_medis],
                    [
                        'tindakan_dilakukan' => $request->tindakan_dilakukan,
                        'resep_obat'         => $request->resep_obat,
                    ]
                );
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
