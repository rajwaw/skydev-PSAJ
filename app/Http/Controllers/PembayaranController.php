<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\RekamMedis;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PembayaranController extends Controller
{
    /**
     * Menampilkan halaman Pembayaran dengan data pasien, ringkasan pendapatan, dan riwayat transaksi.
     */
    public function index(Request $request)
    {
        $selectedId = $request->query('pasien_id') ?: $request->query('id');
        $search = $request->query('search');

        // 1. Ambil daftar pasien untuk dropdown / pencarian
        $pasienQuery = Pasien::with(['pendaftaranTerbaru.pembayaran', 'rekamMedisTerbaru.asuhanMedis', 'alergis'])
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

        // 2. Pasien terpilih
        $selectedPasien = null;
        if ($selectedId) {
            $selectedPasien = Pasien::with([
                'pendaftaranTerbaru.pembayaran',
                'rekamMedis' => function ($q) {
                    $q->with(['asuhanMedis', 'intervensi', 'implementasi', 'evaluasi'])
                      ->orderByDesc('tgl_pemeriksaan');
                },
                'alergis',
            ])->find($selectedId);
        }

        $latestPendaftaran  = $selectedPasien ? $selectedPasien->pendaftaranTerbaru : null;
        $latestRekamMedis   = $selectedPasien ? $selectedPasien->rekamMedis->first() : null;
        $latestAsuhan       = $latestRekamMedis ? $latestRekamMedis->asuhanMedis : null;
        $latestIntervensi   = $latestRekamMedis ? $latestRekamMedis->intervensi : collect();
        $latestImplementasi = $latestRekamMedis ? $latestRekamMedis->implementasi : null;
        $existingPembayaran = $latestPendaftaran ? $latestPendaftaran->pembayaran : null;

        // 3. Ringkasan Pendapatan & Statistik
        $today = Carbon::today();
        $pendapatanHariIni = Pembayaran::whereDate('created_at', $today)
            ->where(function ($q) {
                $q->where('status_bayar', 'lunas')
                  ->orWhere('status_bayar', 'Lunas');
            })
            ->sum('total_bayar');

        $transaksiHariIni = Pembayaran::whereDate('created_at', $today)
            ->where(function ($q) {
                $q->where('status_bayar', 'lunas')
                  ->orWhere('status_bayar', 'Lunas');
            })
            ->count();

        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $pendapatanMingguIni = Pembayaran::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->where(function ($q) {
                $q->where('status_bayar', 'lunas')
                  ->orWhere('status_bayar', 'Lunas');
            })
            ->sum('total_bayar');

        $transaksiMingguIni = Pembayaran::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->where(function ($q) {
                $q->where('status_bayar', 'lunas')
                  ->orWhere('status_bayar', 'Lunas');
            })
            ->count();

        $pendapatanBulanIni = Pembayaran::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->where(function ($q) {
                $q->where('status_bayar', 'lunas')
                  ->orWhere('status_bayar', 'Lunas');
            })
            ->sum('total_bayar');

        $transaksiBulanIni = Pembayaran::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->where(function ($q) {
                $q->where('status_bayar', 'lunas')
                  ->orWhere('status_bayar', 'Lunas');
            })
            ->count();

        // 4. Riwayat Pembayaran (Semua transaksi terakhir)
        $riwayatPembayaran = Pembayaran::with([
            'pendaftaran.pasien',
            'pendaftaran.rekamMedis.asuhanMedis',
            'pendaftaran.rekamMedis.implementasi',
        ])
        ->orderByDesc('id_pembayaran')
        ->limit(30)
        ->get();

        return view('pembayaran', compact(
            'daftarPasien',
            'selectedPasien',
            'latestPendaftaran',
            'latestRekamMedis',
            'latestAsuhan',
            'latestIntervensi',
            'latestImplementasi',
            'existingPembayaran',
            'pendapatanHariIni',
            'transaksiHariIni',
            'pendapatanMingguIni',
            'transaksiMingguIni',
            'pendapatanBulanIni',
            'transaksiBulanIni',
            'riwayatPembayaran',
            'search'
        ));
    }

    /**
     * Endpoint AJAX: Ambil detail pasien, rekam medis terakhir, resep obat, dan status pembayaran.
     */
    public function getPasienDetail($id)
    {
        $pasien = Pasien::with([
            'pendaftaranTerbaru.pembayaran',
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

        $latestPendaftaran  = $pasien->pendaftaranTerbaru;
        $latestRekam        = $pasien->rekamMedis->first();
        $latestAsuhan       = $latestRekam ? $latestRekam->asuhanMedis : null;
        $latestIntervensi   = $latestRekam ? $latestRekam->intervensi : collect();
        $latestImplementasi = $latestRekam ? $latestRekam->implementasi : null;
        $existingPembayaran = $latestPendaftaran ? $latestPendaftaran->pembayaran : null;

        // Auto-extract suggested medicines from prescription text (resep_obat)
        $obatSuggestions = [];
        if ($latestImplementasi && !empty($latestImplementasi->resep_obat)) {
            $lines = preg_split('/[\r\n,;]+/', $latestImplementasi->resep_obat);
            foreach ($lines as $line) {
                $clean = trim($line);
                if (!empty($clean)) {
                    // Coba deteksi pola kuantitas jika ada (contoh: "Paracetamol 500mg 2 strip" atau "Amoxicillin x2")
                    $qty = 1;
                    $namaObat = $clean;
                    if (preg_match('/(\d+)\s*(strip|tablet|tab|kapsul|btl|botol|pcs|x)/i', $clean, $m)) {
                        $qty = (int)$m[1];
                    }
                    $obatSuggestions[] = [
                        'nama' => $namaObat,
                        'jumlah' => $qty > 0 ? $qty : 1,
                        'harga' => 0,
                        'subtotal' => 0,
                    ];
                }
            }
        }

        return response()->json([
            'success' => true,
            'pasien' => [
                'id_pasien'        => $pasien->id_pasien,
                'nama_lengkap'     => $pasien->nama_lengkap,
                'nik'              => $pasien->nik,
                'no_rm'            => $pasien->no_rm,
                'initials'         => $pasien->initials,
                'age'              => $pasien->age,
                'formatted_jk'     => $pasien->formatted_jk,
                'no_telp'          => $pasien->no_telp ?: '-',
                'alamat'           => $pasien->alamat ?: '-',
                'id_pendaftaran'   => $latestPendaftaran ? $latestPendaftaran->id_pendaftaran : null,
                'tgl_kunjungan'    => $latestPendaftaran
                    ? Carbon::parse($latestPendaftaran->tgl_daftar)->translatedFormat('d M Y, H:i')
                    : Carbon::now()->translatedFormat('d M Y, H:i'),
                'status_kunjungan' => $latestPendaftaran ? $latestPendaftaran->status_kunjungan : 'Menunggu',
            ],
            'pemeriksaan' => [
                'keluhan_utama'      => $latestAsuhan ? $latestAsuhan->keluhan_utama : '-',
                'diagnosa'           => $latestIntervensi->isNotEmpty() && $latestIntervensi->first()->diagnosa_awal
                    ? $latestIntervensi->first()->diagnosa_awal
                    : ($latestAsuhan ? $latestAsuhan->keluhan_utama : '-'),
                'tindakan_dilakukan' => $latestImplementasi ? $latestImplementasi->tindakan_dilakukan : '-',
                'resep_obat'         => $latestImplementasi ? $latestImplementasi->resep_obat : '',
            ],
            'obat_saran' => $obatSuggestions,
            'pembayaran' => $existingPembayaran ? [
                'id_pembayaran'     => $existingPembayaran->id_pembayaran,
                'biaya_tindakan'    => (float) $existingPembayaran->biaya_tindakan,
                'biaya_obat'        => (float) $existingPembayaran->biaya_obat,
                'total_bayar'       => (float) $existingPembayaran->total_bayar,
                'status_bayar'      => $existingPembayaran->status_bayar,
                'metode_pembayaran' => $existingPembayaran->metode_pembayaran,
                'uang_dibayar'      => (float) $existingPembayaran->uang_dibayar,
                'kembalian'         => (float) $existingPembayaran->kembalian,
                'catatan'           => $existingPembayaran->catatan,
                'rincian_obat'      => is_string($existingPembayaran->rincian_obat) 
                    ? json_decode($existingPembayaran->rincian_obat, true) 
                    : $existingPembayaran->rincian_obat,
                'rincian_tindakan'  => is_string($existingPembayaran->rincian_tindakan) 
                    ? json_decode($existingPembayaran->rincian_tindakan, true) 
                    : $existingPembayaran->rincian_tindakan,
            ] : null,
        ]);
    }

    /**
     * Menyimpan transaksi pembayaran ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pasien'          => 'required|exists:pasien,id_pasien',
            'id_pendaftaran'     => 'nullable|exists:pendaftaran,id_pendaftaran',
            'biaya_tindakan'     => 'nullable|numeric|min:0',
            'biaya_obat'         => 'nullable|numeric|min:0',
            'metode_pembayaran'  => 'required|string|max:50',
            'uang_dibayar'       => 'required|numeric|min:0',
            'kembalian'          => 'nullable|numeric',
            'catatan'            => 'nullable|string',
            'rincian_obat'       => 'nullable',
            'rincian_tindakan'   => 'nullable',
        ]);

        try {
            DB::beginTransaction();

            $idPasien = $request->id_pasien;
            $pasien = Pasien::findOrFail($idPasien);

            // 1. Dapatkan atau buat pendaftaran pasien
            $idPendaftaran = $request->id_pendaftaran;
            $pendaftaran = null;

            if ($idPendaftaran) {
                $pendaftaran = Pendaftaran::find($idPendaftaran);
            }

            if (!$pendaftaran) {
                // Cari pendaftaran hari ini atau buat baru
                $pendaftaran = Pendaftaran::where('id_pasien', $idPasien)
                    ->orderByDesc('id_pendaftaran')
                    ->first();

                if (!$pendaftaran) {
                    $antreanHariIni = Pendaftaran::whereDate('tgl_daftar', now()->toDateString())->count();
                    $pendaftaran = Pendaftaran::create([
                        'id_pasien'        => $idPasien,
                        'tgl_daftar'       => now(),
                        'no_antrean'       => $antreanHariIni + 1,
                        'status_kunjungan' => 'Selesai',
                    ]);
                }
            }

            // 2. Parse & siapkan rincian obat dan tindakan
            $rincianObat = $request->rincian_obat;
            if (is_string($rincianObat)) {
                $rincianObatDecoded = json_decode($rincianObat, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $rincianObat = $rincianObatDecoded;
                }
            }

            $rincianTindakan = $request->rincian_tindakan;
            if (is_string($rincianTindakan)) {
                $rincianTindakanDecoded = json_decode($rincianTindakan, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $rincianTindakan = $rincianTindakanDecoded;
                }
            }

            $biayaObat = (float) ($request->biaya_obat ?? 0);
            $biayaTindakan = (float) ($request->biaya_tindakan ?? 0);
            $totalBayar = $biayaObat + $biayaTindakan;
            $uangDibayar = (float) ($request->uang_dibayar ?? 0);
            $kembalian = max(0, $uangDibayar - $totalBayar);

            // 3. Simpan atau perbarui record Pembayaran
            $pembayaran = Pembayaran::where('id_pendaftaran', $pendaftaran->id_pendaftaran)->first();

            $dataPembayaran = [
                'id_pendaftaran'    => $pendaftaran->id_pendaftaran,
                'biaya_tindakan'    => $biayaTindakan,
                'biaya_obat'        => $biayaObat,
                'status_bayar'      => 'lunas',
                'metode_pembayaran' => strtolower($request->metode_pembayaran),
                'uang_dibayar'      => $uangDibayar,
                'kembalian'         => $kembalian,
                'catatan'           => $request->catatan,
                'rincian_obat'      => is_array($rincianObat) ? json_encode($rincianObat) : $rincianObat,
                'rincian_tindakan'  => is_array($rincianTindakan) ? json_encode($rincianTindakan) : $rincianTindakan,
            ];

            if ($pembayaran) {
                $pembayaran->update($dataPembayaran);
            } else {
                $pembayaran = Pembayaran::create($dataPembayaran);
            }

            // 4. Update status kunjungan pendaftaran menjadi 'Selesai'
            $pendaftaran->update(['status_kunjungan' => 'Selesai']);

            DB::commit();

            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => true,
                    'message' => 'Pembayaran untuk pasien ' . $pasien->nama_lengkap . ' sejumlah Rp ' . number_format($totalBayar, 0, ',', '.') . ' berhasil disimpan!',
                    'id_pembayaran' => $pembayaran->id_pembayaran,
                    'total_bayar'   => $totalBayar,
                    'uang_dibayar'  => $uangDibayar,
                    'kembalian'     => $kembalian,
                    'nama_pasien'   => $pasien->nama_lengkap,
                    'no_rm'         => $pasien->no_rm,
                    'tgl_bayar'     => Carbon::now()->translatedFormat('d M Y, H:i'),
                ]);
            }

            return redirect()->route('pembayaran', ['pasien_id' => $idPasien])
                ->with('success', 'Pembayaran untuk pasien ' . $pasien->nama_lengkap . ' berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan pembayaran: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Gagal menyimpan pembayaran: ' . $e->getMessage())->withInput();
        }
    }
}
