@extends('layouts.app')

@section('title', 'Mandalacare - Pembayaran & Kasir')
@section('header_title', 'Kasir & Pembayaran Pasien')
@section('header_subtitle', 'Proses rincian biaya obat, tindakan medis, dan cetak struk pembayaran pasien.')

@section('content')
{{-- ================= TOAST NOTIFICATION ================= --}}
<div id="toastNotification" class="fixed top-6 left-1/2 -translate-x-1/2 z-50 w-[92%] max-w-md pointer-events-none transition-all duration-300 transform -translate-y-16 opacity-0 hidden">
    <div id="toastCard" class="pointer-events-auto bg-white border border-slate-200 rounded-2xl shadow-xl p-4 flex items-start gap-3.5 backdrop-blur-md relative overflow-hidden">
        <div id="toastAccentBar" class="absolute left-0 top-0 bottom-0 w-1.5 bg-primary"></div>
        <div id="toastIconContainer" class="w-10 h-10 rounded-xl bg-[#E5F5F0] text-primary flex items-center justify-center shrink-0">
            <span id="toastIcon" class="material-symbols-outlined text-xl font-bold">check_circle</span>
        </div>
        <div class="flex-1 min-w-0 pr-1">
            <h4 class="text-sm font-bold text-on-surface" id="toastTitle">Pembayaran Tersimpan!</h4>
            <p class="text-xs text-on-surface-variant mt-0.5 leading-relaxed" id="toastMessage">Data pembayaran pasien berhasil dicatat.</p>
        </div>
        <button type="button" onclick="hideToast()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg shrink-0">
            <span class="material-symbols-outlined text-base">close</span>
        </button>
    </div>
</div>

