@extends('layouts.app')

@section('title', 'Mandalacare - Pembayaran')
@section('header_title', 'Pembayaran Pasien')
@section('header_subtitle', 'Kelola pembayaran obat, tindakan medis, dan pantau pendapatan klinik.')

@section('content')
{{-- ================= TOAST NOTIFICATION ================= --}}
<div id="toastNotification" class="fixed top-6 left-1/2 -translate-x-1/2 z-50 w-[92%] max-w-lg pointer-events-none transition-all duration-300 transform -translate-y-16 opacity-0 hidden">
    <div id="toastCard" class="pointer-events-auto bg-white border-2 border-primary/40 rounded-2xl shadow-2xl p-4 md:p-5 flex items-start gap-4 backdrop-blur-md bg-white/95 relative overflow-hidden">
        <div id="toastAccentBar" class="absolute left-0 top-0 bottom-0 w-2 bg-primary"></div>
        <div id="toastIconContainer" class="w-11 h-11 rounded-xl bg-[#E5F5F0] text-primary flex items-center justify-center flex-shrink-0 shadow-sm">
            <span id="toastIcon" class="material-symbols-outlined text-2xl font-bold">check_circle</span>
        </div>
        <div class="flex-1 min-w-0 pr-2">
            <h4 class="text-base font-bold text-on-surface" id="toastTitle">Pembayaran Tersimpan!</h4>
            <p class="text-sm text-on-surface-variant mt-1" id="toastMessage">Data pembayaran pasien berhasil dicatat.</p>
        </div>
        <button type="button" onclick="hideToast()" class="text-on-surface-variant hover:text-on-surface p-1 rounded-lg flex-shrink-0">
            <span class="material-symbols-outlined text-lg">close</span>
        </button>
    </div>
</div>

