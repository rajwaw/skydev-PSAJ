<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\RekamMedis;
use App\Models\Implementasi;
use Illuminate\Http\Request;

class ImplementasiController extends Controller
{
    /**
     * Simpan atau perbarui data implementasi (tindakan yang dilakukan) ke database.
     * Dipanggil via AJAX dari halaman Asuhan Keperawatan atau Evaluasi.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_pasien'              => 'required|exists:pasien,id_pasien',
            'id_rekam_medis'         => 'required|exists:rekam_medis,id_rekam_medis',
            'implementasi_tindakan'  => 'nullable|array',
            'implementasi_tindakan.*.tindakan' => 'nullable|string',
            'implementasi_tindakan.*.obat' => 'nullable|string',
            'implementasi_tindakan.*.keterangan' => 'nullable|string',
            'tindakan_dilakukan'     => 'nullable|string',
            'resep_obat'             => 'nullable|string',
        ]);

        try {
            $pasien = Pasien::findOrFail($request->id_pasien);

            $implementasiList = $request->implementasi_tindakan;
            $items = [];
            $tindakanLines = [];
            $obatLines = [];
            $noTindakan = 1;
            $noObat = 1;

            if (!empty($implementasiList) && is_array($implementasiList)) {
                foreach ($implementasiList as $item) {
                    $tindakan = trim($item['tindakan'] ?? '');
                    $obat = trim($item['obat'] ?? '');
                    $keterangan = trim($item['keterangan'] ?? '');

                    if ($tindakan !== '' || $obat !== '' || $keterangan !== '') {
                        $items[] = [
                            'tindakan'   => $tindakan,
                            'obat'       => $obat,
                            'keterangan' => $keterangan,
                        ];

                        if ($tindakan !== '') {
                            $tText = "{$noTindakan}. {$tindakan}";
                            if ($keterangan !== '') {
                                $tText .= " ({$keterangan})";
                            }
                            $tindakanLines[] = $tText;
                            $noTindakan++;
                        }

                        if ($obat !== '' && $obat !== '-') {
                            $obatLines[] = "{$noObat}. {$obat}";
                            $noObat++;
                        }
                    }
                }
            }

            $tindakanDilakukan = !empty($tindakanLines) ? implode("\n", $tindakanLines) : ($request->tindakan_dilakukan ?: null);
            $resepObat = !empty($obatLines) ? implode("\n", $obatLines) : ($request->resep_obat ?: null);
            $rincianImplementasi = !empty($items) ? $items : null;

            Implementasi::updateOrCreate(
                ['id_rekam_medis' => $request->id_rekam_medis],
                [
                    'tindakan_dilakukan'   => $tindakanDilakukan,
                    'resep_obat'           => $resepObat,
                    'rincian_implementasi' => $rincianImplementasi,
                ]
            );

            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => true,
                    'message' => 'Implementasi tindakan untuk pasien ' . $pasien->nama_lengkap . ' berhasil disimpan!',
                ]);
            }

            return back()->with('success', 'Implementasi tindakan berhasil disimpan!');

        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan implementasi: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage())->withInput();
        }
    }
}
