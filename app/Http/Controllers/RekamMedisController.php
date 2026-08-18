<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\RekamMedis;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RekamMedisController extends Controller
{
    /**
     * Menampilkan halaman rekam medis dengan data dinamis pasien dari database.
     */
    public function index(Request $request)
    {
        $selectedId = $request->query('pasien_id') ?: $request->query('id');
        $search = $request->query('search');

        // Query seluruh pasien
        $pasienQuery = Pasien::with(['pendaftaranTerbaru', 'rekamMedisTerbaru', 'alergis'])
            ->orderByDesc('id_pasien');

        if ($search) {
            $pasienQuery->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('no_telp', 'like', "%{$search}%");
            });
        }

        $daftarPasien = $pasienQuery->get();

        // Tentukan pasien yang sedang dipilih
        $selectedPasien = null;
        if ($selectedId) {
            $selectedPasien = Pasien::with([
                'pendaftarans' => function ($q) {
                    $q->orderByDesc('tgl_daftar');
                },
                'rekamMedis' => function ($q) {
                    $q->with(['asuhanMedis', 'intervensi', 'implementasi', 'evaluasi', 'pendaftaran'])
                      ->orderByDesc('tgl_pemeriksaan');
                },
                'alergis'
            ])->find($selectedId);
        }

        if (!$selectedPasien && $daftarPasien->isNotEmpty()) {
            $selectedPasien = Pasien::with([
                'pendaftarans' => function ($q) {
                    $q->orderByDesc('tgl_daftar');
                },
                'rekamMedis' => function ($q) {
                    $q->with(['asuhanMedis', 'intervensi', 'implementasi', 'evaluasi', 'pendaftaran'])
                      ->orderByDesc('tgl_pemeriksaan');
                },
                'alergis'
            ])->find($daftarPasien->first()->id_pasien);
        }

        // Format alergi obat
        $alergiText = 'Tidak Ada';
        if ($selectedPasien && $selectedPasien->alergis->isNotEmpty()) {
            $alergiText = $selectedPasien->alergis->pluck('nama_obat')->join(', ');
        }

        // Riwayat kunjungan / rekam medis pasien terpilih
        $riwayatKunjungan = $selectedPasien ? $selectedPasien->rekamMedis : collect();

        // Tanggal kunjungan terakhir
        $lastVisitDate = '-';
        if ($selectedPasien) {
            $latestRegistration = $selectedPasien->pendaftarans->first();
            if ($latestRegistration) {
                $lastVisitDate = Carbon::parse($latestRegistration->tgl_daftar)->translatedFormat('d M Y');
            } elseif ($selectedPasien->created_at) {
                $lastVisitDate = Carbon::parse($selectedPasien->created_at)->translatedFormat('d M Y');
            }
        }

        return view('rekam-medis', compact(
            'daftarPasien',
            'selectedPasien',
            'riwayatKunjungan',
            'alergiText',
            'lastVisitDate',
            'search'
        ));
    }

    /**
     * Endpoint API/AJAX untuk mengambil data detail pasien & rekam medisnya secara dinamis.
     */
    public function getPasienRM($id)
    {
        $pasien = Pasien::with([
            'pendaftarans' => function ($q) {
                $q->orderByDesc('tgl_daftar');
            },
            'rekamMedis' => function ($q) {
                $q->with(['asuhanMedis', 'intervensi', 'implementasi', 'evaluasi', 'pendaftaran'])
                  ->orderByDesc('tgl_pemeriksaan');
            },
            'alergis'
        ])->find($id);

        if (!$pasien) {
            return response()->json([
                'success' => false,
                'message' => 'Pasien tidak ditemukan.'
            ], 404);
        }

        $alergiText = $pasien->alergis->isNotEmpty() 
            ? $pasien->alergis->pluck('nama_obat')->join(', ') 
            : 'Tidak Ada';

        $latestRegistration = $pasien->pendaftarans->first();
        $lastVisitDate = $latestRegistration 
            ? Carbon::parse($latestRegistration->tgl_daftar)->translatedFormat('d M Y') 
            : ($pasien->created_at ? Carbon::parse($pasien->created_at)->translatedFormat('d M Y') : '-');

        $riwayat = $pasien->rekamMedis->map(function ($rm) {
            $asuhan = $rm->asuhanMedis;
            $intervensiList = $rm->intervensi;
            $evaluasi = $rm->evaluasi;
            $implementasi = $rm->implementasi;

            return [
                'id_rekam_medis' => $rm->id_rekam_medis,
                'tgl_pemeriksaan' => Carbon::parse($rm->tgl_pemeriksaan)->translatedFormat('d F Y'),
                'keluhan_utama' => $asuhan ? $asuhan->keluhan_utama : 'Belum diisi',
                'riwayat_keluhan' => $asuhan ? $asuhan->riwayat_keluhan : '-',
                'kondisi_umum' => $asuhan ? $asuhan->kondisi_umum : '-',
                'kesadaran' => $asuhan ? $asuhan->kesadaran : '-',
                'tanda_vital' => [
                    'td' => $asuhan && $asuhan->tekanan_darah ? $asuhan->tekanan_darah : '120/80',
                    'suhu' => $asuhan && $asuhan->suhu_tubuh ? $asuhan->suhu_tubuh : '36.5',
                    'nadi' => $asuhan && $asuhan->nadi ? $asuhan->nadi : '80',
                    'rr' => $asuhan && $asuhan->rr ? $asuhan->rr : '20',
                    'spo2' => $asuhan && $asuhan->spo2 ? $asuhan->spo2 : '98',
                ],
                'diagnosa' => $intervensiList->isNotEmpty() && $intervensiList->first()->diagnosa_awal 
                    ? $intervensiList->first()->diagnosa_awal 
                    : ($asuhan ? $asuhan->keluhan_utama : '-'),
                'rencana_tindakan' => $intervensiList->pluck('rencana_tindakan')->filter()->values()->all(),
                'soap' => [
                    'assessment' => $intervensiList->isNotEmpty() && $intervensiList->first()->diagnosa_awal 
                        ? $intervensiList->first()->diagnosa_awal 
                        : ($asuhan ? 'Keluhan: ' . $asuhan->keluhan_utama : '-'),
                    'plan' => $intervensiList->isNotEmpty() && $intervensiList->first()->rencana_tindakan 
                        ? $intervensiList->pluck('rencana_tindakan')->join('; ') 
                        : ($implementasi ? $implementasi->tindakan_dilakukan : 'Observasi dan istirahat yang cukup.'),
                ]
            ];
        });

        return response()->json([
            'success' => true,
            'pasien' => [
                'id_pasien' => $pasien->id_pasien,
                'nama_lengkap' => $pasien->nama_lengkap,
                'nik' => $pasien->nik,
                'no_rm' => $pasien->no_rm,
                'initials' => $pasien->initials,
                'gender_age' => $pasien->formatted_jk . ', ' . $pasien->age,
                'golongan_darah' => $pasien->golongan_darah ?: '-',
                'alergi' => $alergiText,
                'last_visit' => $lastVisitDate,
            ],
            'riwayat' => $riwayat,
        ]);
    }
}
