<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\AiPercakapan;
use App\Models\AiPesan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AiClinicalAssistantController extends Controller
{
    public function index(Request $request)
    {
        $daftarPasien = Pasien::orderByDesc('id_pasien')->get();

        return view('ai-clinical-assistant', compact('daftarPasien'));
    }

    public function getSummary(Request $request, $id)
    {
        $pasien = Pasien::with([
            'rekamMedis' => function ($q) {
                $q->orderByDesc('tgl_pemeriksaan')->limit(10);
            },
            'rekamMedis.asuhanMedis',
            'rekamMedis.intervensi',
            'rekamMedis.evaluasi',
            'alergis',
        ])->findOrFail($id);

        $prompt = $this->buildPrompt($pasien);

        try {
            $response = Http::timeout(30)->withHeaders([
                'Content-Type' => 'application/json',
                'x-goog-api-key' => config('services.gemini.key'),
            ])->post(
                'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent',
                [
                    'system_instruction' => [
                        'parts' => [
                            ['text' => 'Kamu adalah asisten yang meringkas riwayat medis pasien untuk tenaga medis klinik. Hanya rangkum data yang diberikan, JANGAN membuat diagnosa baru atau saran pengobatan yang tidak ada di data. Gunakan bahasa Indonesia yang ringkas dan profesional.']
                        ]
                    ],
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                ]
            );


            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'summary' => 'Gagal menghubungi AI. Silakan coba lagi.',
                ]);
            }

            $aiText = $response->json('candidates.0.content.parts.0.text');

            return response()->json([
                'success' => true,
                'summary' => $aiText ?? 'AI tidak mengembalikan hasil.',
            ]);

        } catch (\Exception $e) {
            Log::error('Gemini API exception', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'summary' => 'Terjadi kesalahan saat menghubungi AI. Silakan coba lagi.',
            ]);
        }
    }

    public function getGrafikData(Request $request, $id)
    {
        $pasien = Pasien::with([
            'rekamMedis' => function ($q) {
                $q->reorder('tgl_pemeriksaan')->limit(15);
            },
            'rekamMedis.asuhanMedis',
            'rekamMedis.evaluasi',
        ])->findOrFail($id);

        $tandaVital = [];
        $statusEvaluasiCount = [
            'Stabil' => 0,
            'Membaik' => 0,
            'Tidak Berubah' => 0,
            'Memburuk' => 0,
        ];

        foreach ($pasien->rekamMedis as $rm) {
            $asuhan = $rm->asuhanMedis;

            if ($asuhan) {
                $tandaVital[] = [
                    'tanggal' => $rm->tgl_pemeriksaan ? $rm->tgl_pemeriksaan->format('d/m/y') : null,
                    'tekanan_darah_sistol' => $this->parseAngkaSistol($asuhan->tekanan_darah),
                    'nadi' => $asuhan->nadi !== null ? (float) $asuhan->nadi : null,
                    'suhu' => $asuhan->suhu_tubuh !== null ? (float) $asuhan->suhu_tubuh : null,
                    'spo2' => $asuhan->spo2 !== null ? (float) $asuhan->spo2 : null,
                    'tb' => $asuhan->tinggi_badan !== null ? (float) $asuhan->tinggi_badan : null,
                    'bb' => $asuhan->berat_badan !== null ? (float) $asuhan->berat_badan : null,
                    'imt' => $asuhan->imt !== null ? (float) $asuhan->imt : null,
                ];
            }

            if ($rm->evaluasi && isset($statusEvaluasiCount[$rm->evaluasi->status_kondisi])) {
                $statusEvaluasiCount[$rm->evaluasi->status_kondisi]++;
            }
        }

        return response()->json([
            'success' => true,
            'tanda_vital' => $tandaVital,
            'status_evaluasi' => $statusEvaluasiCount,
        ]);
    }

    private function parseAngkaSistol(?string $tekananDarah): ?float
    {
        if (!$tekananDarah) {
            return null;
        }

        if (preg_match('/(\d+)\s*\/\s*\d+/', $tekananDarah, $m)) {
            return (float) $m[1];
        }

        if (is_numeric($tekananDarah)) {
            return (float) $tekananDarah;
        }

        return null;
    }

    private function buildPrompt(Pasien $pasien): string
    {
        $text = "Data Pasien:\n";
        $text .= "Nama: {$pasien->nama_lengkap}\n";
        $text .= "Umur: {$pasien->age}\n";
        $text .= "Jenis Kelamin: {$pasien->formatted_jk}\n";
        $text .= "Golongan Darah: " . ($pasien->golongan_darah ?? '-') . "\n";

        if ($pasien->alergis->isNotEmpty()) {
            $text .= "Alergi Obat: " . $pasien->alergis->pluck('nama_obat')->implode(', ') . "\n";
        } else {
            $text .= "Alergi Obat: Tidak ada data\n";
        }

        $text .= "\nRiwayat Kunjungan (terbaru ke terlama):\n";

        if ($pasien->rekamMedis->isEmpty()) {
            $text .= "Belum ada riwayat kunjungan.\n";
        } else {
            foreach ($pasien->rekamMedis as $rm) {
                $text .= "\n- Tanggal: {$rm->tgl_pemeriksaan}\n";

                if ($rm->asuhanMedis) {
                    $text .= "  Keluhan Utama: {$rm->asuhanMedis->keluhan_utama}\n";
                    $text .= "  Tekanan Darah: {$rm->asuhanMedis->tekanan_darah}, Nadi: {$rm->asuhanMedis->nadi}, Suhu: {$rm->asuhanMedis->suhu_tubuh}, SpO2: {$rm->asuhanMedis->spo2}";
                    if ($rm->asuhanMedis->tinggi_badan || $rm->asuhanMedis->berat_badan) {
                        $text .= ", TB: " . ($rm->asuhanMedis->tinggi_badan ?? '-') . " cm, BB: " . ($rm->asuhanMedis->berat_badan ?? '-') . " kg";
                        if ($rm->asuhanMedis->imt) {
                            $text .= " (IMT: {$rm->asuhanMedis->imt})";
                        }
                    }
                    $text .= "\n";
                }

                if ($rm->intervensi && $rm->intervensi->isNotEmpty()) {
                    foreach ($rm->intervensi as $iv) {
                        $text .= "  Diagnosa Awal: {$iv->diagnosa_awal}, Rencana Tindakan: {$iv->rencana_tindakan}\n";
                    }
                }

                if ($rm->evaluasi) {
                    $text .= "  Status Evaluasi: {$rm->evaluasi->status_evaluasi}\n";
                    $text .= "  Hasil Evaluasi: {$rm->evaluasi->hasil_evaluasi}\n";
                    $text .= "  Rencana Selanjutnya: {$rm->evaluasi->rencana_selanjutnya}\n";
                }
            }
        }

        $text .= "\nTolong buatkan ringkasan naratif singkat (maksimal 200 kata) mencakup: kondisi umum pasien, hal penting yang perlu diperhatikan (alergi/kondisi khusus), riwayat kunjungan terakhir, dan catatan untuk kunjungan berikutnya jika ada.";

        return $text;
    }

    public function daftarPercakapan(Request $request)
    {
        $percakapan = AiPercakapan::with('pasien')
            ->where('user_id', auth()->id())
            ->orderByDesc('updated_at')
            ->get();

        return response()->json(['success' => true, 'data' => $percakapan]);
    }

    public function buatPercakapan(Request $request)
    {
        $percakapan = AiPercakapan::create([
            'id_pasien' => $request->input('id_pasien'),
            'user_id' => auth()->id(),
            'judul' => null,
        ]);

        return response()->json(['success' => true, 'id_percakapan' => $percakapan->id_percakapan]);
    }

    public function getPercakapan(Request $request, $id)
    {
        $percakapan = AiPercakapan::with(['pesan', 'pasien'])->findOrFail($id);

        return response()->json(['success' => true, 'data' => $percakapan]);
    }

    public function kirimPesan(Request $request, $id)
    {
        $request->validate(['pesan' => 'required|string']);

        $pesanUser = $request->input('pesan');
        $percakapan = AiPercakapan::with(['pesan', 'pasien'])->findOrFail($id);

        $systemPrompt = 'Kamu adalah asisten AI yang membantu tenaga medis klinik dengan informasi klinis. '
            . 'JANGAN membuat diagnosa pasti atau resep obat baru yang tidak berbasis data yang diberikan. '
            . 'Selalu ingatkan bahwa jawabanmu perlu diverifikasi tenaga medis untuk hal yang krusial. '
            . 'Gunakan bahasa Indonesia yang jelas dan profesional.';

        if ($percakapan->pasien) {
            $systemPrompt .= "\n\nKonteks pasien yang sedang dibahas:\n" . $this->buildPrompt($percakapan->pasien);
        }

        $contents = [];
        foreach ($percakapan->pesan()->orderBy('created_at')->get() as $pesan) {
            $contents[] = [
                'role' => $pesan->role === 'user' ? 'user' : 'model',
                'parts' => [['text' => $pesan->isi]],
            ];
        }
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $pesanUser]],
        ];

        try {
            $response = Http::timeout(30)->withHeaders([
                'Content-Type' => 'application/json',
                'x-goog-api-key' => config('services.gemini.key'),
            ])->post(
                'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent',
                [
                    'system_instruction' => ['parts' => [['text' => $systemPrompt]]],
                    'contents' => $contents,
                ]
            );

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghubungi AI. Silakan coba lagi.',
                ]);
            }

            $aiText = $response->json('candidates.0.content.parts.0.text') ?? 'AI tidak mengembalikan hasil.';

            DB::transaction(function () use ($id, $pesanUser, $aiText, $percakapan) {
                AiPesan::create([
                    'id_percakapan' => $id,
                    'role' => 'user',
                    'isi' => $pesanUser,
                ]);

                AiPesan::create([
                    'id_percakapan' => $id,
                    'role' => 'assistant',
                    'isi' => $aiText,
                ]);

                if (!$percakapan->judul) {
                    $percakapan->update(['judul' => Str::limit($pesanUser, 50)]);
                }

                $percakapan->touch();
            });

            return response()->json(['success' => true, 'balasan' => $aiText]);

        } catch (\Exception $e) {
            Log::error('Gemini Chat exception', ['message' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat memproses pesan. Silakan coba lagi.']);
        }
    }
}