<div class="p-4 sm:p-6 md:p-8 lg:p-10 w-full max-w-7xl mx-auto flex-1 flex flex-col gap-6">

    {{-- ================= TOP HEADER & QUICK ACTIONS ================= --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-on-surface tracking-tight">Kasir & Pembayaran Pasien</h1>
            <p class="text-sm text-on-surface-variant mt-1">
                Kelola tagihan obat, biaya tindakan medis, dan selesaikan transaksi kasir klinik secara terintegrasi.
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('rekam-medis') }}" class="bg-white border border-outline-variant hover:border-primary text-slate-700 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all inline-flex items-center gap-1.5 shadow-sm hover:shadow">
                <span class="material-symbols-outlined text-primary text-lg">medical_services</span>
                <span>Rekam Medis</span>
            </a>
            <a href="{{ route('evaluasi') }}" class="bg-white border border-outline-variant hover:border-primary text-slate-700 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all inline-flex items-center gap-1.5 shadow-sm hover:shadow">
                <span class="material-symbols-outlined text-primary text-lg">assignment_turned_in</span>
                <span>Evaluasi</span>
            </a>
        </div>
    </div>


    {{-- ================= RINGKASAN PENDAPATAN (ELEGANT STATS) ================= --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5">
        {{-- Hari Ini --}}
        <div class="bg-white rounded-xl border border-outline-variant p-4 sm:p-5 card-shadow flex items-start justify-between">
            <div class="min-w-0 flex-1">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Pendapatan Hari Ini</p>
                <h3 id="summaryHariIni" class="text-xl sm:text-2xl font-bold text-on-surface mt-1.5 tracking-tight truncate">
                    Rp {{ number_format($pendapatanHariIni ?? 0, 0, ',', '.') }}
                </h3>
                <div class="flex items-center gap-1.5 mt-1.5 text-xs text-slate-500">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                    <span id="summaryTrxHariIni" class="font-medium text-slate-600 truncate">{{ $transaksiHariIni ?? 0 }} transaksi berhasil</span>
                </div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-[#E5F5F0] text-primary flex items-center justify-center shrink-0 ml-3">
                <span class="material-symbols-outlined text-2xl">payments</span>
            </div>
        </div>

        {{-- Minggu Ini --}}
        <div class="bg-white rounded-xl border border-outline-variant p-4 sm:p-5 card-shadow flex items-start justify-between">
            <div class="min-w-0 flex-1">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Pendapatan Minggu Ini</p>
                <h3 class="text-xl sm:text-2xl font-bold text-on-surface mt-1.5 tracking-tight truncate">
                    Rp {{ number_format($pendapatanMingguIni ?? 0, 0, ',', '.') }}
                </h3>
                <div class="flex items-center gap-1.5 mt-1.5 text-xs text-slate-500">
                    <span class="w-2 h-2 rounded-full bg-blue-500 shrink-0"></span>
                    <span class="font-medium text-slate-600 truncate">{{ $transaksiMingguIni ?? 0 }} transaksi minggu ini</span>
                </div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-[#E8F0FE] text-[#1A73E8] flex items-center justify-center shrink-0 ml-3">
                <span class="material-symbols-outlined text-2xl">calendar_view_week</span>
            </div>
        </div>

        {{-- Bulan Ini --}}
        <div class="bg-white rounded-xl border border-outline-variant p-4 sm:p-5 card-shadow flex items-start justify-between">
            <div class="min-w-0 flex-1">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Pendapatan Bulan Ini</p>
                <h3 class="text-xl sm:text-2xl font-bold text-on-surface mt-1.5 tracking-tight truncate">
                    Rp {{ number_format($pendapatanBulanIni ?? 0, 0, ',', '.') }}
                </h3>
                <div class="flex items-center gap-1.5 mt-1.5 text-xs text-slate-500">
                    <span class="w-2 h-2 rounded-full bg-purple-500 shrink-0"></span>
                    <span class="font-medium text-slate-600 truncate">{{ $transaksiBulanIni ?? 0 }} transaksi bulan ini</span>
                </div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-[#F3E8FF] text-[#9333EA] flex items-center justify-center shrink-0 ml-3">
                <span class="material-symbols-outlined text-2xl">account_balance_wallet</span>
            </div>
        </div>
    </div>


    {{-- ================= MAIN CASHIER & BILLING FORM ================= --}}
    <form id="formPembayaran" onsubmit="submitPembayaran(event)">
        @csrf
        <input type="hidden" name="id_pasien" id="hidden_id_pasien" value="{{ $selectedPasien ? $selectedPasien->id_pasien : '' }}">
        <input type="hidden" name="id_pendaftaran" id="hidden_id_pendaftaran" value="{{ $latestPendaftaran ? $latestPendaftaran->id_pendaftaran : '' }}">
        <input type="hidden" name="biaya_obat" id="hidden_biaya_obat" value="{{ $existingPembayaran ? $existingPembayaran->biaya_obat : 0 }}">
        <input type="hidden" name="biaya_tindakan" id="hidden_biaya_tindakan" value="{{ $existingPembayaran ? $existingPembayaran->biaya_tindakan : 0 }}">
        <input type="hidden" name="rincian_obat" id="hidden_rincian_obat" value="">
        <input type="hidden" name="rincian_tindakan" id="hidden_rincian_tindakan" value="">
        <input type="hidden" id="hidden_status_bayar" value="{{ $existingPembayaran ? $existingPembayaran->status_bayar : '' }}">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            {{-- ================= LEFT COLUMN: BILLING & CLINICAL ITEMS ================= --}}
            <div class="lg:col-span-8 flex flex-col gap-5 w-full min-w-0">

                {{-- CARD 1: PILIH PASIEN & INFORMASI KUNJUNGAN --}}
                <div class="bg-white rounded-xl border border-outline-variant p-4 sm:p-5 card-shadow">
                    <div class="flex items-center justify-between mb-3.5">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-[#E5F5F0] text-primary flex items-center justify-center text-xs font-bold">
                                <span class="material-symbols-outlined text-sm">person_search</span>
                            </span>
                            <h2 class="text-sm sm:text-base font-bold text-on-surface">Pilih Pasien untuk Pembayaran</h2>
                        </div>
                        <span class="text-xs text-slate-500 font-medium bg-slate-100 px-2.5 py-1 rounded-full">
                            {{ $daftarPasien->count() }} Pasien Terdaftar
                        </span>
                    </div>

                    {{-- Search Input with Dropdown --}}
                    <div class="relative mb-3.5">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg pointer-events-none">
                            search
                        </span>

                        <input
                            id="pasienSearchInput"
                            type="text"
                            placeholder="Ketik nama pasien, NIK, atau No. Rekam Medis..."
                            value="{{ $selectedPasien ? $selectedPasien->nama_lengkap : '' }}"
                            autocomplete="off"
                            onclick="showDropdown(event)"
                            onfocus="showDropdown(event)"
                            oninput="filterDropdown(this.value)"
                            class="w-full bg-slate-50 hover:bg-white border border-slate-300 rounded-xl py-2.5 pl-10 pr-9 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">

                        <button
                            type="button"
                            onclick="clearSearch(event)"
                            class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                            <span class="material-symbols-outlined text-base">close</span>
                        </button>

                        {{-- Dropdown Suggestions --}}
                        <div id="pasienDropdownList" class="absolute left-0 right-0 top-full mt-1.5 bg-white border border-slate-200 rounded-xl shadow-xl z-30 max-h-64 overflow-y-auto hidden divide-y divide-slate-100">
                            @forelse ($daftarPasien as $p)
                                @php
                                    $isLunas = $p->pendaftaranTerbaru && $p->pendaftaranTerbaru->pembayaran && in_array(strtolower($p->pendaftaranTerbaru->pembayaran->status_bayar), ['lunas', 'selesai']);
                                @endphp
                                <div
                                    class="pasien-dropdown-item flex items-center justify-between p-3 hover:bg-[#E5F5F0]/40 cursor-pointer transition-colors group"
                                    data-id="{{ $p->id_pasien }}"
                                    data-nama="{{ $p->nama_lengkap }}"
                                    data-nik="{{ $p->nik }}"
                                    onclick="selectPasien({{ $p->id_pasien }})">

                                    <div class="flex items-center gap-3 min-w-0 pr-2">
                                        <div class="w-9 h-9 rounded-full bg-[#E5F5F0] text-primary flex items-center justify-center text-xs font-bold shrink-0 group-hover:bg-primary group-hover:text-white transition-colors">
                                            {{ $p->initials }}
                                        </div>
                                        <div class="min-w-0">
                                            <h4 class="text-sm font-semibold text-slate-800 group-hover:text-primary transition-colors truncate">{{ $p->nama_lengkap }}</h4>
                                            <p class="text-xs text-slate-500 truncate">
                                                No. RM: <span class="font-medium text-slate-700">{{ $p->no_rm }}</span> &bull; NIK: {{ $p->nik }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 shrink-0">
                                        @if($isLunas)
                                            <span class="text-[10px] bg-emerald-50 text-emerald-700 border border-emerald-200 font-semibold px-2 py-0.5 rounded-full">Lunas</span>
                                        @else
                                            <span class="text-[10px] bg-amber-50 text-amber-700 border border-amber-200 font-semibold px-2 py-0.5 rounded-full">Menunggu</span>
                                        @endif
                                        <span class="material-symbols-outlined text-slate-400 group-hover:text-primary text-base transition-colors">arrow_forward</span>
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-center text-xs text-slate-500">
                                    Belum ada data pasien. <a href="{{ route('pendaftaran') }}" class="text-primary underline font-semibold">Daftarkan pasien</a>.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Selected Patient Banner --}}
                    <div class="p-3.5 sm:p-4 bg-slate-50/80 rounded-xl border border-slate-200/90 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div id="cardAvatar" class="w-11 h-11 rounded-full bg-primary/10 text-primary flex items-center justify-center text-sm font-bold shrink-0">
                                {{ $selectedPasien ? $selectedPasien->initials : 'PS' }}
                            </div>

                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h4 id="cardNama" class="font-bold text-slate-900 text-sm sm:text-base leading-tight truncate">
                                        {{ $selectedPasien ? $selectedPasien->nama_lengkap : 'Belum Memilih Pasien' }}
                                    </h4>
                                    <span id="badgeStatusKunjungan" class="text-[11px] font-semibold px-2 py-0.5 rounded-full {{ ($selectedPasien && $latestPendaftaran) ? 'bg-blue-100 text-blue-800' : 'bg-slate-200 text-slate-600' }}">
                                        {{ ($selectedPasien && $latestPendaftaran) ? $latestPendaftaran->status_kunjungan : 'Menunggu Pasien' }}
                                    </span>
                                </div>
                                <p id="cardSub" class="text-xs text-slate-500 mt-1 leading-snug truncate">
                                    @if($selectedPasien)
                                        RM: <span class="text-primary font-semibold">{{ $selectedPasien->no_rm }}</span> &bull; NIK: {{ $selectedPasien->nik }} &bull; Umur: {{ $selectedPasien->age }} &bull; JK: {{ $selectedPasien->formatted_jk }}
                                    @else
                                        Silakan pilih pasien di atas untuk memproses tagihan kasir.
                                    @endif
                                </p>
                            </div>
                        </div>

                        <button
                            type="button"
                            id="btnPilihPasien"
                            onclick="toggleDropdown(event)"
                            class="bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 text-xs font-semibold px-3 py-2 rounded-lg transition-all flex items-center justify-center gap-1.5 shrink-0 shadow-sm active:scale-95">
                            <span class="material-symbols-outlined text-base text-slate-500">swap_horiz</span>
                            <span>Ganti Pasien</span>
                        </button>
                    </div>
                </div>


                {{-- CARD 2: RESEP DOKTER & HASIL PEMERIKSAAN MEDIS (CONTEXT CARD) --}}
                <div class="bg-white rounded-xl border border-outline-variant p-4 sm:p-5 card-shadow">
                    <div class="flex items-center justify-between mb-3 pb-2.5 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center text-xs font-bold">
                                <span class="material-symbols-outlined text-sm">clinical_notes</span>
                            </span>
                            <h3 class="text-sm font-bold text-on-surface">Catatan Medis & Resep Dokter</h3>
                        </div>
                        <span class="text-xs text-slate-400 font-medium">Data Rekam Medis</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                        <div class="p-3 bg-slate-50 rounded-lg border border-slate-100">
                            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block mb-1">Keluhan Utama</span>
                            <p id="summaryKeluhan" class="text-xs sm:text-sm font-medium text-slate-800 leading-relaxed">
                                {{ $latestAsuhan ? $latestAsuhan->keluhan_utama : 'Pilih pasien untuk melihat catatan keluhan' }}
                            </p>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-lg border border-slate-100">
                            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block mb-1">Diagnosa Medis</span>
                            <p id="summaryDiagnosa" class="text-xs sm:text-sm font-medium text-slate-800 leading-relaxed whitespace-pre-line">
                                {{ ($latestIntervensi->isNotEmpty() && $latestIntervensi->first()->diagnosa_awal) ? $latestIntervensi->first()->diagnosa_awal : ($latestAsuhan ? $latestAsuhan->keluhan_utama : '-') }}
                            </p>
                        </div>
                    </div>

                    {{-- Resep Obat Strip --}}
                    <div class="p-3.5 rounded-xl bg-[#E5F5F0]/60 border border-primary/20 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-start gap-2.5 min-w-0">
                            <span class="material-symbols-outlined text-primary text-xl shrink-0 mt-0.5">medication</span>
                            <div class="min-w-0">
                                <h4 class="text-xs font-bold text-primary uppercase tracking-wider">Resep Obat yang Diresepkan</h4>
                                <p id="summaryResepObat" class="text-xs sm:text-sm text-slate-800 font-medium mt-0.5 leading-snug">
                                    {{ ($latestImplementasi && $latestImplementasi->resep_obat) ? $latestImplementasi->resep_obat : 'Belum ada resep obat di rekam medis.' }}
                                </p>
                            </div>
                        </div>

                        <button
                            type="button"
                            id="btnImporResep"
                            onclick="imporResepKeTabel()"
                            class="bg-primary hover:bg-[#005a3c] text-white text-xs font-semibold px-3 py-2 rounded-lg transition-all shrink-0 inline-flex items-center justify-center gap-1.5 shadow-sm active:scale-95">
                            <span class="material-symbols-outlined text-base">download</span>
                            <span>Salin ke Tabel Tagihan</span>
                        </button>
                    </div>
                </div>


                {{-- CARD 3: RINCIAN TAGIHAN (OBAT & TINDAKAN MEDIS) --}}
                <div class="bg-white rounded-xl border border-outline-variant p-4 sm:p-5 card-shadow">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3.5 pb-3 border-b border-slate-100">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center text-xs font-bold">
                                    <span class="material-symbols-outlined text-sm">receipt_long</span>
                                </span>
                                <h3 class="text-sm sm:text-base font-bold text-on-surface">Rincian Obat & Layanan Medis</h3>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">Sesuaikan nama obat, jumlah, dan biaya layanan klinik.</p>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <button
                                type="button"
                                onclick="tambahBarisObat('Obat')"
                                class="bg-[#E5F5F0] text-primary hover:bg-primary hover:text-white border border-primary/30 text-xs font-bold px-3 py-2 rounded-lg transition-all inline-flex items-center gap-1 active:scale-95">
                                <span class="material-symbols-outlined text-sm">add</span>
                                <span>+ Obat</span>
                            </button>

                            <button
                                type="button"
                                onclick="tambahBarisObat('Tindakan')"
                                class="bg-[#E8F0FE] text-[#1A73E8] hover:bg-[#1A73E8] hover:text-white border border-[#1A73E8]/30 text-xs font-bold px-3 py-2 rounded-lg transition-all inline-flex items-center gap-1 active:scale-95">
                                <span class="material-symbols-outlined text-sm">add</span>
                                <span>+ Tindakan/Jasa</span>
                            </button>
                        </div>
                    </div>

                    {{-- Datalist Autocomplete Obat & Tindakan --}}
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

                    {{-- Table Container --}}
                    <div class="overflow-x-auto rounded-xl border border-slate-200 mb-4">
                        <table class="w-full text-left border-collapse" id="tabelObat">
                            <thead class="bg-slate-50/90 text-xs font-semibold text-slate-600 border-b border-slate-200">
                                <tr>
                                    <th class="px-3 py-2.5 w-28">Kategori</th>
                                    <th class="px-3 py-2.5 min-w-[200px]">Nama Obat / Tindakan Medis</th>
                                    <th class="px-3 py-2.5 text-center w-20">Qty</th>
                                    <th class="px-3 py-2.5 text-right min-w-[130px]">Harga Satuan (Rp)</th>
                                    <th class="px-3 py-2.5 text-right min-w-[130px]">Subtotal (Rp)</th>
                                    <th class="px-3 py-2.5 text-center w-12">Aksi</th>
                                </tr>
                            </thead>

                            <tbody id="bodyTabelObat" class="divide-y divide-slate-100">
                                <tr id="emptyRow">
                                    <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <span class="material-symbols-outlined text-slate-300 text-3xl mb-1">medication_liquid</span>
                                            <p class="font-medium text-slate-500">Belum ada rincian obat atau tindakan.</p>
                                            <p class="text-xs text-slate-400 mt-0.5">Klik <b>+ Obat</b> atau <b>+ Tindakan</b> di atas untuk menambahkan.</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Subtotal Summary Breakdown --}}
                    <div class="bg-slate-50/90 rounded-xl p-3.5 sm:p-4 border border-slate-200 space-y-2 text-xs sm:text-sm">
                        <div class="flex justify-between items-center text-slate-600">
                            <span class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                Total Biaya Tindakan & Jasa Layanan:
                            </span>
                            <span id="displayBiayaTindakan" class="font-semibold text-slate-800">
                                Rp 0
                            </span>
                        </div>

                        <div class="flex justify-between items-center text-slate-600">
                            <span class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                Total Biaya Obat-obatan:
                            </span>
                            <span id="displayBiayaObat" class="font-semibold text-slate-800">
                                Rp 0
                            </span>
                        </div>

                        <div class="flex justify-between items-center pt-2.5 border-t border-slate-200">
                            <span class="font-bold text-slate-900 text-sm">
                                Grand Total Tagihan:
                            </span>
                            <span id="displayTotalBayar" class="text-lg sm:text-xl font-extrabold text-primary">
                                Rp 0
                            </span>
                        </div>
                    </div>
                </div>

            </div>


            {{-- ================= RIGHT COLUMN: CASHIER PAYMENT PANEL ================= --}}
            <div class="lg:col-span-4 w-full min-w-0">
                <div class="lg:sticky lg:top-20 space-y-4">

                    <div class="bg-white rounded-xl border border-outline-variant p-4 sm:p-5 card-shadow">
                        <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <span class="w-7 h-7 rounded-lg bg-[#E5F5F0] text-primary flex items-center justify-center text-sm font-bold">
                                    <span class="material-symbols-outlined text-base">point_of_sale</span>
                                </span>
                                <h3 class="text-base font-bold text-on-surface">Kasir Pembayaran</h3>
                            </div>
                            <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">
                                Transaksi
                            </span>
                        </div>

                        <div class="space-y-4">

                            {{-- Clean, Trustworthy Total Bill Banner --}}
                            <div class="rounded-xl bg-slate-900 text-white p-4 shadow-sm border border-slate-800">
                                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">
                                    Total yang Harus Dibayar
                                </p>
                                <h3 id="cardTotalBayar" class="text-2xl sm:text-3xl font-extrabold text-emerald-400 mt-1 tracking-tight">
                                    Rp 0
                                </h3>
                                <p class="text-[11px] text-slate-400 mt-1">
                                    Termasuk rincian obat & layanan medis pasien.
                                </p>
                            </div>

                            {{-- Metode Pembayaran (Visual Chips) --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                    Metode Pembayaran <span class="text-red-500">*</span>
                                </label>

                                {{-- Hidden select for form compatibility --}}
                                <select name="metode_pembayaran" id="inputMetode" class="hidden">
                                    <option value="tunai" selected>Tunai (Cash)</option>
                                    <option value="qris">QRIS</option>
                                    <option value="transfer">Transfer Bank</option>
                                    <option value="debit">Kartu Debit</option>
                                </select>

                                {{-- Visual Radio Grid --}}
                                <div class="grid grid-cols-2 gap-2" id="metodePicker">
                                    <button
                                        type="button"
                                        onclick="pilihMetode('tunai')"
                                        data-metode="tunai"
                                        class="metode-btn flex items-center gap-2 p-2.5 rounded-xl border border-primary bg-[#E5F5F0] text-primary font-semibold text-xs transition-all shadow-xs text-left">
                                        <span class="material-symbols-outlined text-lg">payments</span>
                                        <span>Tunai (Cash)</span>
                                    </button>

                                    <button
                                        type="button"
                                        onclick="pilihMetode('qris')"
                                        data-metode="qris"
                                        class="metode-btn flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs transition-all shadow-xs text-left">
                                        <span class="material-symbols-outlined text-lg text-slate-500">qr_code_2</span>
                                        <span>QRIS</span>
                                    </button>

                                    <button
                                        type="button"
                                        onclick="pilihMetode('transfer')"
                                        data-metode="transfer"
                                        class="metode-btn flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs transition-all shadow-xs text-left">
                                        <span class="material-symbols-outlined text-lg text-slate-500">account_balance</span>
                                        <span>Transfer</span>
                                    </button>

                                    <button
                                        type="button"
                                        onclick="pilihMetode('debit')"
                                        data-metode="debit"
                                        class="metode-btn flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs transition-all shadow-xs text-left">
                                        <span class="material-symbols-outlined text-lg text-slate-500">credit_card</span>
                                        <span>Kartu Debit</span>
                                    </button>
                                </div>
                            </div>

                            {{-- Uang Dibayar / Diterima --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Uang Diterima / Dibayar <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">
                                        Rp
                                    </span>
                                    <input
                                        type="text"
                                        inputmode="numeric"
                                        id="inputUangDibayar"
                                        name="uang_dibayar"
                                        placeholder="0"
                                        value="0"
                                        required
                                        oninput="handleUangDibayarInput(this)"
                                        class="w-full rounded-xl border border-slate-300 bg-white pl-11 pr-3.5 py-2.5 text-base font-bold text-slate-900 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                </div>

                                {{-- Quick Cash Chips --}}
                                <div class="flex flex-wrap gap-1.5 mt-2">
                                    <button type="button" onclick="setUangPas()" class="px-2.5 py-1 bg-slate-100 hover:bg-[#E5F5F0] hover:text-primary text-slate-700 text-xs font-semibold rounded-lg transition-colors">
                                        Uang Pas
                                    </button>
                                    <button type="button" onclick="setQuickCash(50000)" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors">
                                        50.000
                                    </button>
                                    <button type="button" onclick="setQuickCash(100000)" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors">
                                        100.000
                                    </button>
                                    <button type="button" onclick="setQuickCash(200000)" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors">
                                        200.000
                                    </button>
                                    <button type="button" onclick="setQuickCash(500000)" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors">
                                        500.000
                                    </button>
                                </div>
                            </div>

                            {{-- Kembalian Box with Dynamic Visual Feedback --}}
                            <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/90 transition-all" id="kembalianContainer">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">
                                        Status Kembalian:
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

                            {{-- Catatan Tambahan --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Catatan Pembayaran (Opsional)
                                </label>
                                <textarea
                                    id="inputCatatan"
                                    name="catatan"
                                    rows="2"
                                    placeholder="Keterangan diskon / catatan transaksi..."
                                    class="w-full rounded-xl border border-slate-300 bg-white p-2.5 text-xs text-slate-800 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 resize-none"></textarea>
                            </div>

                            {{-- Actions CTAs --}}
                            <div class="pt-2 space-y-2">
                                <button
                                    type="submit"
                                    id="btnSimpanPembayaran"
                                    class="w-full flex items-center justify-center gap-2 bg-primary hover:bg-[#005a3c] text-white py-3 px-4 rounded-xl font-bold text-sm shadow-sm hover:shadow transition-all active:scale-[0.98]">
                                    <span class="material-symbols-outlined text-lg">payments</span>
                                    <span>Simpan & Selesaikan Pembayaran</span>
                                </button>

                                <button
                                    type="button"
                                    id="btnCetakNota"
                                    onclick="bukaModalNota()"
                                    class="w-full flex items-center justify-center gap-2 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 py-2.5 px-4 rounded-xl font-semibold text-xs transition-colors shadow-xs active:scale-[0.98]">
                                    <span class="material-symbols-outlined text-base text-slate-500">receipt</span>
                                    <span>Lihat & Cetak Nota Struk</span>
                                </button>
                            </div>

                        </div>
                    </div>

                    {{-- Subtle Notice Box --}}
                    <div class="bg-[#E8F0FE]/70 border border-[#1A73E8]/20 rounded-xl p-3.5 text-slate-700 text-xs">
                        <div class="flex gap-2.5">
                            <span class="material-symbols-outlined text-[#1A73E8] shrink-0 text-lg">info</span>
                            <div class="leading-relaxed">
                                <span class="font-bold text-slate-900 block mb-0.5">Otomatis Terintegrasi</span>
                                Saat pembayaran disimpan, status kunjungan pasien otomatis diperbarui menjadi <b>Selesai</b>.
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </form>


    {{-- ================= RIWAYAT TRANSAKSI PEMBAYARAN ================= --}}
    <div class="mt-2">
        <div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-outline-variant flex flex-col md:flex-row md:items-center md:justify-between gap-3 bg-white">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-[#E5F5F0] text-primary flex items-center justify-center text-xs font-bold">
                            <span class="material-symbols-outlined text-sm">history</span>
                        </span>
                        <h3 class="text-base font-bold text-on-surface">Riwayat Transaksi Pembayaran</h3>
                    </div>
                    <p class="text-xs text-on-surface-variant mt-0.5">
                        Daftar transaksi kasir yang telah berhasil diselesaikan.
                    </p>
                </div>

                <div class="relative w-full md:w-72">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none">
                        search
                    </span>
                    <input
                        type="text"
                        id="searchRiwayatInput"
                        oninput="filterRiwayat(this.value)"
                        placeholder="Cari pasien / no. transaksi..."
                        class="w-full pl-9 pr-3.5 py-2 rounded-xl border border-slate-300 bg-slate-50 hover:bg-white text-xs text-slate-800 focus:outline-none focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all">
                </div>
            </div>

            <div class="overflow-x-auto w-full">
                <table class="w-full text-left border-collapse min-w-[700px]" id="tabelRiwayat">
                    <thead class="bg-[#F8FAFC] text-xs text-slate-600 font-semibold border-b border-outline-variant">
                        <tr>
                            <th class="px-4 py-3">No. Trx</th>
                            <th class="px-4 py-3">Tanggal & Waktu</th>
                            <th class="px-4 py-3">Pasien</th>
                            <th class="px-4 py-3">Rincian Tagihan</th>
                            <th class="px-4 py-3">Metode</th>
                            <th class="px-4 py-3 text-right">Total Bayar</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 text-xs sm:text-sm" id="bodyTabelRiwayat">
                        @forelse ($riwayatPembayaran as $bayar)
                            @php
                                $pasienBayar = $bayar->pendaftaran ? $bayar->pendaftaran->pasien : null;
                                $rincianObatList = is_string($bayar->rincian_obat) ? json_decode($bayar->rincian_obat, true) : $bayar->rincian_obat;
                                $itemCount = is_array($rincianObatList) ? count($rincianObatList) : 0;
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors riwayat-row" data-search="{{ $pasienBayar ? strtolower($pasienBayar->nama_lengkap . ' ' . $pasienBayar->nik . ' ' . $pasienBayar->no_rm) : '' }} trx-{{ $bayar->id_pembayaran }}">
                                <td class="px-4 py-3 font-mono font-bold text-slate-700 text-xs">
                                    #TRX-{{ str_pad($bayar->id_pembayaran, 4, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-4 py-3 text-xs text-slate-500">
                                    {{ $bayar->formatted_tgl }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($pasienBayar)
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-full bg-[#E5F5F0] text-primary flex items-center justify-center text-[10px] font-bold shrink-0">
                                                {{ $pasienBayar->initials }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold text-slate-800 truncate">{{ $pasienBayar->nama_lengkap }}</p>
                                                <p class="text-[11px] text-slate-400 truncate">{{ $pasienBayar->no_rm }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400">Pasien Tidak Ditemukan</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs text-slate-600">
                                    @if($itemCount > 0)
                                        <span class="inline-flex items-center gap-1 font-medium text-primary bg-[#E5F5F0] px-2 py-0.5 rounded-md text-[11px]">
                                            <span class="material-symbols-outlined text-[13px]">medication</span>
                                            <span>{{ $itemCount }} item</span>
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-xs">Obat: Rp {{ number_format($bayar->biaya_obat, 0, ',', '.') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs uppercase font-semibold text-slate-700">
                                    <span class="inline-block px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[11px]">
                                        {{ $bayar->metode_pembayaran ?: 'Tunai' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-xs font-bold text-primary text-right">
                                    {{ $bayar->formatted_total_bayar }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#E5F5F0] text-primary border border-primary/20">
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
                                        class="p-1.5 text-slate-500 hover:text-primary hover:bg-[#E5F5F0] rounded-lg transition-colors"
                                        title="Cetak Nota Pembayaran">
                                        <span class="material-symbols-outlined text-base">receipt</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyRiwayatRow">
                                <td colspan="8" class="p-8 text-center text-sm text-slate-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="material-symbols-outlined text-slate-300 text-3xl mb-1">receipt_long</span>
                                        <h4 class="font-semibold text-slate-700 text-xs sm:text-sm">Belum Ada Riwayat Transaksi</h4>
                                        <p class="text-xs text-slate-400 mt-0.5">Transaksi yang telah Anda simpan akan tampil di sini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Controls for Riwayat Transaksi --}}
            <div id="riwayatPaginationContainer" class="p-3.5 sm:p-4 border-t border-outline-variant flex flex-col sm:flex-row items-center justify-between gap-3 bg-white">
                <span id="riwayatPaginationInfo" class="text-xs sm:text-sm text-on-surface-variant text-center sm:text-left">
                    Menampilkan 1–{{ min(5, count($riwayatPembayaran)) }} dari {{ count($riwayatPembayaran) }} transaksi
                </span>

                <div class="flex items-center gap-1 overflow-x-auto max-w-full pb-1 sm:pb-0" id="riwayatPaginationNav">
                    {{-- Diisi secara dinamis oleh JavaScript --}}
                </div>
            </div>
        </div>
    </div>

</div>


{{-- ================= MODAL CETAK NOTA / STRUK THERMAL ================= --}}
<div id="modalNota" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-3 sm:p-4 hidden">
    <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-md overflow-hidden flex flex-col max-h-[92vh]">
        
        {{-- Modal Header --}}
        <div class="p-3.5 sm:p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between gap-2">
            <div class="flex items-center gap-2 min-w-0">
                <span class="material-symbols-outlined text-primary text-xl shrink-0">receipt_long</span>
                <div class="truncate">
                    <h3 class="font-bold text-slate-900 text-sm sm:text-base leading-tight">Nota Pembayaran</h3>
                    <p class="text-[11px] text-slate-500 truncate">Format Struk Thermal Mandalacare</p>
                </div>
            </div>
            
            {{-- Ukuran Kertas Selector --}}
            <div class="flex items-center gap-2 shrink-0">
                <div class="flex items-center bg-slate-200/90 p-0.5 rounded-lg text-xs">
                    <button type="button" id="btnSize80" onclick="setUkuranNota('80mm')" 
                        class="px-2.5 py-1 rounded-md font-bold text-xs transition-all bg-white text-primary shadow-xs flex items-center gap-1">
                        <span>80mm</span>
                        <span class="text-[10px] opacity-75 font-normal hidden sm:inline">(Standar)</span>
                    </button>
                    <button type="button" id="btnSize58" onclick="setUkuranNota('58mm')" 
                        class="px-2.5 py-1 rounded-md font-semibold text-xs transition-all text-slate-600 hover:text-slate-900 flex items-center gap-1">
                        <span>58mm</span>
                        <span class="text-[10px] opacity-75 font-normal hidden sm:inline">(Mini)</span>
                    </button>
                </div>

                <button type="button" onclick="tutupModalNota()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-200/60 transition-colors">
                    <span class="material-symbols-outlined text-lg">close</span>
                </button>
            </div>
        </div>

        {{-- Printable Receipt Content in Realistic Paper Wrapper --}}
        <div class="p-4 sm:p-6 bg-slate-100/90 overflow-y-auto flex-1 flex justify-center items-start">
            <div id="notaPaperWrapper" class="bg-white border border-slate-300 shadow-sm rounded-sm p-4 sm:p-5 w-full max-w-[340px] text-slate-900 transition-all duration-300">
                <div id="printArea" class="bg-white text-slate-900 font-sans">
                    
                    {{-- Header Nota / Kop Klinik --}}
                    <div class="text-center pb-2">
                        <h2 class="clinic-title text-base sm:text-lg font-extrabold text-primary tracking-tight leading-tight">KLINIK MANDALACARE</h2>
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
                            <span id="notaTotalBayar" class="text-primary font-extrabold text-base">Rp 0</span>
                        </div>
                        <div class="total-row flex justify-between text-slate-600">
                            <span>Uang Diterima:</span>
                            <span id="notaUangDibayar" class="val font-semibold text-slate-900">Rp 0</span>
                        </div>
                        <div class="total-row flex justify-between text-slate-600">
                            <span>Kembalian:</span>
                            <span id="notaKembalian" class="val font-bold text-primary">Rp 0</span>
                        </div>
                    </div>

                    <div class="dashed-sep border-t border-dashed border-slate-400 my-2"></div>

                    {{-- Footer --}}
                    <div class="footer text-center text-[10px] text-slate-500 pt-1 leading-relaxed">
                        <p class="lunas-badge font-bold text-primary text-[11px] mb-0.5">*** LUNAS ***</p>
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
                <span>Pilih <b>Margin: None</b> pada dialog print browser.</span>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                <button type="button" onclick="tutupModalNota()" class="flex-1 sm:flex-none px-3.5 py-2 rounded-xl border border-slate-300 text-slate-700 text-xs font-semibold hover:bg-slate-100 transition-colors">
                    Tutup
                </button>
                <button type="button" onclick="printNota()" class="flex-1 sm:flex-none px-4 py-2 rounded-xl bg-primary text-white text-xs font-bold hover:bg-[#005a3c] transition-all flex items-center justify-center gap-1.5 shadow-sm active:scale-95">
                    <span class="material-symbols-outlined text-base">print</span>
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

// ================= METODE PEMBAYARAN VISUAL SELECTOR =================
function pilihMetode(metode) {
    const inputMetode = document.getElementById('inputMetode');
    if (inputMetode) inputMetode.value = metode;

    document.querySelectorAll('.metode-btn').forEach(btn => {
        const isCurrent = btn.dataset.metode === metode;
        if (isCurrent) {
            btn.className = 'metode-btn flex items-center gap-2 p-2.5 rounded-xl border border-primary bg-[#E5F5F0] text-primary font-semibold text-xs transition-all shadow-xs text-left';
            const icon = btn.querySelector('.material-symbols-outlined');
            if (icon) icon.className = 'material-symbols-outlined text-lg text-primary';
        } else {
            btn.className = 'metode-btn flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs transition-all shadow-xs text-left';
            const icon = btn.querySelector('.material-symbols-outlined');
            if (icon) icon.className = 'material-symbols-outlined text-lg text-slate-500';
        }
    });
}

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
    const dropdown = document.getElementById('pasienDropdownList');
    if (dropdown) dropdown.classList.add('hidden');
    
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
        document.getElementById('cardSub').innerHTML     = `RM: <span class="text-primary font-semibold">${p.no_rm}</span> &bull; NIK: ${p.nik} &bull; Umur: ${p.age} &bull; JK: ${p.formatted_jk}`;
        document.getElementById('pasienSearchInput').value = p.nama_lengkap;
        
        const badgeKunjungan = document.getElementById('badgeStatusKunjungan');
        if (badgeKunjungan) {
            badgeKunjungan.textContent = p.status_kunjungan || 'Menunggu';
            if (sudahLunas) {
                badgeKunjungan.className = 'text-[11px] font-semibold px-2 py-0.5 rounded-full bg-[#E5F5F0] text-primary border border-primary/20';
            } else {
                badgeKunjungan.className = 'text-[11px] font-semibold px-2 py-0.5 rounded-full bg-blue-100 text-blue-800';
            }
        }

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
            if (bayar.metode_pembayaran) {
                pilihMetode(bayar.metode_pembayaran);
            }
            if (bayar.uang_dibayar) document.getElementById('inputUangDibayar').value = formatRupiahString(bayar.uang_dibayar);
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
    showToast('Resep Disalin', `${addedCount} item obat ditambahkan. Silakan lengkapi harga obat.`, 'success');
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

// ================= FORMAT RUPIAH & CURRENCY INPUT HELPERS =================
function formatRupiahString(val) {
    if (val === null || val === undefined || val === '') return '';
    const cleanNumber = String(val).replace(/\D/g, '');
    if (!cleanNumber) return '';
    return new Intl.NumberFormat('id-ID').format(cleanNumber);
}

function parseRupiahNumber(val) {
    if (val === null || val === undefined || val === '') return 0;
    const cleanNumber = String(val).replace(/\D/g, '');
    return cleanNumber ? parseInt(cleanNumber, 10) : 0;
}

function formatCurrencyInput(inputElement) {
    const rawVal = inputElement.value || '';
    if (!rawVal) {
        inputElement.value = '';
        return;
    }

    const cursorPosition = inputElement.selectionStart || rawVal.length;
    
    // Hitung berapa digit angka murni di sebelah kiri kursor sebelum pemformatan
    const leftText = rawVal.substring(0, cursorPosition);
    const digitsBeforeCursor = leftText.replace(/\D/g, '').length;

    const rawNumber = parseRupiahNumber(rawVal);
    const formatted = rawNumber > 0 ? formatRupiahString(rawNumber) : '';
    inputElement.value = formatted;

    // Pertahankan posisi kursor secara presisi setelah penambahan/pengurangan titik
    if (formatted.length > 0) {
        let newCursorPos = 0;
        let countedDigits = 0;
        for (let i = 0; i < formatted.length; i++) {
            if (/\d/.test(formatted[i])) {
                countedDigits++;
            }
            if (countedDigits === digitsBeforeCursor) {
                newCursorPos = i + 1;
                break;
            }
        }
        if (newCursorPos === 0 && digitsBeforeCursor === 0) {
            newCursorPos = 0;
        } else if (countedDigits < digitsBeforeCursor) {
            newCursorPos = formatted.length;
        }
        inputElement.setSelectionRange(newCursorPos, newCursorPos);
    }
}

function handleHargaItemInput(index, inputElement) {
    formatCurrencyInput(inputElement);
    const numericValue = parseRupiahNumber(inputElement.value);
    updateItem(index, 'harga', numericValue);
}

function handleUangDibayarInput(inputElement) {
    formatCurrencyInput(inputElement);
    hitungKembalian();
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
                        <p class="font-medium text-slate-500">Belum ada rincian obat atau tindakan.</p>
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
                <td class="px-3 py-2">
                    <select onchange="updateItem(${idx}, 'kategori', this.value)" class="text-xs font-semibold rounded-lg border border-slate-200 px-2 py-1.5 focus:outline-none focus:border-primary ${isObat ? 'bg-emerald-50 text-emerald-800' : 'bg-blue-50 text-blue-800'}">
                        <option value="Obat" ${isObat ? 'selected' : ''}>Obat</option>
                        <option value="Tindakan" ${!isObat ? 'selected' : ''}>Tindakan</option>
                    </select>
                </td>
                <td class="px-3 py-2">
                    <input
                        type="text"
                        list="listObatPopuler"
                        placeholder="Nama obat / layanan..."
                        value="${escapeHtml(item.nama || '')}"
                        oninput="updateItem(${idx}, 'nama', this.value)"
                        class="w-full text-xs sm:text-sm font-medium text-slate-800 bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20">
                </td>
                <td class="px-3 py-2 text-center">
                    <input
                        type="number"
                        min="1"
                        value="${item.jumlah || 1}"
                        oninput="updateItem(${idx}, 'jumlah', this.value)"
                        class="w-14 text-center text-xs sm:text-sm font-bold text-slate-800 bg-white border border-slate-200 rounded-lg px-1 py-1.5 focus:outline-none focus:border-primary">
                </td>
                <td class="px-3 py-2 text-right">
                    <div class="relative inline-block w-full max-w-[130px]">
                        <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-xs text-slate-400 font-medium">Rp</span>
                        <input
                            type="text"
                            inputmode="numeric"
                            placeholder="0"
                            value="${item.harga ? formatRupiahString(item.harga) : ''}"
                            oninput="handleHargaItemInput(${idx}, this)"
                            class="w-full text-right text-xs sm:text-sm font-semibold text-slate-800 bg-white border border-slate-200 rounded-lg pl-7 pr-2 py-1.5 focus:outline-none focus:border-primary">
                    </div>
                </td>
                <td class="px-3 py-2 text-right font-bold text-xs sm:text-sm text-slate-900" id="subtotal-${idx}">
                    Rp ${formatRupiah(item.subtotal || 0)}
                </td>
                <td class="px-3 py-2 text-center">
                    <button type="button" onclick="hapusBaris(${idx})" class="p-1 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus baris">
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
    const uangDibayar = parseRupiahNumber(inputUang.value);

    const kembalian = uangDibayar - grandTotal;
    const displayKembalian = document.getElementById('displayKembalian');
    const statusBadge = document.getElementById('badgeKembalianStatus');
    const container = document.getElementById('kembalianContainer');
    const hiddenKembalian = document.getElementById('hidden_kembalian');

    hiddenKembalian.value = Math.max(0, kembalian);

    if (kembalian === 0) {
        statusBadge.textContent = 'Pas';
        statusBadge.className = 'text-[11px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800';
        displayKembalian.textContent = 'Rp 0';
        displayKembalian.className = 'text-xl font-extrabold text-slate-800 mt-1';
        if (container) container.className = 'p-3.5 rounded-xl border border-slate-200 bg-slate-50/90 transition-all';
    } else if (kembalian > 0) {
        statusBadge.textContent = 'Kembalian';
        statusBadge.className = 'text-[11px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800';
        displayKembalian.textContent = 'Rp ' + formatRupiah(kembalian);
        displayKembalian.className = 'text-xl font-extrabold text-primary mt-1';
        if (container) container.className = 'p-3.5 rounded-xl border border-primary/30 bg-[#E5F5F0]/40 transition-all';
    } else {
        statusBadge.textContent = 'Kurang Rp ' + formatRupiah(Math.abs(kembalian));
        statusBadge.className = 'text-[11px] font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-700';
        displayKembalian.textContent = '- Rp ' + formatRupiah(Math.abs(kembalian));
        displayKembalian.className = 'text-xl font-extrabold text-red-600 mt-1';
        if (container) container.className = 'p-3.5 rounded-xl border border-red-200 bg-red-50/40 transition-all';
    }
}

function setUangPas() {
    const totalObat = parseFloat(document.getElementById('hidden_biaya_obat').value) || 0;
    const totalTindakan = parseFloat(document.getElementById('hidden_biaya_tindakan').value) || 0;
    const grandTotal = totalObat + totalTindakan;
    document.getElementById('inputUangDibayar').value = formatRupiahString(grandTotal) || '0';
    hitungKembalian();
}

function setQuickCash(amount) {
    document.getElementById('inputUangDibayar').value = formatRupiahString(amount);
    hitungKembalian();
}

// ================= SIMPAN PEMBAYARAN VIA AJAX =================
function setStatusLunasUI(sudahLunas) {
    const btn = document.getElementById('btnSimpanPembayaran');
    if (!btn) return;

    if (sudahLunas) {
        btn.disabled = true;
        btn.classList.add('opacity-60', 'cursor-not-allowed');
        btn.innerHTML = '<span class="material-symbols-outlined text-[18px]">verified</span><span>Sudah Lunas</span>';
    } else {
        btn.disabled = false;
        btn.classList.remove('opacity-60', 'cursor-not-allowed');
        btn.innerHTML = '<span class="material-symbols-outlined text-[18px]">payments</span><span>Simpan & Selesaikan Pembayaran</span>';
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
    const uangDibayar = parseRupiahNumber(document.getElementById('inputUangDibayar').value) || 0;

    if (uangDibayar < grandTotal) {
        showToast('Uang Pembayaran Kurang', `Uang yang dibayarkan (Rp ${formatRupiah(uangDibayar)}) kurang dari total tagihan (Rp ${formatRupiah(grandTotal)}).`, 'error');
        return;
    }

    const btn = document.getElementById('btnSimpanPembayaran');
    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined text-[18px] animate-spin">autorenew</span><span>Menyimpan Pembayaran...</span>';

    document.getElementById('hidden_rincian_obat').value = JSON.stringify(daftarItems);

    const formData = new FormData(document.getElementById('formPembayaran'));
    formData.set('uang_dibayar', uangDibayar);

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
        btn.innerHTML = '<span class="material-symbols-outlined text-[18px]">payments</span><span>Simpan & Selesaikan Pembayaran</span>';

        if (data.success) {
            showToast('Pembayaran Berhasil!', data.message, 'success');

            const badgeStatus = document.getElementById('badgeStatusKunjungan');
            if (badgeStatus) {
                badgeStatus.textContent = 'Selesai';
                badgeStatus.className = 'text-[11px] font-semibold px-2 py-0.5 rounded-full bg-[#E5F5F0] text-primary border border-primary/20';
            }

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
        btn.innerHTML = '<span class="material-symbols-outlined text-[18px]">payments</span><span>Simpan & Selesaikan Pembayaran</span>';
        showToast('Terjadi Kesalahan', 'Gagal terhubung ke server. Periksa jaringan Anda.', 'error');
    });
}

// ================= MODAL NOTA & PRINT =================
function bukaModalNota() {
    const idPasien = document.getElementById('hidden_id_pasien').value;
    if (!idPasien) {
        showToast('Belum Memilih Pasien', 'Pilih pasien dan isi rincian tagihan terlebih dahulu.', 'warning');
        return;
    }

    const totalObat = parseFloat(document.getElementById('hidden_biaya_obat').value) || 0;
    const totalTindakan = parseFloat(document.getElementById('hidden_biaya_tindakan').value) || 0;
    const grandTotal = totalObat + totalTindakan;
    const uangDibayar = parseRupiahNumber(document.getElementById('inputUangDibayar').value) || grandTotal;
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
        if (btn58) btn58.className = 'px-2.5 py-1 rounded-md font-bold text-xs transition-all bg-white text-primary shadow-xs flex items-center gap-1';
        if (btn80) btn80.className = 'px-2.5 py-1 rounded-md font-semibold text-xs transition-all text-slate-600 hover:text-slate-900 flex items-center gap-1';
        if (wrapper) {
            wrapper.style.maxWidth = '250px';
        }
    } else {
        if (btn80) btn80.className = 'px-2.5 py-1 rounded-md font-bold text-xs transition-all bg-white text-primary shadow-xs flex items-center gap-1';
        if (btn58) btn58.className = 'px-2.5 py-1 rounded-md font-semibold text-xs transition-all text-slate-600 hover:text-slate-900 flex items-center gap-1';
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
            color: #006c49;
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
            color: #006c49;
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
            color: #006c49;
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

// ================= PAGINATION & LIVE FILTER RIWAYAT TRANSAKSI =================
const ITEMS_PER_PAGE_RIWAYAT = 5;
let currentPageRiwayat = 1;

function renderRiwayatPagination() {
    const input = document.getElementById('searchRiwayatInput');
    const query = (input ? input.value : '').toLowerCase().trim();
    const rows = Array.from(document.querySelectorAll('.riwayat-row'));

    if (rows.length === 0) {
        const container = document.getElementById('riwayatPaginationContainer');
        if (container) container.classList.add('hidden');
        return;
    }

    // 1. Filter baris yang cocok dengan kata kunci pencarian
    const matchingRows = rows.filter(r => {
        const text = (r.dataset.search || '').toLowerCase();
        return text.includes(query);
    });

    const totalItems = matchingRows.length;
    const totalPages = Math.ceil(totalItems / ITEMS_PER_PAGE_RIWAYAT) || 1;

    if (currentPageRiwayat > totalPages) {
        currentPageRiwayat = totalPages;
    }
    if (currentPageRiwayat < 1) {
        currentPageRiwayat = 1;
    }

    // 2. Sembunyikan semua baris, lalu tampilkan hanya maksimal 5 baris di halaman aktif
    rows.forEach(r => r.style.display = 'none');

    const startIndex = (currentPageRiwayat - 1) * ITEMS_PER_PAGE_RIWAYAT;
    const endIndex = Math.min(startIndex + ITEMS_PER_PAGE_RIWAYAT, totalItems);

    for (let i = startIndex; i < endIndex; i++) {
        if (matchingRows[i]) {
            matchingRows[i].style.display = '';
        }
    }

    // 3. Tampilkan pesan kosong jika pencarian tidak menemukan hasil
    let noSearchRow = document.getElementById('noSearchMatchRow');
    if (totalItems === 0 && query.length > 0) {
        if (!noSearchRow) {
            const tbody = document.getElementById('bodyTabelRiwayat');
            noSearchRow = document.createElement('tr');
            noSearchRow.id = 'noSearchMatchRow';
            noSearchRow.innerHTML = `
                <td colspan="8" class="p-8 text-center text-sm text-slate-400">
                    <div class="flex flex-col items-center justify-center">
                        <span class="material-symbols-outlined text-slate-300 text-3xl mb-1">search_off</span>
                        <h4 class="font-semibold text-slate-700 text-xs sm:text-sm">Tidak Ada Transaksi Ditemukan</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Tidak ditemukan transaksi yang cocok dengan kata kunci pencarian.</p>
                    </div>
                </td>
            `;
            tbody.appendChild(noSearchRow);
        } else {
            noSearchRow.style.display = '';
        }
    } else if (noSearchRow) {
        noSearchRow.style.display = 'none';
    }

    // 4. Update Teks Info Pagination
    const infoEl = document.getElementById('riwayatPaginationInfo');
    const containerEl = document.getElementById('riwayatPaginationContainer');
    if (containerEl) containerEl.classList.remove('hidden');

    if (infoEl) {
        if (totalItems === 0) {
            infoEl.textContent = 'Menampilkan 0 transaksi';
        } else {
            infoEl.textContent = `Menampilkan ${startIndex + 1}–${endIndex} dari ${totalItems} transaksi`;
        }
    }

    // 5. Render Tombol Navigasi Pagination
    const navEl = document.getElementById('riwayatPaginationNav');
    if (!navEl) return;

    if (totalPages <= 1) {
        navEl.innerHTML = `
            <span class="w-8 h-8 flex items-center justify-center rounded bg-primary text-white font-medium text-xs shrink-0 shadow-xs">1</span>
        `;
        return;
    }

    let navHtml = '';

    // Tombol Sebelumnya (<)
    if (currentPageRiwayat === 1) {
        navHtml += `
            <button type="button" class="w-8 h-8 flex items-center justify-center rounded text-on-surface-variant/40 cursor-not-allowed shrink-0" disabled aria-label="Sebelumnya">
                <span class="material-symbols-outlined text-sm">chevron_left</span>
            </button>
        `;
    } else {
        navHtml += `
            <button type="button" onclick="goToRiwayatPage(${currentPageRiwayat - 1})" class="w-8 h-8 flex items-center justify-center rounded text-on-surface hover:bg-surface-container-low transition-colors shrink-0" title="Halaman Sebelumnya" aria-label="Sebelumnya">
                <span class="material-symbols-outlined text-sm">chevron_left</span>
            </button>
        `;
    }

    // Nomor Halaman (1, 2, 3...)
    for (let p = 1; p <= totalPages; p++) {
        if (p === currentPageRiwayat) {
            navHtml += `
                <span class="w-8 h-8 flex items-center justify-center rounded bg-primary text-white font-medium text-xs shrink-0 shadow-xs">
                    ${p}
                </span>
            `;
        } else {
            navHtml += `
                <button type="button" onclick="goToRiwayatPage(${p})" class="w-8 h-8 flex items-center justify-center rounded text-on-surface hover:bg-surface-container-low transition-colors text-xs font-medium shrink-0" title="Halaman ${p}">
                    ${p}
                </button>
            `;
        }
    }

    // Tombol Selanjutnya (>)
    if (currentPageRiwayat === totalPages) {
        navHtml += `
            <button type="button" class="w-8 h-8 flex items-center justify-center rounded text-on-surface-variant/40 cursor-not-allowed shrink-0" disabled aria-label="Selanjutnya">
                <span class="material-symbols-outlined text-sm">chevron_right</span>
            </button>
        `;
    } else {
        navHtml += `
            <button type="button" onclick="goToRiwayatPage(${currentPageRiwayat + 1})" class="w-8 h-8 flex items-center justify-center rounded text-on-surface hover:bg-surface-container-low transition-colors shrink-0" title="Halaman Selanjutnya" aria-label="Selanjutnya">
                <span class="material-symbols-outlined text-sm">chevron_right</span>
            </button>
        `;
    }

    navEl.innerHTML = navHtml;
}

function goToRiwayatPage(page) {
    currentPageRiwayat = page;
    renderRiwayatPagination();
}

function filterRiwayat(query) {
    currentPageRiwayat = 1;
    renderRiwayatPagination();
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
        bar.style.background = '#006c49';
        ic.style.color = '#006c49';
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
document.addEventListener('DOMContentLoaded', () => {
    renderRiwayatPagination();
});

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