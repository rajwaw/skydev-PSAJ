<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PendaftaranController extends Controller
{
    public function store(Request $request)
    {
        // Validasi data dari form dengan aturan 1 NIK hanya untuk 1 pasien (unique:pasien,nik)
        $validated = $request->validate([
            'nik' => 'required|string|max:20|unique:pasien,nik',
            'nama' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string|max:1',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'golongan_darah' => 'nullable|in:A,B,AB,O',
            'alergi_obat' => 'nullable|string|max:255',
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.unique' => 'NIK sudah terdaftar dalam sistem. 1 NIK hanya berlaku untuk 1 pasien.',
            'nama.required' => 'Nama lengkap wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
        ]);

        try {

            DB::beginTransaction();

            /*
            |--------------------------------------------------------------------------
            | 1. Simpan Data Pasien Baru ke Database
            |--------------------------------------------------------------------------
            */

            $idPasien = DB::table('pasien')->insertGetId([
                'nik' => $request->nik,
                'nama_lengkap' => $request->nama,
                'tgl_lahir' => $request->tanggal_lahir,
                'jk' => $request->jenis_kelamin,
                'alamat' => $request->alamat,
                'no_telp' => $request->telepon,
                'golongan_darah' => $request->golongan_darah,
                'created_at' => now(),
                'updated_at' => now(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | 3. Simpan Alergi Obat ke Database (Tabel alergi_obat)
            |--------------------------------------------------------------------------
            */

            if ($request->filled('alergi_obat')) {
                $alergiInput = trim((string) $request->alergi_obat);
                $lower = strtolower($alergiInput);

                if ($lower !== 'tidak ada' && $lower !== '-' && $lower !== 'none' && $lower !== 'tidak' && $lower !== 'tdk ada') {
                    // Hapus data alergi lama untuk pasien ini agar sinkron
                    DB::table('alergi_obat')->where('id_pasien', $idPasien)->delete();

                    // Dukung pemisahan jika ada beberapa obat yang dipisahkan koma
                    $obatList = array_map('trim', explode(',', $alergiInput));
                    foreach ($obatList as $obat) {
                        if (!empty($obat)) {
                            DB::table('alergi_obat')->insert([
                                'id_pasien' => $idPasien,
                                'nama_obat' => $obat,
                                'keterangan' => 'Dicatat saat pendaftaran',
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                } else {
                    DB::table('alergi_obat')->where('id_pasien', $idPasien)->delete();
                }
            }


            /*
            |--------------------------------------------------------------------------
            | 4. Buat nomor antrean
            |--------------------------------------------------------------------------
            */

            $jumlahHariIni = DB::table('pendaftaran')
                ->whereDate('tgl_daftar', now()->toDateString())
                ->count();

            $nomorAntrean = $jumlahHariIni + 1;


            /*
            |--------------------------------------------------------------------------
            | 5. Simpan data pendaftaran
            |--------------------------------------------------------------------------
            */

            $idPendaftaran = DB::table('pendaftaran')->insertGetId([
                'id_pasien' => $idPasien,
                'tgl_daftar' => now(),
                'no_antrean' => $nomorAntrean,
                'status_kunjungan' => 'Menunggu',
            ]);


            DB::commit();


            /*
            |--------------------------------------------------------------------------
            | 6. Kirim response ke JavaScript
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'success' => true,
                'message' => 'Data pasien berhasil disimpan!',
                'id_pasien' => $idPasien,
                'id_pendaftaran' => $idPendaftaran,
                'no_antrean' => $nomorAntrean,
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Data gagal disimpan.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}