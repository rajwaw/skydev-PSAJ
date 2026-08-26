<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
                    'summary' => 'Gagal menghubungi AI. Silakan coba lagi (error ' . $response->status() . ').',
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
                'summary' => 'Terjadi kesalahan saat menghubungi AI: ' . $e->getMessage(),
            ]);
        }
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
                    $text .= "  Tekanan Darah: {$rm->asuhanMedis->tekanan_darah}, Nadi: {$rm->asuhanMedis->nadi}, Suhu: {$rm->asuhanMedis->suhu_tubuh}, SpO2: {$rm->asuhanMedis->spo2}\n";
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
}