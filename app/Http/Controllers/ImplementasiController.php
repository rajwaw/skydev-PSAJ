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
            'id_pasien'          => 'required|exists:pasien,id_pasien',
            'id_rekam_medis'     => 'required|exists:rekam_medis,id_rekam_medis',
            'tindakan_dilakukan' => 'required|string',
            'resep_obat'         => 'nullable|string',
        ]);

        try {
            $pasien = Pasien::findOrFail($request->id_pasien);

            Implementasi::updateOrCreate(
                ['id_rekam_medis' => $request->id_rekam_medis],
                [
                    'tindakan_dilakukan' => $request->tindakan_dilakukan,
                    'resep_obat'         => $request->resep_obat,
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
