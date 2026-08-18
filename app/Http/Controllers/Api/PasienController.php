<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use Illuminate\Http\Request;

class PasienController extends Controller
{
    // GET semua pasien
    public function index()
    {
        $pasien = Pasien::all();

        return response()->json([
            'success' => true,
            'data' => $pasien
        ]);
    }

    // POST tambah pasien
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|string|max:20|unique:pasien,nik',
            'nama_lengkap' => 'required|string|max:100',
            'tgl_lahir' => 'required|date',
            'jk' => 'required|string|max:10',
            'alamat' => 'nullable|string',
            'no_telp' => 'nullable|string|max:20',
        ]);

        $pasien = Pasien::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pasien berhasil ditambahkan',
            'data' => $pasien
        ], 201);
    }

    // GET satu pasien
    public function show($id)
    {
        $pasien = Pasien::find($id);

        if (!$pasien) {
            return response()->json([
                'success' => false,
                'message' => 'Pasien tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $pasien
        ]);
    }

    // PUT update pasien
    public function update(Request $request, $id)
    {
        $pasien = Pasien::find($id);

        if (!$pasien) {
            return response()->json([
                'success' => false,
                'message' => 'Pasien tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'nik' => 'required|string|max:20|unique:pasien,nik,' . $id . ',id_pasien',
            'nama_lengkap' => 'required|string|max:100',
            'tgl_lahir' => 'required|date',
            'jk' => 'required|string|max:10',
            'alamat' => 'nullable|string',
            'no_telp' => 'nullable|string|max:20',
        ]);

        $pasien->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pasien berhasil diperbarui',
            'data' => $pasien
        ]);
    }

    // DELETE pasien
    public function destroy($id)
    {
        $pasien = Pasien::find($id);

        if (!$pasien) {
            return response()->json([
                'success' => false,
                'message' => 'Pasien tidak ditemukan'
            ], 404);
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($id, $pasien) {
                $pendaftaranIds = \Illuminate\Support\Facades\DB::table('pendaftaran')->where('id_pasien', $id)->pluck('id_pendaftaran');
                $rekamMedisIds = \Illuminate\Support\Facades\DB::table('rekam_medis')
                    ->where('id_pasien', $id)
                    ->orWhereIn('id_pendaftaran', $pendaftaranIds)
                    ->pluck('id_rekam_medis');

                if ($rekamMedisIds->isNotEmpty()) {
                    \Illuminate\Support\Facades\DB::table('asuhan_medis')->whereIn('id_rekam_medis', $rekamMedisIds)->delete();
                    \Illuminate\Support\Facades\DB::table('intervensi')->whereIn('id_rekam_medis', $rekamMedisIds)->delete();
                    \Illuminate\Support\Facades\DB::table('implementasi')->whereIn('id_rekam_medis', $rekamMedisIds)->delete();
                    \Illuminate\Support\Facades\DB::table('evaluasi')->whereIn('id_rekam_medis', $rekamMedisIds)->delete();
                    \Illuminate\Support\Facades\DB::table('rekam_medis')->whereIn('id_rekam_medis', $rekamMedisIds)->delete();
                }

                if ($pendaftaranIds->isNotEmpty()) {
                    \Illuminate\Support\Facades\DB::table('pembayaran')->whereIn('id_pendaftaran', $pendaftaranIds)->delete();
                    \Illuminate\Support\Facades\DB::table('pendaftaran')->whereIn('id_pendaftaran', $pendaftaranIds)->delete();
                }

                \Illuminate\Support\Facades\DB::table('alergi_obat')->where('id_pasien', $id)->delete();
                $pasien->delete();
            });

            return response()->json([
                'success' => true,
                'message' => 'Pasien berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus pasien: ' . $e->getMessage()
            ], 500);
        }
    }
}