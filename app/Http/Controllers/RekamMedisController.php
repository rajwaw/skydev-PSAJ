<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\RekamMedis;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class RekamMedisController extends Controller
{
    /**
     * Menampilkan halaman rekam medis dengan data dinamis pasien dari database.
     */
    public function index(Request $request)
    {
        $selectedId = $request->query('pasien_id') ?: $request->query('id');
        $search = $request->query('search');

        // Query seluruh pasien - Urutkan dari pasien yang paling baru mendaftar di urutan paling atas
        $pasienQuery = Pasien::with(['pendaftarans', 'rekamMedisTerbaru', 'alergis'])
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

        // Tentukan pasien yang sedang dipilih (hanya jika ada ID yang diminta secara eksplisit)
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

        $latestAsuhan = $riwayatKunjungan->isNotEmpty() ? $riwayatKunjungan->first()->asuhanMedis : null;

        return view('rekam-medis', compact(
            'daftarPasien',
            'selectedPasien',
            'riwayatKunjungan',
            'latestAsuhan',
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
                    'tb' => $asuhan && $asuhan->tinggi_badan ? $asuhan->tinggi_badan : null,
                    'bb' => $asuhan && $asuhan->berat_badan ? $asuhan->berat_badan : null,
                    'imt' => $asuhan ? $asuhan->imt : null,
                ],
                'diagnosa' => $intervensiList->isNotEmpty() && $intervensiList->first()->diagnosa_awal 
                    ? $intervensiList->first()->diagnosa_awal 
                    : ($asuhan ? $asuhan->keluhan_utama : '-'),
                'prioritas_diagnosa' => $intervensiList->pluck('prioritas_diagnosa')->filter()->first() ?: null,
                'rencana_tindakan' => $intervensiList->pluck('rencana_tindakan')->filter()->values()->all(),
                'implementasi' => $implementasi ? [
                    'tindakan_dilakukan' => $implementasi->tindakan_dilakukan,
                    'resep_obat' => $implementasi->resep_obat ?: '-',
                ] : null,
                'evaluasi' => $evaluasi ? [
                    'status_kondisi' => $evaluasi->status_kondisi,
                    'status_evaluasi' => $evaluasi->status_evaluasi,
                    'keluhan_setelah_tindakan' => $evaluasi->keluhan_setelah_tindakan ?: '-',
                    'respon_pasien' => $evaluasi->respon_pasien ?: '-',
                    'hasil_evaluasi' => $evaluasi->hasil_evaluasi ?: '-',
                    'rencana_selanjutnya' => $evaluasi->rencana_selanjutnya ?: '-',
                    'catatan_soap' => $evaluasi->catatan_soap ?: null,
                    'soap_s' => $evaluasi->soap_s ?: null,
                    'soap_o' => $evaluasi->soap_o ?: null,
                    'soap_a' => $evaluasi->soap_a ?: null,
                    'soap_p' => $evaluasi->soap_p ?: null,
                ] : null,
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

    /**
     * Download / Cetak PDF ringkasan rekam medis dan konsultasi pasien.
     */
    public function downloadPdf(Request $request, $id)
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
        ])->findOrFail($id);

        $selectedRmId = $request->query('rm_id');
        $mode = $request->query('mode');
        $isSingleVisit = false;

        if ($selectedRmId) {
            $filtered = $pasien->rekamMedis->where('id_rekam_medis', (int) $selectedRmId)->values();
            $riwayatKunjungan = $filtered->isNotEmpty() ? $filtered : $pasien->rekamMedis->take(1);
            $isSingleVisit = true;
        } elseif ($mode === 'all') {
            $riwayatKunjungan = $pasien->rekamMedis;
            $isSingleVisit = false;
        } else {
            // Default: ambil 1 pemeriksaan terkini (hari ini / kunjungan terakhir)
            $latestRm = $pasien->rekamMedis->first();
            $riwayatKunjungan = $latestRm ? collect([$latestRm]) : collect();
            $isSingleVisit = true;
        }

        $alergiText = $pasien->alergis->isNotEmpty() 
            ? $pasien->alergis->pluck('nama_obat')->join(', ') 
            : 'Tidak Ada';

        // Logo klinik (JPG tidak membutuhkan ekstensi GD di DomPDF)
        $logoJpg = public_path('images/Logo Mandala Care.jpg');
        $logoPng = public_path('images/Logo Mandala Care.png');
        if (file_exists($logoJpg)) {
            $logoBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoJpg));
        } elseif (file_exists($logoPng)) {
            $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPng));
        } else {
            $logoBase64 = '';
        }

        // Foto tanda tangan Yudha Tama Mandala Putra S. Kep., Ns
        $ttdJpg = public_path('images/Tandatangan om.jpg');
        $ttdPng = public_path('images/Tandatangan om .png');
        if (file_exists($ttdJpg)) {
            $ttdBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($ttdJpg));
        } elseif (file_exists($ttdPng)) {
            $ttdBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($ttdPng));
        } else {
            $ttdBase64 = '';
        }

        $tglCetak = Carbon::now()->translatedFormat('d F Y');

        $pdf = Pdf::loadView('pdf.rekam-medis-pdf', compact(
            'pasien',
            'riwayatKunjungan',
            'alergiText',
            'tglCetak',
            'logoBase64',
            'ttdBase64',
            'isSingleVisit'
        ))->setPaper('a4', 'portrait');

        $cleanName = Str::slug($pasien->nama_lengkap, '_');
        $cleanRM = str_replace('-', '_', $pasien->no_rm);

        if ($isSingleVisit && $riwayatKunjungan->isNotEmpty()) {
            $dateFormatted = Carbon::parse($riwayatKunjungan->first()->tgl_pemeriksaan)->format('Ymd');
            $filename = "Rangkuman_Rekam_Medis_{$cleanName}_{$cleanRM}_{$dateFormatted}.pdf";
        } else {
            $filename = "Rekam_Medis_Lengkap_{$cleanName}_{$cleanRM}.pdf";
        }

        if ($request->query('download')) {
            return $pdf->download($filename);
        }

        return $pdf->stream($filename);
    }

    /**
     * Download / Cetak PDF untuk 1 kunjungan konsultasi spesifik berdasarkan ID Rekam Medis.
     */
    public function downloadKunjunganPdf(Request $request, $id_rekam_medis)
    {
        $rm = RekamMedis::with([
            'pasien.alergis',
            'asuhanMedis',
            'intervensi',
            'implementasi',
            'evaluasi',
            'pendaftaran'
        ])->findOrFail($id_rekam_medis);

        $pasien = $rm->pasien;
        $riwayatKunjungan = collect([$rm]);
        $isSingleVisit = true;

        $alergiText = $pasien->alergis->isNotEmpty() 
            ? $pasien->alergis->pluck('nama_obat')->join(', ') 
            : 'Tidak Ada';

        // Logo klinik (JPG tidak membutuhkan ekstensi GD di DomPDF)
        $logoJpg = public_path('images/Logo Mandala Care.jpg');
        $logoPng = public_path('images/Logo Mandala Care.png');
        if (file_exists($logoJpg)) {
            $logoBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoJpg));
        } elseif (file_exists($logoPng)) {
            $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPng));
        } else {
            $logoBase64 = '';
        }

        // Foto tanda tangan Yudha Tama Mandala Putra S. Kep., Ns
        $ttdJpg = public_path('images/Tandatangan om.jpg');
        $ttdPng = public_path('images/Tandatangan om .png');
        if (file_exists($ttdJpg)) {
            $ttdBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($ttdJpg));
        } elseif (file_exists($ttdPng)) {
            $ttdBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($ttdPng));
        } else {
            $ttdBase64 = '';
        }

        $tglCetak = Carbon::now()->translatedFormat('d F Y');

        $pdf = Pdf::loadView('pdf.rekam-medis-pdf', compact(
            'pasien',
            'riwayatKunjungan',
            'alergiText',
            'tglCetak',
            'logoBase64',
            'ttdBase64',
            'isSingleVisit'
        ))->setPaper('a4', 'portrait');

        $cleanName = Str::slug($pasien->nama_lengkap, '_');
        $cleanRM = str_replace('-', '_', $pasien->no_rm);
        $dateFormatted = Carbon::parse($rm->tgl_pemeriksaan)->format('Ymd');
        $filename = "Rangkuman_Rekam_Medis_{$cleanName}_{$cleanRM}_{$dateFormatted}.pdf";

        if ($request->query('download')) {
            return $pdf->download($filename);
        }

        return $pdf->stream($filename);
    }
}
