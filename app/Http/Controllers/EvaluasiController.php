<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\RekamMedis;
use App\Models\Evaluasi;
use App\Models\Implementasi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EvaluasiController extends Controller
{
    /**
     * Menampilkan halaman Evaluasi dengan data dinamis dari database.
     */
    public function index(Request $request)
    {
        $selectedId = $request->query('pasien_id') ?: $request->query('id');
        $search = $request->query('search');

        // Ambil daftar pasien untuk dropdown / pencarian - Urutkan dari pasien yang paling baru mendaftar
        $pasienQuery = Pasien::with(['pendaftaranTerbaru', 'rekamMedisTerbaru', 'alergis'])
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

        // Tentukan pasien yang sedang terpilih (hanya jika ada ID yang diminta secara spesifik)
        $selectedPasien = null;
        if ($selectedId) {
            $selectedPasien = Pasien::with([
                'pendaftaranTerbaru',
                'rekamMedis' => function ($q) {
                    $q->with(['asuhanMedis', 'intervensi', 'implementasi', 'evaluasi'])
                      ->orderByDesc('tgl_pemeriksaan');
                },
                'alergis',
            ])->find($selectedId);
        }

        // Data rekam medis & evaluasi terakhir pasien terpilih
        $latestRekamMedis    = $selectedPasien ? $selectedPasien->rekamMedis->first() : null;
        $latestAsuhan        = $latestRekamMedis ? $latestRekamMedis->asuhanMedis : null;
        $latestIntervensi    = $latestRekamMedis ? $latestRekamMedis->intervensi : collect();
        $latestImplementasi  = $latestRekamMedis ? $latestRekamMedis->implementasi : null;
        $latestEvaluasi      = $latestRekamMedis ? $latestRekamMedis->evaluasi : null;

        // Riwayat evaluasi (semua pasien, 30 terakhir)
        $riwayatEvaluasi = RekamMedis::with(['pasien', 'evaluasi', 'asuhanMedis'])
            ->whereHas('evaluasi')
            ->orderByDesc('tgl_pemeriksaan')
            ->limit(30)
            ->get();

        return view('evaluasi', compact(
            'daftarPasien',
            'selectedPasien',
            'latestRekamMedis',
            'latestAsuhan',
            'latestIntervensi',
            'latestImplementasi',
            'latestEvaluasi',
            'riwayatEvaluasi',
            'search'
        ));
    }

    /**
     * Endpoint AJAX: ambil detail pasien + ringkasan kunjungan + data evaluasi.
     */
    public function getPasienDetail($id)
    {
        $pasien = Pasien::with([
            'pendaftaranTerbaru',
            'rekamMedis' => function ($q) {
                $q->with(['asuhanMedis', 'intervensi', 'implementasi', 'evaluasi'])
                  ->orderByDesc('tgl_pemeriksaan');
            },
            'alergis',
        ])->find($id);

        if (!$pasien) {
            return response()->json([
                'success' => false,
                'message' => 'Data pasien tidak ditemukan.',
            ], 404);
        }

        $latestRekam        = $pasien->rekamMedis->first();
        $latestAsuhan       = $latestRekam ? $latestRekam->asuhanMedis : null;
        $latestIntervensi   = $latestRekam ? $latestRekam->intervensi : collect();
        $latestImplementasi = $latestRekam ? $latestRekam->implementasi : null;
        $latestEvaluasi     = $latestRekam ? $latestRekam->evaluasi : null;
        $latestPendaftaran  = $pasien->pendaftaranTerbaru;

        return response()->json([
            'success' => true,
            'pasien' => [
                'id_pasien'        => $pasien->id_pasien,
                'nama_lengkap'     => $pasien->nama_lengkap,
                'nik'              => $pasien->nik,
                'no_rm'            => $pasien->no_rm,
                'initials'         => $pasien->initials,
                'tgl_kunjungan'    => $latestPendaftaran
                    ? Carbon::parse($latestPendaftaran->tgl_daftar)->translatedFormat('d M Y')
                    : Carbon::now()->translatedFormat('d M Y'),
                'status_kunjungan' => $latestPendaftaran ? $latestPendaftaran->status_kunjungan : 'Menunggu',
                'id_rekam_medis'   => $latestRekam ? $latestRekam->id_rekam_medis : null,
            ],
            'ringkasan' => [
                'keluhan_utama' => $latestAsuhan ? $latestAsuhan->keluhan_utama : '-',
                'diagnosa'      => $latestIntervensi->isNotEmpty() && $latestIntervensi->first()->diagnosa_awal
                    ? $latestIntervensi->first()->diagnosa_awal
                    : ($latestAsuhan ? $latestAsuhan->keluhan_utama : '-'),
                'tindakan'      => $latestImplementasi ? $latestImplementasi->tindakan_dilakukan : '-',
                'intervensi'    => $latestIntervensi->isNotEmpty()
                    ? $latestIntervensi->pluck('rencana_tindakan')->filter()->join('; ')
                    : '-',
            ],
            'implementasi' => $latestImplementasi ? [
                'id_implementasi'    => $latestImplementasi->id_implementasi,
                'tindakan_dilakukan' => $latestImplementasi->tindakan_dilakukan,
                'resep_obat'         => $latestImplementasi->resep_obat ?: '',
            ] : null,
            'evaluasi' => $latestEvaluasi ? [
                'id_evaluasi'              => $latestEvaluasi->id_evaluasi,
                'status_kondisi'           => $latestEvaluasi->status_kondisi,
                'status_evaluasi'          => $latestEvaluasi->status_evaluasi,
                'keluhan_setelah_tindakan' => $latestEvaluasi->keluhan_setelah_tindakan,
                'respon_pasien'            => $latestEvaluasi->respon_pasien,
                'hasil_evaluasi'           => $latestEvaluasi->hasil_evaluasi,
                'rencana_selanjutnya'      => $latestEvaluasi->rencana_selanjutnya,
            ] : null,
        ]);
    }

    /**
     * Simpan atau perbarui data evaluasi ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_pasien'                => 'required|exists:pasien,id_pasien',
            'id_rekam_medis'           => 'required|exists:rekam_medis,id_rekam_medis',
            'tindakan_dilakukan'       => 'required|string',
            'resep_obat'               => 'nullable|string',
            'status_kondisi'           => 'required|string|max:50',
            'status_evaluasi'          => 'required|string|max:50',
            'keluhan_setelah_tindakan' => 'nullable|string',
            'respon_pasien'            => 'nullable|string',
            'hasil_evaluasi'           => 'nullable|string',
            'rencana_selanjutnya'      => 'nullable|string',
        ]);

        try {
            $pasien = Pasien::findOrFail($request->id_pasien);

            // Simpan / update Implementasi
            Implementasi::updateOrCreate(
                ['id_rekam_medis' => $request->id_rekam_medis],
                [
                    'tindakan_dilakukan' => $request->tindakan_dilakukan,
                    'resep_obat'         => $request->resep_obat,
                ]
            );

            // Simpan / update Evaluasi
            Evaluasi::updateOrCreate(
                ['id_rekam_medis' => $request->id_rekam_medis],
                [
                    'status_kondisi'           => $request->status_kondisi,
                    'status_evaluasi'          => $request->status_evaluasi,
                    'keluhan_setelah_tindakan' => $request->keluhan_setelah_tindakan,
                    'respon_pasien'            => $request->respon_pasien,
                    'hasil_evaluasi'           => $request->hasil_evaluasi,
                    'rencana_selanjutnya'      => $request->rencana_selanjutnya,
                ]
            );

            // Kalau evaluasi sudah selesai, update status kunjungan jadi Selesai
            if ($request->status_evaluasi === 'Selesai') {
                $rekamMedis = RekamMedis::with('pendaftaran')->find($request->id_rekam_medis);
                if ($rekamMedis && $rekamMedis->pendaftaran) {
                    $rekamMedis->pendaftaran->update(['status_kunjungan' => 'Selesai']);
                }
            }

            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => true,
                    'message' => 'Evaluasi & Tindakan untuk pasien ' . $pasien->nama_lengkap . ' berhasil disimpan!',
                ]);
            }

            return redirect()->route('evaluasi', ['pasien_id' => $request->id_pasien])
                ->with('success', 'Evaluasi & Tindakan untuk pasien ' . $pasien->nama_lengkap . ' berhasil disimpan!');

        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan evaluasi: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage())->withInput();
        }
    }
}