<div class="min-h-screen bg-slate-50 p-4 sm:p-6 md:p-8 lg:p-10 w-full max-w-7xl mx-auto flex-1 flex flex-col gap-6">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">
                Pembayaran & Kasir
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Pilih pasien, catat rincian obat serta tindakan medis, dan selesaikan transaksi pembayaran.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('rekam-medis') }}" class="bg-white border border-slate-200 hover:border-emerald-600 text-slate-700 px-4 py-2.5 rounded-xl text-sm font-semibold transition-colors inline-flex items-center gap-2 shadow-sm">
                <span class="material-symbols-outlined text-emerald-600 text-[20px]">medical_services</span>
                <span>Rekam Medis</span>
            </a>
            <a href="{{ route('evaluasi') }}" class="bg-white border border-slate-200 hover:border-emerald-600 text-slate-700 px-4 py-2.5 rounded-xl text-sm font-semibold transition-colors inline-flex items-center gap-2 shadow-sm">
                <span class="material-symbols-outlined text-emerald-600 text-[20px]">assignment_turned_in</span>
                <span>Evaluasi</span>
            </a>
        </div>
    </div>


    {{-- ================= RINGKASAN PENDAPATAN ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

        {{-- Hari Ini --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Pendapatan Hari Ini
                    </p>
                    <h2 id="summaryHariIni" class="text-2xl font-bold text-slate-900 mt-2">
                        Rp {{ number_format($pendapatanHariIni ?? 0, 0, ',', '.') }}
                    </h2>
                    <p class="text-xs text-slate-400 mt-1 flex items-center gap-1">
                        <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span id="summaryTrxHariIni">{{ $transaksiHariIni ?? 0 }} transaksi berhasil hari ini</span>
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-emerald-600 text-[26px]">
                        payments
                    </span>
                </div>
            </div>
        </div>

        {{-- Minggu Ini --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Pendapatan Minggu Ini
                    </p>
                    <h2 class="text-2xl font-bold text-slate-900 mt-2">
                        Rp {{ number_format($pendapatanMingguIni ?? 0, 0, ',', '.') }}
                    </h2>
                    <p class="text-xs text-slate-400 mt-1 flex items-center gap-1">
                        <span class="inline-block w-2 h-2 rounded-full bg-blue-500"></span>
                        <span>{{ $transaksiMingguIni ?? 0 }} transaksi minggu ini</span>
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-blue-600 text-[26px]">
                        trending_up
                    </span>
                </div>
            </div>
        </div>

        {{-- Bulan Ini --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Pendapatan Bulan Ini
                    </p>
                    <h2 class="text-2xl font-bold text-slate-900 mt-2">
                        Rp {{ number_format($pendapatanBulanIni ?? 0, 0, ',', '.') }}
                    </h2>
                    <p class="text-xs text-slate-400 mt-1 flex items-center gap-1">
                        <span class="inline-block w-2 h-2 rounded-full bg-purple-500"></span>
                        <span>{{ $transaksiBulanIni ?? 0 }} transaksi bulan ini</span>
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-purple-600 text-[26px]">
                        account_balance_wallet
                    </span>
                </div>
            </div>
        </div>

    </div>


    {{-- ================= MAIN CONTENT (PILIH PASIEN & RINCIAN) ================= --}}
    <form id="formPembayaran" onsubmit="submitPembayaran(event)">
        @csrf
        <input type="hidden" name="id_pasien" id="hidden_id_pasien" value="{{ $selectedPasien ? $selectedPasien->id_pasien : '' }}">
        <input type="hidden" name="id_pendaftaran" id="hidden_id_pendaftaran" value="{{ $latestPendaftaran ? $latestPendaftaran->id_pendaftaran : '' }}">
        <input type="hidden" name="biaya_obat" id="hidden_biaya_obat" value="{{ $existingPembayaran ? $existingPembayaran->biaya_obat : 0 }}">
        <input type="hidden" name="biaya_tindakan" id="hidden_biaya_tindakan" value="{{ $existingPembayaran ? $existingPembayaran->biaya_tindakan : 0 }}">
        <input type="hidden" name="rincian_obat" id="hidden_rincian_obat" value="">
        <input type="hidden" name="rincian_tindakan" id="hidden_rincian_tindakan" value="">
        <input type="hidden" id="hidden_status_bayar" value="{{ $existingPembayaran ? $existingPembayaran->status_bayar : '' }}">

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start">

            {{-- LEFT COLUMN (PILIH PASIEN + DETAIL PEMERIKSAAN + TABEL OBAT) --}}
            <div class="xl:col-span-2 space-y-6">

                {{-- 1. PILIH PASIEN --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm font-bold">1</span>
                            <h2 class="text-lg font-bold text-slate-900">
                                Pilih Pasien
                            </h2>
                        </div>
                        <span class="text-xs text-slate-500 bg-slate-100 px-3 py-1 rounded-full font-medium">
                            {{ $daftarPasien->count() }} Pasien Terdaftar
                        </span>
                    </div>

                    {{-- Search Dropdown Box --}}
                    <div class="relative mb-4">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                            search
                        </span>

                        <input
                            id="pasienSearchInput"
                            type="text"
                            placeholder="Cari nama pasien, NIK, atau nomor rekam medis..."
                            value="{{ $selectedPasien ? $selectedPasien->nama_lengkap : '' }}"
                            autocomplete="off"
                            onclick="showDropdown(event)"
                            onfocus="showDropdown(event)"
                            oninput="filterDropdown(this.value)"
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl py-3 pl-11 pr-10 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition-all">

                        <button
                            type="button"
                            onclick="clearSearch(event)"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                            <span class="material-symbols-outlined text-sm">close</span>
                        </button>

                        <div id="pasienDropdownList" class="absolute left-0 right-0 top-full mt-1.5 bg-white border border-slate-200 rounded-xl shadow-xl z-30 max-h-64 overflow-y-auto hidden">
                            @forelse ($daftarPasien as $p)
                                <div
                                    class="pasien-dropdown-item flex items-center justify-between p-3.5 hover:bg-slate-50 cursor-pointer border-b border-slate-100 transition-colors"
                                    data-id="{{ $p->id_pasien }}"
                                    data-nama="{{ $p->nama_lengkap }}"
                                    data-nik="{{ $p->nik }}"
                                    onclick="selectPasien({{ $p->id_pasien }})">

                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold shrink-0">
                                            {{ $p->initials }}
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-semibold text-slate-800">{{ $p->nama_lengkap }}</h4>
                                            <p class="text-xs text-slate-500">
                                                NIK: {{ $p->nik }} &bull; <span class="text-emerald-700 font-medium">{{ $p->no_rm }}</span>
                                                @if($p->pendaftaranTerbaru)
                                                    &bull; <span class="text-slate-400">{{ \Carbon\Carbon::parse($p->pendaftaranTerbaru->tgl_daftar)->translatedFormat('d M Y') }}</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        @if($p->pendaftaranTerbaru && $p->pendaftaranTerbaru->pembayaran && in_array(strtolower($p->pendaftaranTerbaru->pembayaran->status_bayar), ['lunas', 'selesai']))
                                            <span class="text-[11px] bg-emerald-100 text-emerald-800 font-semibold px-2 py-0.5 rounded-full">Lunas</span>
                                        @else
                                            <span class="text-[11px] bg-amber-100 text-amber-800 font-semibold px-2 py-0.5 rounded-full">Belum Bayar</span>
                                        @endif
                                        <span class="material-symbols-outlined text-emerald-600 text-sm">arrow_forward</span>
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-center text-xs text-slate-500">
                                    Belum ada data pasien. <a href="{{ route('pendaftaran') }}" class="text-emerald-600 underline font-semibold">Daftarkan pasien</a>.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Selected Patient Display Card --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <div class="flex items-center gap-3.5">
                            <div id="cardAvatar" class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-base font-bold shrink-0">
                                {{ $selectedPasien ? $selectedPasien->initials : 'PS' }}
                            </div>

                            <div>
                                <h4 id="cardNama" class="font-bold text-slate-900 text-base">
                                    {{ $selectedPasien ? $selectedPasien->nama_lengkap : 'Belum Memilih Pasien' }}
                                </h4>
                                <p id="cardSub" class="text-xs text-slate-500 mt-0.5">
                                    @if($selectedPasien)
                                        NIK: {{ $selectedPasien->nik }} &bull; <span class="text-emerald-700 font-semibold">{{ $selectedPasien->no_rm }}</span>
                                        &bull; Umur: {{ $selectedPasien->age }}
                                        &bull; JK: {{ $selectedPasien->formatted_jk }}
                                    @else
                                        Silakan pilih pasien terlebih dahulu untuk memproses rincian obat dan pembayaran.
                                    @endif
                                </p>
                            </div>
                        </div>

                        <button
                            type="button"
                            id="btnPilihPasien"
                            onclick="toggleDropdown(event)"
                            class="bg-white border border-slate-200 hover:border-emerald-500 text-slate-700 text-xs font-semibold px-3.5 py-2.5 rounded-xl transition-colors flex items-center justify-center gap-1.5 shadow-sm">
                            <span class="material-symbols-outlined text-[18px]">swap_horiz</span>
                            <span>Pilih / Ganti Pasien</span>
                        </button>
                    </div>
                </div>


                {{-- 2. INFORMASI PEMERIKSAAN & RESEP DOKTER --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm font-bold">2</span>
                            <h2 class="text-lg font-bold text-slate-900">
                                Hasil Pemeriksaan & Rekam Medis
                            </h2>
                        </div>
                        <span id="badgeStatusKunjungan" class="text-xs font-semibold px-2.5 py-1 rounded-full {{ ($selectedPasien && $latestPendaftaran) ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-500' }}">
                            {{ ($selectedPasien && $latestPendaftaran) ? $latestPendaftaran->status_kunjungan : 'Belum Ada Kunjungan' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/80">
                            <p class="text-xs font-semibold text-slate-500 mb-1">Keluhan Pasien</p>
                            <p id="summaryKeluhan" class="text-sm font-medium text-slate-800">
                                {{ $latestAsuhan ? $latestAsuhan->keluhan_utama : 'Pilih pasien untuk melihat keluhan' }}
                            </p>
                        </div>

                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/80">
                            <p class="text-xs font-semibold text-slate-500 mb-1">Diagnosa</p>
                            <p id="summaryDiagnosa" class="text-sm font-medium text-slate-800 whitespace-pre-line">
                                {{ ($latestIntervensi->isNotEmpty() && $latestIntervensi->first()->diagnosa_awal) ? $latestIntervensi->first()->diagnosa_awal : ($latestAsuhan ? $latestAsuhan->keluhan_utama : '-') }}
                            </p>
                        </div>
                    </div>

                    {{-- Resep Obat & Tindakan dari Rekam Medis --}}
                    <div class="p-4 rounded-xl bg-emerald-50/70 border border-emerald-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-emerald-700 text-2xl mt-0.5">medication</span>
                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-800">Resep Obat dari Rekam Medis</h4>
                                <p id="summaryResepObat" class="text-sm text-emerald-950 mt-0.5">
                                    {{ ($latestImplementasi && $latestImplementasi->resep_obat) ? $latestImplementasi->resep_obat : 'Belum ada catatan resep obat di rekam medis.' }}
                                </p>
                            </div>
                        </div>

                        <button
                            type="button"
                            id="btnImporResep"
                            onclick="imporResepKeTabel()"
                            class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-3.5 py-2 rounded-xl transition-all shrink-0 inline-flex items-center justify-center gap-1.5 shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">add_circle</span>
                            <span>Salin ke Tabel Obat</span>
                        </button>
                    </div>
                </div>


                {{-- 3. RINCIAN OBAT & TINDAKAN (TABEL INTERAKTIF & PERHITUNGAN) --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm font-bold">3</span>
                            <div>
                                <h2 class="text-lg font-bold text-slate-900">
                                    Rincian Obat & Biaya Layanan
                                </h2>
                                <p class="text-xs text-slate-500">Catat nama obat, jumlah, dan harga satuan. Total dihitung otomatis.</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                onclick="tambahBarisObat('Obat')"
                                class="bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 text-xs font-bold px-3 py-2 rounded-xl transition-all inline-flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">add</span>
                                <span>Obat</span>
                            </button>

                            <button
                                type="button"
                                onclick="tambahBarisObat('Tindakan')"
                                class="bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 text-xs font-bold px-3 py-2 rounded-xl transition-all inline-flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">add</span>
                                <span>Tindakan/Jasa</span>
                            </button>
                        </div>
                    </div>

                    {{-- Datalist Autocomplete Obat & Tindakan Populer --}}
                    <datalist id="listObatPopuler">
                        <option value="Paracetamol 500mg (Strip)">
                        <option value="Amoxicillin 500mg">
                        <option value="Ibuprofen 400mg">
                        <option value="Antasida Doen (Tablet Kunyah)">
                        <option value="Ambroxol Syrup 30mg">
                        <option value="Cetirizine 10mg">
                        <option value="Asam Mefenamat 500mg">
                        <option value="Cefixime 100mg">
                        <option value="Omeprazole 20mg">
                        <option value="Dexamethasone 0.5mg">
                        <option value="Vitamin C 500mg">
                        <option value="Multivitamin & Mineral">
                        <option value="Oralit Sachet">
                        <option value="Konsultasi & Pemeriksaan Dokter">
                        <option value="Tindakan Rawat Luka Ringan">
                        <option value="Injeksi & Obat Injeksi">
                        <option value="Cek Gula Darah Sewaktu">
                        <option value="Cek Kolesterol">
                        <option value="Cek Asam Urat">
                        <option value="Nebulizer">
                    </datalist>

                    {{-- Tabel Item Obat & Tindakan --}}
                    <div class="overflow-x-auto rounded-xl border border-slate-200 mb-5">
                        <table class="w-full text-left border-collapse" id="tabelObat">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-3.5 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">
                                        Kategori
                                    </th>
                                    <th class="px-3.5 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider min-w-[200px]">
                                        Nama Obat / Tindakan
                                    </th>
                                    <th class="px-3.5 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider text-center w-24">
                                        Qty
                                    </th>
                                    <th class="px-3.5 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider text-right min-w-[140px]">
                                        Harga Satuan (Rp)
                                    </th>
                                    <th class="px-3.5 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider text-right min-w-[140px]">
                                        Subtotal (Rp)
                                    </th>
                                    <th class="px-3.5 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider text-center w-12">
                                        Hapus
                                    </th>
                                </tr>
                            </thead>

                            <tbody id="bodyTabelObat" class="divide-y divide-slate-100">
                                <tr id="emptyRow">
                                    <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <span class="material-symbols-outlined text-slate-300 text-3xl mb-1">medication_liquid</span>
                                            <p>Belum ada rincian obat atau tindakan.</p>
                                            <p class="text-xs text-slate-400 mt-0.5">Klik <b>+ Obat</b> atau <b>+ Tindakan</b> di atas untuk menambahkan.</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Breakdown Perhitungan Biaya --}}
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-200 space-y-3">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-600 flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                                Total Biaya Tindakan / Layanan:
                            </span>
                            <span id="displayBiayaTindakan" class="font-semibold text-slate-800">
                                Rp 0
                            </span>
                        </div>

                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-600 flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                Total Biaya Obat-obatan:
                            </span>
                            <span id="displayBiayaObat" class="font-semibold text-slate-800">
                                Rp 0
                            </span>
                        </div>

                        <div class="flex justify-between items-center pt-3 border-t border-slate-200">
                            <span class="text-base font-bold text-slate-900">
                                Grand Total Pembayaran:
                            </span>
                            <span id="displayTotalBayar" class="text-xl sm:text-2xl font-extrabold text-emerald-600">
                                Rp 0
                            </span>
                        </div>
                    </div>
                </div>

            </div>


            {{-- RIGHT COLUMN (FORM PEMBAYARAN KASIR) --}}
            <div class="xl:col-span-1">
                <div class="sticky top-24 space-y-5">

                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 sm:p-6">
                        <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                            <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                                <span class="material-symbols-outlined text-emerald-600">point_of_sale</span>
                                Transaksi Kasir
                            </h3>
                            <span class="text-xs bg-emerald-100 text-emerald-800 font-semibold px-2 py-0.5 rounded-full">
                                Mandalacare
                            </span>
                        </div>

                        <div class="space-y-4">

                            {{-- Total Display Box --}}
                            <div class="rounded-2xl bg-gradient-to-br from-emerald-600 to-emerald-700 p-4 text-white shadow-md">
                                <p class="text-xs text-emerald-100 font-medium uppercase tracking-wider">
                                    Total yang Harus Dibayar
                                </p>
                                <h3 id="cardTotalBayar" class="text-2xl sm:text-3xl font-extrabold mt-1 tracking-tight">
                                    Rp 0
                                </h3>
                                <p class="text-[11px] text-emerald-100/90 mt-1">
                                    Sudah termasuk rincian obat dan tindakan medis.
                                </p>
                            </div>

                            {{-- Metode Pembayaran --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Metode Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <select
                                    name="metode_pembayaran"
                                    id="inputMetode"
                                    required
                                    class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                                    <option value="tunai" selected>Tunai (Cash)</option>
                                    <option value="qris">QRIS</option>
                                    <option value="transfer">Transfer Bank</option>
                                    <option value="debit">Kartu Debit</option>
                                </select>
                            </div>

                            {{-- Uang Dibayar --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Uang Diterima / Dibayar <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">
                                        Rp
                                    </span>
                                    <input
                                        type="number"
                                        id="inputUangDibayar"
                                        name="uang_dibayar"
                                        min="0"
                                        placeholder="0"
                                        value="0"
                                        required
                                        oninput="hitungKembalian()"
                                        class="w-full rounded-xl border border-slate-300 bg-white pl-11 pr-3.5 py-2.5 text-base font-bold text-slate-900 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                                </div>

                                {{-- Quick Cash Buttons --}}
                                <div class="grid grid-cols-4 gap-1.5 mt-2">
                                    <button type="button" onclick="setUangPas()" class="px-2 py-1.5 bg-slate-100 hover:bg-emerald-100 hover:text-emerald-800 text-slate-700 text-xs font-semibold rounded-lg transition-colors text-center">
                                        Uang Pas
                                    </button>
                                    <button type="button" onclick="setQuickCash(50000)" class="px-2 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors text-center">
                                        50.000
                                    </button>
                                    <button type="button" onclick="setQuickCash(100000)" class="px-2 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors text-center">
                                        100.000
                                    </button>
                                    <button type="button" onclick="setQuickCash(200000)" class="px-2 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors text-center">
                                        200.000
                                    </button>
                                </div>
                            </div>

                            {{-- Kembalian Box --}}
                            <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">
                                        Kembalian:
                                    </span>
                                    <span id="badgeKembalianStatus" class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800">
                                        Pas
                                    </span>
                                </div>
                                <h4 id="displayKembalian" class="text-xl font-extrabold text-slate-800 mt-1">
                                    Rp 0
                                </h4>
                                <input type="hidden" name="kembalian" id="hidden_kembalian" value="0">
                            </div>

                            {{-- Catatan --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Catatan Tambahan
                                </label>
                                <textarea
                                    id="inputCatatan"
                                    name="catatan"
                                    rows="2"
                                    placeholder="Catatan pembayaran / diskon jika ada..."
                                    class="w-full rounded-xl border border-slate-300 bg-white p-3 text-xs text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 resize-none"></textarea>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="pt-2 space-y-2">
                                <button
                                    type="submit"
                                    id="btnSimpanPembayaran"
                                    class="w-full flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white py-3 px-4 rounded-xl font-bold text-sm shadow-md transition-all">
                                    <span class="material-symbols-outlined text-[20px]">payments</span>
                                    <span>Simpan & Selesaikan Pembayaran</span>
                                </button>

                                <button
                                    type="button"
                                    id="btnCetakNota"
                                    onclick="bukaModalNota()"
                                    class="w-full flex items-center justify-center gap-2 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 py-2.5 px-4 rounded-xl font-semibold text-xs transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">receipt</span>
                                    <span>Lihat / Cetak Nota Pembayaran</span>
                                </button>
                            </div>

                        </div>
                    </div>

                    {{-- Informasi Singkat --}}
                    <div class="bg-blue-50 border border-blue-200/70 rounded-2xl p-4 text-blue-900">
                        <div class="flex gap-3">
                            <span class="material-symbols-outlined text-blue-600 shrink-0 text-xl">info</span>
                            <div class="text-xs text-blue-800 leading-relaxed">
                                <p class="font-bold text-blue-900 mb-0.5">Sistem Pembayaran Terintegrasi</p>
                                Saat pembayaran disimpan, status kunjungan pasien otomatis diperbarui menjadi <b>Selesai</b> dan tercatat di riwayat klinik.
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </form>


    {{-- ================= RIWAYAT PEMBAYARAN ================= --}}
    <div class="mt-4">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span class="material-symbols-outlined text-emerald-600">receipt_long</span>
                        Riwayat Transaksi Pembayaran
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Daftar transaksi pembayaran yang telah berhasil disimpan.
                    </p>
                </div>

                <div class="relative w-full md:w-72">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">
                        search
                    </span>
                    <input
                        type="text"
                        id="searchRiwayatInput"
                        oninput="filterRiwayat(this.value)"
                        placeholder="Cari nama pasien / no. transaksi..."
                        class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-300 bg-slate-50 text-xs focus:outline-none focus:bg-white focus:border-emerald-500">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" id="tabelRiwayat">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">No. Trx</th>
                            <th class="px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Tanggal & Waktu</th>
                            <th class="px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Pasien</th>
                            <th class="px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Rincian</th>
                            <th class="px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Metode</th>
                            <th class="px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider text-right">Total Bayar</th>
                            <th class="px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider text-center">Status</th>
                            <th class="px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100" id="bodyTabelRiwayat">
                        @forelse ($riwayatPembayaran as $bayar)
                            @php
                                $pasienBayar = $bayar->pendaftaran ? $bayar->pendaftaran->pasien : null;
                                $rincianObatList = is_string($bayar->rincian_obat) ? json_decode($bayar->rincian_obat, true) : $bayar->rincian_obat;
                                $itemCount = is_array($rincianObatList) ? count($rincianObatList) : 0;
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors riwayat-row" data-search="{{ $pasienBayar ? strtolower($pasienBayar->nama_lengkap . ' ' . $pasienBayar->nik . ' ' . $pasienBayar->no_rm) : '' }} trx-{{ $bayar->id_pembayaran }}">
                                <td class="px-4 py-3 text-xs font-bold text-slate-800">
                                    #TRX-{{ str_pad($bayar->id_pembayaran, 4, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-4 py-3 text-xs text-slate-500">
                                    {{ $bayar->formatted_tgl }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($pasienBayar)
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-[10px] font-bold shrink-0">
                                                {{ $pasienBayar->initials }}
                                            </div>
                                            <div>
                                                <p class="text-xs font-bold text-slate-800">{{ $pasienBayar->nama_lengkap }}</p>
                                                <p class="text-[11px] text-slate-400">{{ $pasienBayar->no_rm }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400">Pasien Tidak Ditemukan</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs text-slate-600">
                                    @if($itemCount > 0)
                                        <span class="inline-flex items-center gap-1 font-medium text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md text-[11px]">
                                            <span class="material-symbols-outlined text-[14px]">pill</span>
                                            {{ $itemCount }} item obat/tindakan
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-xs">Biaya Obat: Rp {{ number_format($bayar->biaya_obat, 0, ',', '.') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs uppercase font-semibold text-slate-700">
                                    {{ $bayar->metode_pembayaran ?: 'Tunai' }}
                                </td>
                                <td class="px-4 py-3 text-xs font-bold text-emerald-700 text-right">
                                    {{ $bayar->formatted_total_bayar }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        Lunas
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button
                                        type="button"
                                        onclick="cetakRiwayatNota({{ json_encode([
                                            'id' => $bayar->id_pembayaran,
                                            'tgl' => $bayar->formatted_tgl,
                                            'nama' => $pasienBayar ? $pasienBayar->nama_lengkap : 'Pasien',
                                            'nik' => $pasienBayar ? $pasienBayar->nik : '-',
                                            'no_rm' => $pasienBayar ? $pasienBayar->no_rm : '-',
                                            'metode' => $bayar->metode_pembayaran ?: 'Tunai',
                                            'total' => $bayar->total_bayar,
                                            'dibayar' => $bayar->uang_dibayar,
                                            'kembalian' => $bayar->kembalian,
                                            'catatan' => $bayar->catatan,
                                            'items' => $rincianObatList ?: [],
                                            'biaya_tindakan' => $bayar->biaya_tindakan,
                                            'biaya_obat' => $bayar->biaya_obat,
                                        ]) }})"
                                        class="p-1.5 text-slate-500 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors"
                                        title="Cetak Nota">
                                        <span class="material-symbols-outlined text-[18px]">receipt</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyRiwayatRow">
                                <td colspan="8" class="p-10 text-center text-sm text-slate-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="material-symbols-outlined text-slate-300 text-4xl mb-2">receipt_long</span>
                                        <h4 class="font-semibold text-slate-700">Belum Ada Riwayat Pembayaran</h4>
                                        <p class="text-xs text-slate-400 mt-1">Transaksi yang telah Anda simpan akan tampil di sini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>


{{-- ================= MODAL CETAK NOTA / STRUK PEMBAYARAN ================= --}}
<div id="modalNota" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-3 sm:p-4 hidden">
    <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-lg overflow-hidden flex flex-col max-h-[92vh]">
        
        {{-- Modal Header --}}
        <div class="p-3.5 sm:p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between gap-2">
            <div class="flex items-center gap-2 min-w-0">
                <span class="material-symbols-outlined text-emerald-600 shrink-0">receipt_long</span>
                <div class="truncate">
                    <h3 class="font-bold text-slate-900 text-sm sm:text-base leading-tight">Nota Pembayaran</h3>
                    <p class="text-[11px] text-slate-500 truncate">Format Kertas Struk / Nota Thermal</p>
                </div>
            </div>
            
            {{-- Ukuran Kertas Selector --}}
            <div class="flex items-center gap-2 shrink-0">
                <div class="flex items-center bg-slate-200/90 p-0.5 rounded-xl text-xs">
                    <button type="button" id="btnSize80" onclick="setUkuranNota('80mm')" 
                        class="px-2.5 py-1 rounded-lg font-bold text-xs transition-all bg-white text-emerald-700 shadow-sm flex items-center gap-1">
                        <span>80mm</span>
                        <span class="text-[10px] opacity-75 font-normal hidden sm:inline">(Standar)</span>
                    </button>
                    <button type="button" id="btnSize58" onclick="setUkuranNota('58mm')" 
                        class="px-2.5 py-1 rounded-lg font-semibold text-xs transition-all text-slate-600 hover:text-slate-900 flex items-center gap-1">
                        <span>58mm</span>
                        <span class="text-[10px] opacity-75 font-normal hidden sm:inline">(Mini)</span>
                    </button>
                </div>

                <button type="button" onclick="tutupModalNota()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-200/60 transition-colors">
                    <span class="material-symbols-outlined text-xl">close</span>
                </button>
            </div>
        </div>

        {{-- Printable Receipt Content in Realistic Paper Wrapper --}}
        <div class="p-4 sm:p-6 bg-slate-100/90 overflow-y-auto flex-1 flex justify-center items-start">
            <div id="notaPaperWrapper" class="bg-white border border-slate-300/90 shadow-md rounded-sm p-4 sm:p-5 w-full max-w-[340px] text-slate-900 transition-all duration-300">
                <div id="printArea" class="bg-white text-slate-900 font-sans">
                    
                    {{-- Header Nota / Kop Klinik --}}
                    <div class="text-center pb-2">
                        <h2 class="clinic-title text-base sm:text-lg font-extrabold text-emerald-800 tracking-tight leading-tight">KLINIK MANDALACARE</h2>
                        <p class="clinic-sub text-[11px] text-slate-600 font-medium mt-0.5">Layanan Kesehatan & Rawat Jalan Profesional</p>
                        <p class="clinic-sub text-[10px] text-slate-500 mt-0.5 leading-snug">Jl. Menara, Gg. Puter, RT 10/RW 06, Kedungwringin, Patikraja</p>
                        <p class="clinic-sub text-[10px] text-slate-700 font-semibold mt-0.5">Telp/WA: +62 881-8080-805</p>
                    </div>

                    <div class="dashed-sep border-t border-dashed border-slate-400 my-2"></div>

                    {{-- Metadata Transaksi --}}
                    <div class="space-y-1 text-xs py-0.5">
                        <div class="row-meta flex justify-between items-start">
                            <span class="label text-slate-500">No. Transaksi:</span>
                            <span id="notaNoTrx" class="value font-bold text-slate-900">#TRX-0001</span>
                        </div>
                        <div class="row-meta flex justify-between items-start">
                            <span class="label text-slate-500">Tanggal:</span>
                            <span id="notaTanggal" class="value font-medium text-slate-800">-</span>
                        </div>
                        <div class="row-meta flex justify-between items-start">
                            <span class="label text-slate-500">Pasien / RM:</span>
                            <span id="notaPasien" class="value font-bold text-slate-900 text-right">-</span>
                        </div>
                        <div class="row-meta flex justify-between items-start">
                            <span class="label text-slate-500">Metode Bayar:</span>
                            <span id="notaMetode" class="value font-bold text-slate-900 uppercase">TUNAI</span>
                        </div>
                    </div>

                    <div class="dashed-sep border-t border-dashed border-slate-400 my-2"></div>

                    {{-- Table of items in receipt --}}
                    <div class="py-1">
                        <table class="w-full text-xs">
                            <thead>
                                <tr class="text-slate-600 border-b border-dashed border-slate-400">
                                    <th class="text-left pb-1 font-semibold">Item Obat / Layanan</th>
                                    <th class="text-center pb-1 font-semibold w-10">Qty</th>
                                    <th class="text-right pb-1 font-semibold">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="notaItemBody" class="divide-y divide-slate-100">
                                {{-- Items injected dynamically --}}
                            </tbody>
                        </table>
                    </div>

                    <div class="dashed-sep border-t border-dashed border-slate-400 my-2"></div>

                    {{-- Totals --}}
                    <div class="space-y-1 text-xs py-1 total-box">
                        <div class="total-row flex justify-between text-slate-600">
                            <span>Biaya Obat:</span>
                            <span id="notaBiayaObat" class="val font-semibold text-slate-900">Rp 0</span>
                        </div>
                        <div class="total-row flex justify-between text-slate-600">
                            <span>Biaya Tindakan:</span>
                            <span id="notaBiayaTindakan" class="val font-semibold text-slate-900">Rp 0</span>
                        </div>
                        <div class="grand-total flex justify-between items-center text-sm font-bold text-slate-900 py-1.5 border-y border-dashed border-slate-800 my-1">
                            <span>TOTAL BAYAR:</span>
                            <span id="notaTotalBayar" class="text-emerald-700 font-extrabold text-base">Rp 0</span>
                        </div>
                        <div class="total-row flex justify-between text-slate-600">
                            <span>Uang Diterima:</span>
                            <span id="notaUangDibayar" class="val font-semibold text-slate-900">Rp 0</span>
                        </div>
                        <div class="total-row flex justify-between text-slate-600">
                            <span>Kembalian:</span>
                            <span id="notaKembalian" class="val font-bold text-emerald-600">Rp 0</span>
                        </div>
                    </div>

                    <div class="dashed-sep border-t border-dashed border-slate-400 my-2"></div>

                    {{-- Footer --}}
                    <div class="footer text-center text-[10px] text-slate-500 pt-1 leading-relaxed">
                        <p class="lunas-badge font-bold text-emerald-800 text-[11px] mb-0.5">*** LUNAS ***</p>
                        <p>Terima kasih atas kunjungan Anda.</p>
                        <p>Semoga lekas sembuh dan sehat selalu!</p>
                        <p class="system-tag text-[9px] text-slate-400 mt-1.5 tracking-wider font-mono">SIM-KLINIK MANDALACARE</p>
                    </div>

                </div>
            </div>
        </div>

        {{-- Modal Actions & Print Hint --}}
        <div class="p-3 sm:p-4 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-1.5 text-[11px] text-slate-500">
                <span class="material-symbols-outlined text-amber-500 text-base shrink-0">tips_and_updates</span>
                <span>Pilih <b>Margin: None</b> pada dialog print browser untuk hasil pas.</span>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                <button type="button" onclick="tutupModalNota()" class="flex-1 sm:flex-none px-4 py-2 rounded-xl border border-slate-300 text-slate-700 text-xs font-semibold hover:bg-slate-100 transition-colors">
                    Tutup
                </button>
                <button type="button" onclick="printNota()" class="flex-1 sm:flex-none px-4 py-2 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 transition-all flex items-center justify-center gap-1.5 shadow-sm active:scale-95">
                    <span class="material-symbols-outlined text-[17px]">print</span>
                    <span>Cetak Nota (<span id="lblBtnUkuran">80mm</span>)</span>
                </button>
            </div>
        </div>

    </div>
</div>


{{-- ================= JAVASCRIPT LOGIC ================= --}}
<script>
const ROUTE_PEMBAYARAN_PASIEN = "{{ route('pembayaran.pasien.detail', ['id' => '__ID__']) }}";
let daftarItems = [];

// ================= DROPDOWN PASIEN LOGIC =================
function showDropdown(e) {
    if (e) e.stopPropagation();
    const list = document.getElementById('pasienDropdownList');
    if (list) list.classList.remove('hidden');
}

function toggleDropdown(e) {
    if (e) e.stopPropagation();
    const list = document.getElementById('pasienDropdownList');
    if (list) {
        if (list.classList.contains('hidden')) {
            list.classList.remove('hidden');
            const input = document.getElementById('pasienSearchInput');
            if (input) { input.focus(); input.select(); }
        } else {
            list.classList.add('hidden');
        }
    }
}

document.addEventListener('click', function(e) {
    const input = document.getElementById('pasienSearchInput');
    const dropdown = document.getElementById('pasienDropdownList');
    const btn = document.getElementById('btnPilihPasien');
    if (dropdown && !dropdown.classList.contains('hidden')) {
        const isInsideInput = input && input.contains(e.target);
        const isInsideDropdown = dropdown.contains(e.target);
        const isInsideBtn = btn && btn.contains(e.target);
        if (!isInsideInput && !isInsideDropdown && !isInsideBtn) {
            dropdown.classList.add('hidden');
        }
    }
});

function filterDropdown(val) {
    const q = val.toLowerCase();
    showDropdown();
    document.querySelectorAll('.pasien-dropdown-item').forEach(item => {
        const ok = (item.dataset.nama || '').toLowerCase().includes(q) || (item.dataset.nik || '').toLowerCase().includes(q);
        item.style.display = ok ? '' : 'none';
    });
}

function clearSearch(e) {
    if (e) e.stopPropagation();
    const input = document.getElementById('pasienSearchInput');
    if (input) { input.value = ''; input.focus(); }
    filterDropdown('');
    showDropdown();
}

// ================= PILIH PASIEN VIA AJAX =================
function selectPasien(id) {
    document.getElementById('pasienDropdownList').classList.add('hidden');
    
    fetch(ROUTE_PEMBAYARAN_PASIEN.replace('__ID__', id), {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) {
            showToast('Pasien Tidak Ditemukan', data.message || 'Gagal mengambil data pasien.', 'error');
            return;
        }

        const p = data.pasien;
        const pem = data.pemeriksaan;
        const bayar = data.pembayaran;

        // Set status pembayaran untuk guard "sudah lunas"
        const statusBayar = (bayar && bayar.status_bayar) ? String(bayar.status_bayar).toLowerCase() : '';
        const sudahLunas = ['lunas', 'selesai'].includes(statusBayar);
        document.getElementById('hidden_status_bayar').value = sudahLunas ? 'lunas' : '';
        setStatusLunasUI(sudahLunas);

        // 1. Update Card Info Pasien
        document.getElementById('cardAvatar').textContent  = p.initials;
        document.getElementById('cardNama').textContent    = p.nama_lengkap;
        document.getElementById('cardSub').innerHTML     = `NIK: ${p.nik} &bull; <span class="text-emerald-700 font-semibold">${p.no_rm}</span> &bull; Umur: ${p.age} &bull; JK: ${p.formatted_jk}`;
        document.getElementById('pasienSearchInput').value = p.nama_lengkap;
        document.getElementById('badgeStatusKunjungan').textContent = p.status_kunjungan || 'Menunggu';

        // 2. Update Ringkasan Pemeriksaan & Resep
        document.getElementById('summaryKeluhan').textContent = pem.keluhan_utama || '-';
        document.getElementById('summaryDiagnosa').textContent = pem.diagnosa || '-';
        document.getElementById('summaryResepObat').textContent = pem.resep_obat || 'Belum ada resep obat dicatat di rekam medis.';

        // 3. Set Hidden Fields
        document.getElementById('hidden_id_pasien').value = p.id_pasien;
        document.getElementById('hidden_id_pendaftaran').value = p.id_pendaftaran || '';

        // 4. Load or Initialize Items
        daftarItems = [];
        if (bayar && bayar.rincian_obat && Array.isArray(bayar.rincian_obat) && bayar.rincian_obat.length > 0) {
            daftarItems = bayar.rincian_obat;
            if (bayar.metode_pembayaran) document.getElementById('inputMetode').value = bayar.metode_pembayaran;
            if (bayar.uang_dibayar) document.getElementById('inputUangDibayar').value = bayar.uang_dibayar;
            if (bayar.catatan) document.getElementById('inputCatatan').value = bayar.catatan;
        } else if (data.obat_saran && data.obat_saran.length > 0) {
            data.obat_saran.forEach(ob => {
                daftarItems.push({
                    kategori: 'Obat',
                    nama: ob.nama,
                    jumlah: ob.jumlah || 1,
                    harga: 0,
                    subtotal: 0
                });
            });
            daftarItems.push({
                kategori: 'Tindakan',
                nama: 'Konsultasi & Pemeriksaan Dokter',
                jumlah: 1,
                harga: 35000,
                subtotal: 35000
            });
        } else {
            daftarItems.push({
                kategori: 'Tindakan',
                nama: 'Konsultasi & Pemeriksaan Dokter',
                jumlah: 1,
                harga: 35000,
                subtotal: 35000
            });
        }

        renderTabelObat();
        showToast('Pasien Dipilih', `Data pasien ${p.nama_lengkap} berhasil dimuat.`, 'success');
    })
    .catch(err => {
        console.error(err);
        showToast('Terjadi Kesalahan', 'Gagal memuat data pasien.', 'error');
    });
}

// ================= SALIN RESEP KE TABEL OBAT =================
function imporResepKeTabel() {
    const resepText = document.getElementById('summaryResepObat').textContent.trim();
    if (!resepText || resepText.includes('Belum ada resep')) {
        showToast('Resep Kosong', 'Tidak ada resep obat yang dapat disalin.', 'warning');
        return;
    }

    const lines = resepText.split(/[\r\n,;]+/);
    let addedCount = 0;
    lines.forEach(l => {
        const clean = l.trim();
        if (clean.length > 0) {
            let qty = 1;
            const match = clean.match(/(\d+)\s*(strip|tablet|tab|kapsul|btl|botol|pcs|x)/i);
            if (match) qty = parseInt(match[1]) || 1;
            
            daftarItems.push({
                kategori: 'Obat',
                nama: clean,
                jumlah: qty,
                harga: 0,
                subtotal: 0
            });
            addedCount++;
        }
    });

    renderTabelObat();
    showToast('Resep Disalin', `${addedCount} item obat ditambahkan ke daftar. Silakan lengkapi harga obat.`, 'success');
}

// ================= TABEL OBAT & TINDAKAN INTERAKTIF =================
function tambahBarisObat(kategori = 'Obat') {
    daftarItems.push({
        kategori: kategori,
        nama: kategori === 'Obat' ? '' : 'Pemeriksaan Dokter',
        jumlah: 1,
        harga: kategori === 'Obat' ? 0 : 35000,
        subtotal: kategori === 'Obat' ? 0 : 35000
    });
    renderTabelObat();
}

function hapusBaris(index) {
    daftarItems.splice(index, 1);
    renderTabelObat();
}

function updateItem(index, field, value) {
    if (!daftarItems[index]) return;
    
    if (field === 'jumlah') {
        daftarItems[index].jumlah = Math.max(1, parseInt(value) || 1);
    } else if (field === 'harga') {
        daftarItems[index].harga = Math.max(0, parseFloat(value) || 0);
    } else if (field === 'nama') {
        daftarItems[index].nama = value;
    } else if (field === 'kategori') {
        daftarItems[index].kategori = value;
    }

    daftarItems[index].subtotal = daftarItems[index].jumlah * daftarItems[index].harga;
    
    const subtotalEl = document.getElementById(`subtotal-${index}`);
    if (subtotalEl) {
        subtotalEl.textContent = 'Rp ' + formatRupiah(daftarItems[index].subtotal);
    }

    hitungTotalSemua();
}

function renderTabelObat() {
    const tbody = document.getElementById('bodyTabelObat');
    if (!tbody) return;

    if (daftarItems.length === 0) {
        tbody.innerHTML = `
            <tr id="emptyRow">
                <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-400">
                    <div class="flex flex-col items-center justify-center">
                        <span class="material-symbols-outlined text-slate-300 text-3xl mb-1">medication_liquid</span>
                        <p>Belum ada rincian obat atau tindakan.</p>
                        <p class="text-xs text-slate-400 mt-0.5">Klik <b>+ Obat</b> atau <b>+ Tindakan</b> di atas untuk menambahkan.</p>
                    </div>
                </td>
            </tr>
        `;
        hitungTotalSemua();
        return;
    }

    let html = '';
    daftarItems.forEach((item, idx) => {
        const isObat = (item.kategori || 'Obat') === 'Obat';
        html += `
            <tr class="hover:bg-slate-50/70 transition-colors">
                <td class="px-3.5 py-2.5">
                    <select onchange="updateItem(${idx}, 'kategori', this.value)" class="text-xs font-semibold rounded-lg border border-slate-200 px-2 py-1.5 ${isObat ? 'bg-emerald-50 text-emerald-700' : 'bg-blue-50 text-blue-700'}">
                        <option value="Obat" ${isObat ? 'selected' : ''}>Obat</option>
                        <option value="Tindakan" ${!isObat ? 'selected' : ''}>Tindakan</option>
                    </select>
                </td>
                <td class="px-3.5 py-2.5">
                    <input
                        type="text"
                        list="listObatPopuler"
                        placeholder="Nama obat / tindakan..."
                        value="${escapeHtml(item.nama || '')}"
                        oninput="updateItem(${idx}, 'nama', this.value)"
                        class="w-full text-xs sm:text-sm font-semibold text-slate-800 bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-200">
                </td>
                <td class="px-3.5 py-2.5 text-center">
                    <input
                        type="number"
                        min="1"
                        value="${item.jumlah || 1}"
                        oninput="updateItem(${idx}, 'jumlah', this.value)"
                        class="w-16 text-center text-xs sm:text-sm font-bold text-slate-800 bg-white border border-slate-200 rounded-lg px-1.5 py-1.5 focus:outline-none focus:border-emerald-500">
                </td>
                <td class="px-3.5 py-2.5 text-right">
                    <div class="relative inline-block w-full max-w-[140px]">
                        <span class="absolute left-2 top-1/2 -translate-y-1/2 text-xs text-slate-400 font-medium">Rp</span>
                        <input
                            type="number"
                            min="0"
                            step="500"
                            placeholder="0"
                            value="${item.harga || ''}"
                            oninput="updateItem(${idx}, 'harga', this.value)"
                            class="w-full text-right text-xs sm:text-sm font-bold text-slate-800 bg-white border border-slate-200 rounded-lg pl-7 pr-2 py-1.5 focus:outline-none focus:border-emerald-500">
                    </div>
                </td>
                <td class="px-3.5 py-2.5 text-right font-bold text-xs sm:text-sm text-slate-900" id="subtotal-${idx}">
                    Rp ${formatRupiah(item.subtotal || 0)}
                </td>
                <td class="px-3.5 py-2.5 text-center">
                    <button type="button" onclick="hapusBaris(${idx})" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus baris">
                        <span class="material-symbols-outlined text-[18px]">delete</span>
                    </button>
                </td>
            </tr>
        `;
    });

    tbody.innerHTML = html;
    hitungTotalSemua();
}

// ================= PERHITUNGAN TOTAL & KEMBALIAN =================
function hitungTotalSemua() {
    let totalObat = 0;
    let totalTindakan = 0;

    daftarItems.forEach(it => {
        const sub = (it.jumlah || 1) * (it.harga || 0);
        it.subtotal = sub;
        if ((it.kategori || 'Obat') === 'Obat') {
            totalObat += sub;
        } else {
            totalTindakan += sub;
        }
    });

    const grandTotal = totalObat + totalTindakan;

    document.getElementById('displayBiayaObat').textContent = 'Rp ' + formatRupiah(totalObat);
    document.getElementById('displayBiayaTindakan').textContent = 'Rp ' + formatRupiah(totalTindakan);
    document.getElementById('displayTotalBayar').textContent = 'Rp ' + formatRupiah(grandTotal);
    document.getElementById('cardTotalBayar').textContent = 'Rp ' + formatRupiah(grandTotal);

    document.getElementById('hidden_biaya_obat').value = totalObat;
    document.getElementById('hidden_biaya_tindakan').value = totalTindakan;
    document.getElementById('hidden_rincian_obat').value = JSON.stringify(daftarItems);

    hitungKembalian();
}

function hitungKembalian() {
    const totalObat = parseFloat(document.getElementById('hidden_biaya_obat').value) || 0;
    const totalTindakan = parseFloat(document.getElementById('hidden_biaya_tindakan').value) || 0;
    const grandTotal = totalObat + totalTindakan;

    const inputUang = document.getElementById('inputUangDibayar');
    const uangDibayar = parseFloat(inputUang.value) || 0;

    const kembalian = uangDibayar - grandTotal;
    const displayKembalian = document.getElementById('displayKembalian');
    const statusBadge = document.getElementById('badgeKembalianStatus');
    const hiddenKembalian = document.getElementById('hidden_kembalian');

    hiddenKembalian.value = Math.max(0, kembalian);

    if (kembalian === 0) {
        statusBadge.textContent = 'Pas';
        statusBadge.className = 'text-[11px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800';
        displayKembalian.textContent = 'Rp 0';
        displayKembalian.className = 'text-xl font-extrabold text-slate-800 mt-1';
    } else if (kembalian > 0) {
        statusBadge.textContent = 'Kembalian';
        statusBadge.className = 'text-[11px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800';
        displayKembalian.textContent = 'Rp ' + formatRupiah(kembalian);
        displayKembalian.className = 'text-xl font-extrabold text-emerald-600 mt-1';
    } else {
        statusBadge.textContent = 'Kurang Rp ' + formatRupiah(Math.abs(kembalian));
        statusBadge.className = 'text-[11px] font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-700';
        displayKembalian.textContent = '- Rp ' + formatRupiah(Math.abs(kembalian));
        displayKembalian.className = 'text-xl font-extrabold text-red-600 mt-1';
    }
}

function setUangPas() {
    const totalObat = parseFloat(document.getElementById('hidden_biaya_obat').value) || 0;
    const totalTindakan = parseFloat(document.getElementById('hidden_biaya_tindakan').value) || 0;
    const grandTotal = totalObat + totalTindakan;
    document.getElementById('inputUangDibayar').value = grandTotal;
    hitungKembalian();
}

function setQuickCash(amount) {
    document.getElementById('inputUangDibayar').value = amount;
    hitungKembalian();
}

// ================= SIMPAN PEMBAYARAN VIA AJAX =================
function setStatusLunasUI(sudahLunas) {
    const btn = document.getElementById('btnSimpanPembayaran');
    if (!btn) return;

    if (sudahLunas) {
        btn.disabled = true;
        btn.classList.add('opacity-60', 'cursor-not-allowed');
        btn.innerHTML = '<span class="material-symbols-outlined text-[20px]">verified</span><span>Sudah Lunas</span>';
    } else {
        btn.disabled = false;
        btn.classList.remove('opacity-60', 'cursor-not-allowed');
        btn.innerHTML = '<span class="material-symbols-outlined text-[20px]">payments</span><span>Simpan & Selesaikan Pembayaran</span>';
    }
}

function submitPembayaran(e) {
    e.preventDefault();

    const idPasien = document.getElementById('hidden_id_pasien').value;
    if (!idPasien) {
        showToast('Pilih Pasien Terlebih Dahulu', 'Silakan pilih pasien dari daftar sebelum menyimpan transaksi.', 'warning');
        return;
    }

    const statusBayar = document.getElementById('hidden_status_bayar').value.toLowerCase();
    if (['lunas', 'selesai'].includes(statusBayar)) {
        showToast('Pembayaran Sudah Lunas', 'Pasien ini telah melunasi pembayaran. Tidak dapat diproses ulang.', 'warning');
        return;
    }

    if (daftarItems.length === 0) {
        showToast('Item Pembayaran Kosong', 'Tambahkan setidaknya satu obat atau tindakan.', 'warning');
        return;
    }

    const totalObat = parseFloat(document.getElementById('hidden_biaya_obat').value) || 0;
    const totalTindakan = parseFloat(document.getElementById('hidden_biaya_tindakan').value) || 0;
    const grandTotal = totalObat + totalTindakan;
    const uangDibayar = parseFloat(document.getElementById('inputUangDibayar').value) || 0;

    if (uangDibayar < grandTotal) {
        showToast('Uang Pembayaran Kurang', `Uang yang dibayarkan (Rp ${formatRupiah(uangDibayar)}) kurang dari total tagihan (Rp ${formatRupiah(grandTotal)}).`, 'error');
        return;
    }

    const btn = document.getElementById('btnSimpanPembayaran');
    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined text-[20px] animate-spin">autorenew</span><span>Menyimpan Pembayaran...</span>';

    document.getElementById('hidden_rincian_obat').value = JSON.stringify(daftarItems);

    const formData = new FormData(document.getElementById('formPembayaran'));

    fetch('{{ route("pembayaran.store") }}', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-outlined text-[20px]">payments</span><span>Simpan & Selesaikan Pembayaran</span>';

        if (data.success) {
            showToast('Pembayaran Berhasil!', data.message, 'success');

            document.getElementById('badgeStatusKunjungan').textContent = 'Selesai';
            document.getElementById('badgeStatusKunjungan').className = 'text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800';

            cetakRiwayatNota({
                id: data.id_pembayaran || 1,
                tgl: data.tgl_bayar || new Date().toLocaleString('id-ID'),
                nama: data.nama_pasien || document.getElementById('cardNama').textContent,
                nik: document.getElementById('cardSub').textContent || '-',
                no_rm: data.no_rm || '',
                metode: document.getElementById('inputMetode').value,
                total: data.total_bayar || grandTotal,
                dibayar: data.uang_dibayar || uangDibayar,
                kembalian: data.kembalian || (uangDibayar - grandTotal),
                catatan: document.getElementById('inputCatatan').value,
                items: daftarItems,
                biaya_tindakan: totalTindakan,
                biaya_obat: totalObat
            });

            setTimeout(() => {
                window.location.href = "{{ route('pembayaran') }}?pasien_id=" + idPasien;
            }, 2500);

        } else {
            if (data.message && /lunas|melunasi|sudah bayar|telah melunasi/i.test(data.message)) {
                setStatusLunasUI(true);
            }
            showToast('Gagal Menyimpan', data.message || 'Terjadi kesalahan saat menyimpan pembayaran.', 'error');
        }
    })
    .catch(err => {
        console.error(err);
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-outlined text-[20px]">payments</span><span>Simpan & Selesaikan Pembayaran</span>';
        showToast('Terjadi Kesalahan', 'Gagal terhubung ke server. Periksa jaringan Anda.', 'error');
    });
}

// ================= MODAL NOTA & PRINT =================
function bukaModalNota() {
    const idPasien = document.getElementById('hidden_id_pasien').value;
    if (!idPasien) {
        showToast('Belum Memilih Pasien', 'Pilih pasien dan isi rincian terlebih dahulu.', 'warning');
        return;
    }

    const totalObat = parseFloat(document.getElementById('hidden_biaya_obat').value) || 0;
    const totalTindakan = parseFloat(document.getElementById('hidden_biaya_tindakan').value) || 0;
    const grandTotal = totalObat + totalTindakan;
    const uangDibayar = parseFloat(document.getElementById('inputUangDibayar').value) || grandTotal;
    const kembalian = Math.max(0, uangDibayar - grandTotal);

    cetakRiwayatNota({
        id: 'DRAFT',
        tgl: new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }),
        nama: document.getElementById('cardNama').textContent,
        nik: '',
        no_rm: '',
        metode: document.getElementById('inputMetode').value,
        total: grandTotal,
        dibayar: uangDibayar,
        kembalian: kembalian,
        catatan: document.getElementById('inputCatatan').value,
        items: daftarItems,
        biaya_tindakan: totalTindakan,
        biaya_obat: totalObat
    });
}

let ukuranNotaAktif = '80mm';

function setUkuranNota(size) {
    ukuranNotaAktif = size;
    const btn80 = document.getElementById('btnSize80');
    const btn58 = document.getElementById('btnSize58');
    const wrapper = document.getElementById('notaPaperWrapper');
    const lblBtn = document.getElementById('lblBtnUkuran');

    if (lblBtn) lblBtn.textContent = size;

    if (size === '58mm') {
        if (btn58) btn58.className = 'px-2.5 py-1 rounded-lg font-bold text-xs transition-all bg-white text-emerald-700 shadow-sm flex items-center gap-1';
        if (btn80) btn80.className = 'px-2.5 py-1 rounded-lg font-semibold text-xs transition-all text-slate-600 hover:text-slate-900 flex items-center gap-1';
        if (wrapper) {
            wrapper.style.maxWidth = '250px';
        }
    } else {
        if (btn80) btn80.className = 'px-2.5 py-1 rounded-lg font-bold text-xs transition-all bg-white text-emerald-700 shadow-sm flex items-center gap-1';
        if (btn58) btn58.className = 'px-2.5 py-1 rounded-lg font-semibold text-xs transition-all text-slate-600 hover:text-slate-900 flex items-center gap-1';
        if (wrapper) {
            wrapper.style.maxWidth = '340px';
        }
    }
}

function cetakRiwayatNota(data) {
    document.getElementById('notaNoTrx').textContent = '#TRX-' + String(data.id).padStart(4, '0');
    document.getElementById('notaTanggal').textContent = data.tgl || '-';
    document.getElementById('notaPasien').textContent = data.nama + (data.no_rm ? ` (${data.no_rm})` : '');
    document.getElementById('notaMetode').textContent = (data.metode || 'TUNAI').toUpperCase();

    const tbody = document.getElementById('notaItemBody');
    let itemsHtml = '';
    const items = data.items || [];

    if (items.length > 0) {
        items.forEach(it => {
            const qty = it.jumlah || 1;
            const sub = qty * (it.harga || 0);
            const hargaUnit = it.harga ? `Rp ${formatRupiah(it.harga)}` : '';
            itemsHtml += `
                <tr class="py-1">
                    <td class="py-1 text-left align-top">
                        <div class="item-name font-semibold text-slate-800 text-xs">${escapeHtml(it.nama || '-')}</div>
                        ${hargaUnit ? `<div class="item-price-unit text-[10px] text-slate-500">@ ${hargaUnit}</div>` : ''}
                    </td>
                    <td class="py-1 text-center text-slate-600 align-top text-xs">${qty}</td>
                    <td class="py-1 text-right font-bold text-slate-900 align-top text-xs">Rp ${formatRupiah(sub)}</td>
                </tr>
            `;
        });
    } else {
        itemsHtml = `
            <tr>
                <td colspan="3" class="py-2 text-center text-slate-400 text-xs italic">Rincian Layanan & Obat</td>
            </tr>
        `;
    }
    tbody.innerHTML = itemsHtml;

    document.getElementById('notaBiayaObat').textContent = 'Rp ' + formatRupiah(data.biaya_obat || 0);
    document.getElementById('notaBiayaTindakan').textContent = 'Rp ' + formatRupiah(data.biaya_tindakan || 0);
    document.getElementById('notaTotalBayar').textContent = 'Rp ' + formatRupiah(data.total || 0);
    document.getElementById('notaUangDibayar').textContent = 'Rp ' + formatRupiah(data.dibayar || 0);
    document.getElementById('notaKembalian').textContent = 'Rp ' + formatRupiah(data.kembalian || 0);

    setUkuranNota(ukuranNotaAktif);

    const modal = document.getElementById('modalNota');
    modal.classList.remove('hidden');
}

function tutupModalNota() {
    document.getElementById('modalNota').classList.add('hidden');
}

// Close modal on outside click and Escape key
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('modalNota');
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) tutupModalNota();
        });
    }
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const m = document.getElementById('modalNota');
            if (m && !m.classList.contains('hidden')) tutupModalNota();
        }
    });
});

function printNota() {
    const is58 = ukuranNotaAktif === '58mm';
    const paperWidth = is58 ? '58mm' : '80mm';
    const baseFontSize = is58 ? '9.5px' : '11px';
    const headerTitleSize = is58 ? '13px' : '15px';
    const headerSubSize = is58 ? '8.5px' : '9.5px';
    const padding = is58 ? '2mm 1.5mm' : '3mm 2.5mm';

    const printContents = document.getElementById('printArea').innerHTML;
    const noTrx = document.getElementById('notaNoTrx')?.textContent?.trim() || 'TRX';

    const win = window.open('', '', 'height=680,width=450');
    if (!win) {
        alert('Jendela cetak terblokir oleh browser. Harap izinkan popup untuk mencetak nota.');
        return;
    }

    win.document.write(`<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Nota_${noTrx}_Mandalacare</title>
    <style>
        @page {
            size: ${paperWidth} auto;
            margin: 0mm;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        html, body {
            width: ${paperWidth};
            background: #ffffff;
            margin: 0 auto;
            padding: 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-size: ${baseFontSize};
            line-height: 1.35;
            color: #000000;
            padding: ${padding};
            width: ${paperWidth};
            max-width: ${paperWidth};
            margin: 0 auto;
            word-break: break-word;
        }
        @media print {
            html, body {
                width: ${paperWidth} !important;
                max-width: ${paperWidth} !important;
                margin: 0 !important;
                padding: 0 !important;
                background: #ffffff !important;
            }
            .nota-container {
                width: 100% !important;
                max-width: ${paperWidth} !important;
                margin: 0 !important;
                padding: ${padding} !important;
                box-shadow: none !important;
                border: none !important;
            }
        }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .font-bold { font-weight: 700; }
        .font-semibold { font-weight: 600; }
        .font-extrabold { font-weight: 800; }
        .font-medium { font-weight: 500; }
        .uppercase { text-transform: uppercase; }
        .italic { font-style: italic; }

        .clinic-title {
            font-size: ${headerTitleSize};
            font-weight: 800;
            color: #065f46;
            letter-spacing: -0.01em;
            margin-bottom: 2px;
            text-align: center;
        }
        .clinic-sub {
            font-size: ${headerSubSize};
            color: #374151;
            line-height: 1.25;
            text-align: center;
        }

        .dashed-sep {
            border-top: 1px dashed #4b5563;
            margin: 5px 0;
            height: 0;
        }

        .flex {
            display: flex;
        }
        .justify-between {
            justify-content: space-between;
        }
        .items-center {
            align-items: center;
        }

        .row-meta {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2px;
            font-size: ${baseFontSize};
        }
        .row-meta .label {
            color: #4b5563;
            flex-shrink: 0;
            margin-right: 6px;
        }
        .row-meta .value {
            font-weight: 600;
            color: #000000;
            text-align: right;
            word-break: break-word;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: ${baseFontSize};
            margin: 3px 0;
        }
        th {
            border-bottom: 1px dashed #4b5563;
            padding: 3px 0;
            font-weight: 600;
            color: #374151;
        }
        td {
            padding: 2.5px 0;
            vertical-align: top;
        }
        .item-name {
            font-weight: 600;
            color: #000000;
            line-height: 1.25;
        }
        .item-price-unit {
            font-size: ${headerSubSize};
            color: #4b5563;
        }

        .total-box {
            margin-top: 2px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
            color: #374151;
            font-size: ${baseFontSize};
        }
        .total-row .val {
            font-weight: 600;
            color: #000000;
        }
        .grand-total {
            font-size: ${is58 ? '12px' : '13.5px'};
            font-weight: 800;
            color: #065f46;
            padding: 4px 0;
            border-top: 1px dashed #000000;
            border-bottom: 1px dashed #000000;
            margin: 4px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer {
            margin-top: 6px;
            text-align: center;
            font-size: ${headerSubSize};
            color: #4b5563;
            line-height: 1.35;
        }
        .footer .lunas-badge {
            font-size: ${baseFontSize};
            font-weight: 700;
            color: #065f46;
            margin-bottom: 2px;
        }
        .footer .system-tag {
            font-size: 8px;
            color: #9ca3af;
            margin-top: 4px;
            letter-spacing: 0.05em;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="nota-container">
        ${printContents}
    </div>
</body>
</html>`);

    win.document.close();
    win.focus();
    setTimeout(() => {
        win.print();
        win.onafterprint = function() {
            try { win.close(); } catch(e) {}
        };
        setTimeout(() => {
            try { win.close(); } catch(e) {}
        }, 1500);
    }, 350);
}

// ================= FILTER RIWAYAT TABLE =================
function filterRiwayat(query) {
    const q = (query || '').toLowerCase();
    const rows = document.querySelectorAll('.riwayat-row');
    let visible = 0;
    rows.forEach(r => {
        const text = (r.dataset.search || '').toLowerCase();
        if (text.includes(q)) {
            r.style.display = '';
            visible++;
        } else {
            r.style.display = 'none';
        }
    });
}

// ================= TOAST NOTIFICATION UTILS =================
let toastTimer;
function showToast(title, msg, type = 'success') {
    const toast = document.getElementById('toastNotification');
    const icon  = document.getElementById('toastIcon');
    const bar   = document.getElementById('toastAccentBar');
    const ic    = document.getElementById('toastIconContainer');
    document.getElementById('toastTitle').textContent   = title;
    document.getElementById('toastMessage').textContent = msg;

    if (type === 'success') {
        icon.textContent = 'check_circle';
        bar.style.background = '#006c47';
        ic.style.color = '#006c47';
        ic.style.background = '#E5F5F0';
    } else if (type === 'error') {
        icon.textContent = 'error';
        bar.style.background = '#ef4444';
        ic.style.color = '#ef4444';
        ic.style.background = '#fef2f2';
    } else {
        icon.textContent = 'warning';
        bar.style.background = '#f59e0b';
        ic.style.color = '#f59e0b';
        ic.style.background = '#fffbeb';
    }

    toast.classList.remove('hidden', '-translate-y-16', 'opacity-0');
    toast.classList.add('translate-y-0', 'opacity-100');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(hideToast, 5000);
}

function hideToast() {
    const toast = document.getElementById('toastNotification');
    if (!toast) return;
    toast.classList.remove('translate-y-0', 'opacity-100');
    toast.classList.add('-translate-y-16', 'opacity-0');
    setTimeout(() => toast.classList.add('hidden'), 350);
}

// Helpers
function formatRupiah(number) {
    return new Intl.NumberFormat('id-ID').format(Math.round(number || 0));
}

function escapeHtml(string) {
    const div = document.createElement('div');
    div.innerText = string;
    return div.innerHTML;
}

// ================= INITIAL LOAD =================
@if($selectedPasien)
document.addEventListener('DOMContentLoaded', () => {
    selectPasien({{ $selectedPasien->id_pasien }});
});
@endif

@if(session('success'))
document.addEventListener('DOMContentLoaded', () => showToast('Berhasil!', '{{ session("success") }}', 'success'));
@endif
@if(session('error'))
document.addEventListener('DOMContentLoaded', () => showToast('Gagal!', '{{ session("error") }}', 'error'));
@endif
</script>
@endsection