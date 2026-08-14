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

        $pasien->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pasien berhasil dihapus'
        ]);
    }
}