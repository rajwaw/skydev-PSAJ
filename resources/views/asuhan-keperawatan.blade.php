@extends('layouts.app')

@section('title', 'Asuhan Keperawatan - Mandalacare')

@section('content')

{{-- ========================================================== --}}
{{-- NOTIFIKASI TOAST (AKSEN WARNA HIJAU MANDALACARE) --}}
{{-- ========================================================== --}}
<div id="toastNotification" class="fixed top-6 left-1/2 -translate-x-1/2 z-50 w-[92%] max-w-lg pointer-events-none transition-all duration-300 transform -translate-y-16 opacity-0 hidden">
    <div id="toastCard" class="pointer-events-auto bg-white border-2 border-primary/40 rounded-2xl shadow-2xl p-4 md:p-5 flex items-start gap-4 backdrop-blur-md bg-white/95 relative overflow-hidden">
        <!-- Green Accent Bar on the Left -->
        <div id="toastAccentBar" class="absolute left-0 top-0 bottom-0 w-2 bg-primary"></div>
        
        <!-- Green Icon Badge -->
        <div id="toastIconContainer" class="w-11 h-11 rounded-xl bg-[#E5F5F0] text-primary flex items-center justify-center flex-shrink-0 shadow-sm">
            <span id="toastIcon" class="material-symbols-outlined text-2xl font-bold">check_circle</span>
        </div>

        <!-- Content -->
        <div class="flex-1 min-w-0 pr-2">
            <div class="flex items-center gap-2 mb-1">
                <span id="toastBadge" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#E5F5F0] text-primary">
                    Asuhan Keperawatan Tersimpan
                </span>
                <span class="text-xs text-on-surface-variant font-medium">Baru saja</span>
            </div>
            
            <h4 class="text-base font-bold text-on-surface" id="toastTitle">
                Data Berhasil Disimpan!
            </h4>
            
            <p class="text-sm text-on-surface-variant mt-1" id="toastMessage">
                Asuhan keperawatan dan pengkajian pasien telah tersimpan di database.
            </p>

            <!-- Metadata Badges & Link to Rekam Medis -->
            <div id="toastMeta" class="mt-3 flex flex-wrap items-center gap-2 pt-2 border-t border-outline-variant/60">
                <div class="flex items-center gap-1.5 bg-[#E5F5F0] px-3 py-1 rounded-lg border border-primary/20 text-xs">
                    <span class="text-on-surface-variant font-medium">Pasien:</span>
                    <span class="font-bold text-primary text-sm" id="toastPasienName">{{ $selectedPasien ? $selectedPasien->nama_lengkap : '-' }}</span>
                </div>
                <a id="toastLinkRM" href="{{ $selectedPasien ? route('rekam-medis', ['id' => $selectedPasien->id_pasien]) : route('rekam-medis') }}" class="ml-auto inline-flex items-center gap-1 text-xs font-semibold text-primary hover:underline">
                    Buka Rekam Medis
                    <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                </a>
            </div>
        </div>

        <!-- Close Button -->
        <button type="button" onclick="hideToastNotification()" class="text-on-surface-variant hover:text-on-surface p-1 rounded-lg hover:bg-surface-container transition-colors flex-shrink-0" title="Tutup Notifikasi">
            <span class="material-symbols-outlined text-lg">close</span>
        </button>

        <!-- Auto-dismiss Progress Bar -->
        <div id="toastProgress" class="absolute bottom-0 left-0 h-1 bg-primary w-full transition-all linear"></div>
    </div>
</div>

<div class="p-4 sm:p-6 md:p-8 lg:p-10 w-full max-w-7xl mx-auto flex-1 flex flex-col gap-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-on-surface tracking-tight">
                Asuhan Keperawatan
            </h1>
            <p class="text-sm sm:text-base text-on-surface-variant mt-1">
                Pilih pasien dari database dan kelola pengkajian serta rencana asuhan secara terstruktur.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('pendaftaran') }}" class="bg-surface border border-outline-variant hover:border-primary text-on-surface px-4 py-2 rounded-xl text-sm font-semibold transition-colors inline-flex items-center gap-1.5 shadow-sm">
                <span class="material-symbols-outlined text-base text-primary">person_add</span>
                Pasien Baru
            </a>
            <a id="btnTopRekamMedis" href="{{ $selectedPasien ? route('rekam-medis', ['id' => $selectedPasien->id_pasien]) : route('rekam-medis') }}" class="bg-primary text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-[#005a3c] transition-colors inline-flex items-center gap-1.5 shadow-sm">
                <span class="material-symbols-outlined text-base">folder_open</span>
                Lihat Rekam Medis
            </a>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start">

        <!-- LEFT COLUMN (FORM ASUHAN) -->
        <div class="xl:col-span-2 flex flex-col gap-6">

            <!-- ===================================== -->
            <!-- 1. PILIH PASIEN DARI DATABASE -->
            <!-- ===================================== -->
            <section class="bg-white rounded-xl border border-outline-variant p-5 sm:p-6 card-shadow">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg sm:text-xl font-bold text-on-surface flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-primary/10 text-primary flex items-center justify-center text-sm font-bold">1</span>
                        Pilih Pasien Terdaftar
                    </h3>
                    <span class="text-xs text-on-surface-variant bg-surface-container px-2.5 py-1 rounded-full font-medium">
                        {{ $daftarPasien->count() }} Pasien di Database
                    </span>
                </div>

                <!-- Search Input with Dropdown / Autocomplete -->
                <div class="relative mb-4">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant">
                        search
                    </span>
                    <input
                        id="pasienSearchInput"
                        type="text"
                        placeholder="Ketik nama pasien, NIK, atau nomor telepon..."
                        value="{{ $selectedPasien ? $selectedPasien->nama_lengkap : '' }}"
                        autocomplete="off"
                        onclick="showPasienDropdown(event)"
                        onfocus="showPasienDropdown(event)"
                        oninput="filterPasienDropdown(this.value)"
                        class="w-full bg-surface border border-outline-variant rounded-xl py-3 pl-11 pr-10 text-sm text-on-surface input-ring placeholder-on-surface-variant/60"
                    >
                    <button type="button" onclick="clearSearchPasien(event)" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface p-1 rounded-lg">
                        <span class="material-symbols-outlined text-sm">close</span>
                    </button>

                    <!-- Dropdown Results List -->
                    <div id="pasienDropdownList" class="absolute left-0 right-0 top-full mt-1.5 bg-white border border-outline-variant rounded-xl shadow-xl z-30 max-h-64 overflow-y-auto hidden">
                        @forelse ($daftarPasien as $p)
                            <div
                                class="pasien-dropdown-item flex items-center justify-between p-3.5 hover:bg-surface-container-low cursor-pointer border-b border-outline-variant/40 transition-colors"
                                data-id="{{ $p->id_pasien }}"
                                data-nama="{{ $p->nama_lengkap }}"
                                data-nik="{{ $p->nik }}"
                                data-rm="{{ $p->no_rm }}"
                                onclick="selectPasienById({{ $p->id_pasien }})"
                            >
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-[#E5F5F0] text-primary flex items-center justify-center text-xs font-bold shrink-0">
                                        {{ $p->initials }}
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-semibold text-on-surface">{{ $p->nama_lengkap }}</h4>
                                        <p class="text-xs text-on-surface-variant">NIK: {{ $p->nik }} &bull; {{ $p->no_rm }} &bull; {{ $p->formatted_jk }}</p>
                                    </div>
                                </div>
                                <span class="material-symbols-outlined text-primary text-sm">arrow_forward</span>
                            </div>
                        @empty
                            <div class="p-4 text-center text-xs text-on-surface-variant">
                                Belum ada data pasien. Silakan <a href="{{ route('pendaftaran') }}" class="text-primary underline font-semibold">daftarkan pasien baru</a> terlebih dahulu.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Selected Patient Active Card -->
                <div id="selectedPatientCard" class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 bg-surface-container-low rounded-xl border border-primary/20">
                    <div class="flex items-center gap-3.5">
                        <div id="cardAvatar" class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center text-primary font-bold text-base shrink-0">
                            {{ $selectedPasien ? $selectedPasien->initials : 'PS' }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 id="cardNama" class="font-bold text-on-surface text-base">
                                    {{ $selectedPasien ? $selectedPasien->nama_lengkap : 'Belum Memilih Pasien' }}
                                </h4>
                                <span class="bg-[#E5F5F0] text-primary text-[11px] font-bold px-2 py-0.5 rounded-md {{ $selectedPasien ? '' : 'hidden' }}" id="badgeTerpilih">
                                    Terpilih
                                </span>
                            </div>
                            <p id="cardSub" class="text-xs text-on-surface-variant mt-0.5">
                                @if($selectedPasien)
                                    NIK: {{ $selectedPasien->nik }} &bull; {{ $selectedPasien->no_rm }} &bull; {{ $selectedPasien->no_telp ?: 'Tidak ada no. telp' }}
                                @else
                                    Pilih pasien dari daftar untuk mengisi asuhan keperawatan
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 sm:self-center">
                        <button
                            type="button"
                            id="btnPilihPasien"
                            onclick="toggleOrFocusDropdown(event)"
                            class="bg-white border border-outline-variant hover:border-primary text-on-surface text-xs font-semibold px-3.5 py-2 rounded-lg transition-colors flex items-center gap-1.5 shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">swap_horiz</span>
                            <span>Pilih / Ganti Pasien</span>
                        </button>
                    </div>
                </div>
            </section>

            <!-- ===================================== -->
            <!-- 2. DATA IDENTITAS PASIEN TERPILIH -->
            <!-- ===================================== -->
            <section class="bg-white rounded-xl border border-outline-variant p-5 sm:p-6 card-shadow">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-outline-variant/60">
                    <h3 class="text-lg sm:text-xl font-bold text-on-surface flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-primary/10 text-primary flex items-center justify-center text-sm font-bold">2</span>
                        Data Pasien
                    </h3>
                    <a
                        id="linkLihatRM"
                        href="{{ $selectedPasien ? route('rekam-medis', ['id' => $selectedPasien->id_pasien]) : route('rekam-medis') }}"
                        class="text-secondary border border-secondary/30 px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-secondary/10 transition-colors flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[15px]">open_in_new</span>
                        Lihat Rekam Medis
                    </a>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-5 gap-3 sm:gap-4">
                    <div class="bg-surface p-3.5 rounded-xl border border-outline-variant/60">
                        <p class="text-xs text-on-surface-variant mb-1 font-medium flex items-center gap-1">
                            <span class="material-symbols-outlined text-[15px]">cake</span>
                            Tanggal Lahir
                        </p>
                        <p id="infoTglLahir" class="text-sm font-semibold text-on-surface">
                            {{ $selectedPasien ? $selectedPasien->formatted_tgl_lahir . ' (' . $selectedPasien->age . ')' : '-' }}
                        </p>
                    </div>

                    <div class="bg-surface p-3.5 rounded-xl border border-outline-variant/60">
                        <p class="text-xs text-on-surface-variant mb-1 font-medium flex items-center gap-1">
                            <span class="material-symbols-outlined text-[15px]">wc</span>
                            Jenis Kelamin
                        </p>
                        <p id="infoJk" class="text-sm font-semibold text-on-surface">
                            {{ $selectedPasien ? $selectedPasien->formatted_jk : '-' }}
                        </p>
                    </div>

                    <div class="bg-surface p-3.5 rounded-xl border border-outline-variant/60">
                        <p class="text-xs text-on-surface-variant mb-1 font-medium flex items-center gap-1">
                            <span class="material-symbols-outlined text-[15px]">bloodtype</span>
                            Golongan Darah
                        </p>
                        <p id="infoGolDarah" class="text-sm font-semibold text-red-600">
                            {{ $selectedPasien && $selectedPasien->golongan_darah ? $selectedPasien->golongan_darah : '-' }}
                        </p>
                    </div>

                    <div class="bg-surface p-3.5 rounded-xl border border-outline-variant/60">
                        <p class="text-xs text-on-surface-variant mb-1 font-medium flex items-center gap-1">
                            <span class="material-symbols-outlined text-[15px] text-amber-600">medication</span>
                            Alergi Obat
                        </p>
                        <p id="infoAlergi" class="text-sm font-bold text-amber-700">
                            {{ ($selectedPasien && $selectedPasien->alergis->isNotEmpty()) ? $selectedPasien->alergis->pluck('nama_obat')->join(', ') : 'Tidak Ada' }}
                        </p>
                    </div>

                    <div class="col-span-2 md:col-span-1 bg-surface p-3.5 rounded-xl border border-outline-variant/60">
                        <p class="text-xs text-on-surface-variant mb-1 font-medium flex items-center gap-1">
                            <span class="material-symbols-outlined text-[15px]">calendar_today</span>
                            Tgl Kunjungan
                        </p>
                        <p id="infoTglKunjungan" class="text-sm font-semibold text-on-surface">
                            @if($selectedPasien && $selectedPasien->pendaftaranTerbaru)
                                {{ \Carbon\Carbon::parse($selectedPasien->pendaftaranTerbaru->tgl_daftar)->translatedFormat('d M Y') }}
                            @else
                                {{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}
                            @endif
                        </p>
                    </div>
                </div>
            </section>

            <!-- ===================================== -->
            <!-- FORM ASUHAN KEPERAWATAN -->
            <!-- ===================================== -->
            <form id="formAsuhan" action="{{ route('asuhan-keperawatan.store') }}" method="POST" onsubmit="submitFormAsuhan(event)">
                @csrf
                <input type="hidden" name="id_pasien" id="hidden_id_pasien" value="{{ $selectedPasien ? $selectedPasien->id_pasien : '' }}">

                <!-- ===================================== -->
                <!-- 3. PENGKAJIAN PASIEN -->
                <!-- ===================================== -->
                <section class="bg-white rounded-xl border border-outline-variant p-5 sm:p-6 card-shadow mb-6">
                    <h3 class="text-lg sm:text-xl font-bold text-on-surface mb-5 flex items-center gap-2 pb-3 border-b border-outline-variant/60">
                        <span class="w-7 h-7 rounded-full bg-primary/10 text-primary flex items-center justify-center text-sm font-bold">3</span>
                        Pengkajian Pasien
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <!-- Keluhan Utama -->
                        <div>
                            <label for="inputKeluhanUtama" class="block text-sm font-semibold text-on-surface mb-2">
                                Keluhan Utama <span class="text-red-500">*</span>
                            </label>
                            <textarea
                                id="inputKeluhanUtama"
                                name="keluhan_utama"
                                required
                                placeholder="Deskripsikan keluhan utama pasien (misal: Demam tinggi 3 hari, batuk berdahak, nyeri tenggorokan)..."
                                class="w-full bg-surface border border-outline-variant rounded-xl p-3 text-sm text-on-surface input-ring min-h-[95px] resize-none"
                            >{{ $latestAsuhan ? $latestAsuhan->keluhan_utama : '' }}</textarea>
                        </div>

                        <!-- Riwayat Keluhan / Penyakit -->
                        <div>
                            <label for="inputRiwayatKeluhan" class="block text-sm font-semibold text-on-surface mb-2">
                                Riwayat Keluhan / Penyakit
                            </label>
                            <textarea
                                id="inputRiwayatKeluhan"
                                name="riwayat_keluhan"
                                placeholder="Riwayat penyakit terdahulu, pengobatan sebelumnya, riwayat kambuh..."
                                class="w-full bg-surface border border-outline-variant rounded-xl p-3 text-sm text-on-surface input-ring min-h-[95px] resize-none"
                            >{{ $latestAsuhan ? $latestAsuhan->riwayat_keluhan : '' }}</textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                        <!-- Kondisi Umum -->
                        <div>
                            <label for="inputKondisiUmum" class="block text-sm font-semibold text-on-surface mb-2">
                                Kondisi Umum
                            </label>
                            <select
                                id="inputKondisiUmum"
                                name="kondisi_umum"
                                class="w-full bg-surface border border-outline-variant rounded-xl p-3 text-sm text-on-surface input-ring">
                                <option value="Baik" {{ ($latestAsuhan && $latestAsuhan->kondisi_umum === 'Baik') ? 'selected' : '' }}>Baik</option>
                                <option value="Cukup" {{ ($latestAsuhan && $latestAsuhan->kondisi_umum === 'Cukup') ? 'selected' : '' }}>Cukup</option>
                                <option value="Lemah" {{ ($latestAsuhan && $latestAsuhan->kondisi_umum === 'Lemah') ? 'selected' : '' }}>Lemah</option>
                            </select>
                        </div>

                        <!-- Kesadaran -->
                        <div>
                            <label for="inputKesadaran" class="block text-sm font-semibold text-on-surface mb-2">
                                Kesadaran
                            </label>
                            <select
                                id="inputKesadaran"
                                name="kesadaran"
                                class="w-full bg-surface border border-outline-variant rounded-xl p-3 text-sm text-on-surface input-ring">
                                <option value="Compos Mentis" {{ ($latestAsuhan && $latestAsuhan->kesadaran === 'Compos Mentis') ? 'selected' : '' }}>Compos Mentis</option>
                                <option value="Apatis" {{ ($latestAsuhan && $latestAsuhan->kesadaran === 'Apatis') ? 'selected' : '' }}>Apatis</option>
                                <option value="Delirium" {{ ($latestAsuhan && $latestAsuhan->kesadaran === 'Delirium') ? 'selected' : '' }}>Delirium</option>
                                <option value="Somnolen" {{ ($latestAsuhan && $latestAsuhan->kesadaran === 'Somnolen') ? 'selected' : '' }}>Somnolen</option>
                                <option value="Sopor" {{ ($latestAsuhan && $latestAsuhan->kesadaran === 'Sopor') ? 'selected' : '' }}>Sopor</option>
                                <option value="Koma" {{ ($latestAsuhan && $latestAsuhan->kesadaran === 'Koma') ? 'selected' : '' }}>Koma</option>
                            </select>
                        </div>
                    </div>

                    <!-- TANDA VITAL -->
                    <h4 class="text-sm font-bold text-on-surface uppercase tracking-wider mb-3 flex items-center gap-1.5 text-primary">
                        <span class="material-symbols-outlined text-base">vital_signs</span>
                        Tanda-tanda Vital
                    </h4>

                    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                        <!-- TD -->
                        <div class="bg-surface-container-low/50 p-2.5 rounded-xl border border-outline-variant/60">
                            <label class="block text-xs font-semibold text-on-surface-variant mb-1">
                                TD (mmHg)
                            </label>
                            <input
                                id="inputTD"
                                type="text"
                                name="tekanan_darah"
                                value="{{ $latestAsuhan ? $latestAsuhan->tekanan_darah : '120/80' }}"
                                placeholder="120/80"
                                class="w-full bg-white border border-outline-variant rounded-lg p-2 text-center text-sm font-semibold text-on-surface input-ring">
                        </div>

                        <!-- Nadi -->
                        <div class="bg-surface-container-low/50 p-2.5 rounded-xl border border-outline-variant/60">
                            <label class="block text-xs font-semibold text-on-surface-variant mb-1">
                                Nadi (x/mnt)
                            </label>
                            <input
                                id="inputNadi"
                                type="number"
                                name="nadi"
                                value="{{ $latestAsuhan ? $latestAsuhan->nadi : '80' }}"
                                placeholder="80"
                                class="w-full bg-white border border-outline-variant rounded-lg p-2 text-center text-sm font-semibold text-on-surface input-ring">
                        </div>

                        <!-- Suhu -->
                        <div class="bg-surface-container-low/50 p-2.5 rounded-xl border border-outline-variant/60">
                            <label class="block text-xs font-semibold text-on-surface-variant mb-1">
                                Suhu (°C)
                            </label>
                            <input
                                id="inputSuhu"
                                type="number"
                                step="0.1"
                                name="suhu_tubuh"
                                value="{{ $latestAsuhan ? $latestAsuhan->suhu_tubuh : '36.5' }}"
                                placeholder="36.5"
                                class="w-full bg-white border border-outline-variant rounded-lg p-2 text-center text-sm font-semibold text-on-surface input-ring">
                        </div>

                        <!-- Pernapasan (RR) -->
                        <div class="bg-surface-container-low/50 p-2.5 rounded-xl border border-outline-variant/60">
                            <label class="block text-xs font-semibold text-on-surface-variant mb-1">
                                RR (x/mnt)
                            </label>
                            <input
                                id="inputRR"
                                type="number"
                                name="rr"
                                value="{{ $latestAsuhan ? $latestAsuhan->rr : '20' }}"
                                placeholder="20"
                                class="w-full bg-white border border-outline-variant rounded-lg p-2 text-center text-sm font-semibold text-on-surface input-ring">
                        </div>

                        <!-- SpO2 -->
                        <div class="col-span-2 sm:col-span-1 bg-surface-container-low/50 p-2.5 rounded-xl border border-outline-variant/60">
                            <label class="block text-xs font-semibold text-on-surface-variant mb-1">
                                SpO2 (%)
                            </label>
                            <input
                                id="inputSpO2"
                                type="number"
                                name="spo2"
                                value="{{ $latestAsuhan ? $latestAsuhan->spo2 : '98' }}"
                                placeholder="98"
                                class="w-full bg-white border border-outline-variant rounded-lg p-2 text-center text-sm font-semibold text-on-surface input-ring">
                        </div>
                    </div>
                </section>

                <!-- ===================================== -->
                <!-- 4. DIAGNOSIS KEPERAWATAN -->
                <!-- ===================================== -->
                <section class="bg-white rounded-xl border border-outline-variant p-5 sm:p-6 card-shadow mb-6">
                    <h3 class="text-lg sm:text-xl font-bold text-on-surface mb-5 flex items-center gap-2 pb-3 border-b border-outline-variant/60">
                        <span class="w-7 h-7 rounded-full bg-primary/10 text-primary flex items-center justify-center text-sm font-bold">4</span>
                        Diagnosis Keperawatan
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Masalah / Diagnosa Awal -->
                        <div>
                            <label for="inputDiagnosaAwal" class="block text-sm font-semibold text-on-surface mb-2">
                                Masalah / Diagnosa Awal
                            </label>
                            <textarea
                                id="inputDiagnosaAwal"
                                name="diagnosa_awal"
                                placeholder="Jelaskan masalah spesifik atau diagnosa keperawatan..."
                                class="w-full bg-surface border border-outline-variant rounded-xl p-3 text-sm text-on-surface input-ring min-h-[90px] resize-none"
                            >{{ ($latestIntervensi->isNotEmpty() && $latestIntervensi->first()->diagnosa_awal) ? $latestIntervensi->first()->diagnosa_awal : '' }}</textarea>
                        </div>

                        <!-- Faktor Terkait (Etiologi) -->
                        <div>
                            <label for="inputFaktorTerkait" class="block text-sm font-semibold text-on-surface mb-2">
                                Faktor Terkait (Etiologi)
                            </label>
                            <textarea
                                id="inputFaktorTerkait"
                                name="faktor_terkait"
                                placeholder="Penyebab, faktor risiko, atau pemicu kondisi..."
                                class="w-full bg-surface border border-outline-variant rounded-xl p-3 text-sm text-on-surface input-ring min-h-[90px] resize-none"
                            >{{ ($latestIntervensi->isNotEmpty() && $latestIntervensi->first()->faktor_terkait) ? $latestIntervensi->first()->faktor_terkait : '' }}</textarea>
                        </div>

                        <!-- Prioritas Diagnosa -->
                        <div class="md:col-span-2">
                            <label for="inputPrioritasDiagnosa" class="block text-sm font-semibold text-on-surface mb-2">
                                Prioritas Diagnosa &amp; Catatan Khusus
                            </label>
                            <textarea
                                id="inputPrioritasDiagnosa"
                                name="prioritas_diagnosa"
                                placeholder="Tentukan prioritas penanganan dan arahan khusus..."
                                class="w-full bg-surface border border-outline-variant rounded-xl p-3 text-sm text-on-surface input-ring min-h-[80px] resize-none"
                            >{{ ($latestIntervensi->isNotEmpty() && $latestIntervensi->first()->prioritas_diagnosa) ? $latestIntervensi->first()->prioritas_diagnosa : '' }}</textarea>
                        </div>
                    </div>
                </section>

                <!-- ===================================== -->
                <!-- 5. RENCANA ASUHAN (INTERVENSI) -->
                <!-- ===================================== -->
                <section class="bg-white rounded-xl border border-outline-variant p-5 sm:p-6 card-shadow">
                    <div class="flex items-center justify-between mb-5 pb-3 border-b border-outline-variant/60">
                        <div>
                            <h3 class="text-lg sm:text-xl font-bold text-on-surface flex items-center gap-2">
                                <span class="w-7 h-7 rounded-full bg-primary/10 text-primary flex items-center justify-center text-sm font-bold">5</span>
                                Rencana Asuhan (Intervensi)
                            </h3>
                            <p class="text-xs text-on-surface-variant mt-0.5">Daftar tindakan keperawatan yang direncanakan untuk pasien ini.</p>
                        </div>

                        <button
                            type="button"
                            onclick="tambahBarisRencana()"
                            class="bg-[#E5F5F0] text-primary border border-primary/20 px-3.5 py-2 rounded-lg text-xs font-semibold hover:bg-primary/10 transition-colors flex items-center gap-1.5 shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">add</span>
                            Tambah Rencana
                        </button>
                    </div>

                    <div class="overflow-x-auto rounded-xl border border-outline-variant/70">
                        <table class="w-full text-left border-collapse text-sm min-w-[550px]" id="tabelRencana">
                            <thead>
                                <tr class="bg-[#F8FAFC] border-b border-outline-variant/70 text-xs font-bold text-on-surface-variant">
                                    <th class="py-3 px-4 w-12 text-center">No.</th>
                                    <th class="py-3 px-4">Rencana Tindakan</th>
                                    <th class="py-3 px-4 w-40">Target</th>
                                    <th class="py-3 px-4 w-32">Keterangan</th>
                                    <th class="py-3 px-4 w-16 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="rencanaTbody" class="divide-y divide-outline-variant/50">
                                @if($latestIntervensi && $latestIntervensi->isNotEmpty())
                                    @foreach($latestIntervensi as $index => $item)
                                        <tr class="hover:bg-surface-container-low transition-colors rencana-row">
                                            <td class="py-3 px-4 text-center font-semibold text-on-surface-variant row-number">
                                                {{ $index + 1 }}
                                            </td>
                                            <td class="py-2.5 px-4">
                                                <input type="text" name="rencana_tindakan[{{ $index }}][tindakan]" value="{{ $item->rencana_tindakan }}" placeholder="Deskripsi rencana tindakan..." class="w-full bg-white border border-outline-variant rounded-lg px-3 py-1.5 text-xs text-on-surface input-ring">
                                            </td>
                                            <td class="py-2.5 px-4">
                                                <input type="text" name="rencana_tindakan[{{ $index }}][target]" value="{{ $item->target }}" placeholder="Target hasil..." class="w-full bg-white border border-outline-variant rounded-lg px-3 py-1.5 text-xs text-on-surface input-ring">
                                            </td>
                                            <td class="py-2.5 px-4">
                                                <input type="text" name="rencana_tindakan[{{ $index }}][keterangan]" value="{{ $item->keterangan }}" placeholder="Rutin / Berkala..." class="w-full bg-white border border-outline-variant rounded-lg px-3 py-1.5 text-xs text-on-surface input-ring">
                                            </td>
                                            <td class="py-2.5 px-4 text-center">
                                                <button type="button" onclick="hapusBarisRencana(this)" class="text-red-600 hover:bg-red-50 p-1.5 rounded-lg transition-colors" title="Hapus Tindakan">
                                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="hover:bg-surface-container-low transition-colors rencana-row">
                                        <td class="py-3 px-4 text-center font-semibold text-on-surface-variant row-number">
                                            1
                                        </td>
                                        <td class="py-2.5 px-4">
                                            <input type="text" name="rencana_tindakan[0][tindakan]" value="Monitor tanda vital secara berkala" placeholder="Deskripsi rencana tindakan..." class="w-full bg-white border border-outline-variant rounded-lg px-3 py-1.5 text-xs text-on-surface input-ring">
                                        </td>
                                        <td class="py-2.5 px-4">
                                            <input type="text" name="rencana_tindakan[0][target]" value="Tanda vital stabil" placeholder="Target hasil..." class="w-full bg-white border border-outline-variant rounded-lg px-3 py-1.5 text-xs text-on-surface input-ring">
                                        </td>
                                        <td class="py-2.5 px-4">
                                            <input type="text" name="rencana_tindakan[0][keterangan]" value="Rutin" placeholder="Rutin / Berkala..." class="w-full bg-white border border-outline-variant rounded-lg px-3 py-1.5 text-xs text-on-surface input-ring">
                                        </td>
                                        <td class="py-2.5 px-4 text-center">
                                            <button type="button" onclick="hapusBarisRencana(this)" class="text-red-600 hover:bg-red-50 p-1.5 rounded-lg transition-colors" title="Hapus Tindakan">
                                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-surface-container-low transition-colors rencana-row">
                                        <td class="py-3 px-4 text-center font-semibold text-on-surface-variant row-number">
                                            2
                                        </td>
                                        <td class="py-2.5 px-4">
                                            <input type="text" name="rencana_tindakan[1][tindakan]" value="Observasi keluhan dan respon terapi" placeholder="Deskripsi rencana tindakan..." class="w-full bg-white border border-outline-variant rounded-lg px-3 py-1.5 text-xs text-on-surface input-ring">
                                        </td>
                                        <td class="py-2.5 px-4">
                                            <input type="text" name="rencana_tindakan[1][target]" value="Keluhan berkurang" placeholder="Target hasil..." class="w-full bg-white border border-outline-variant rounded-lg px-3 py-1.5 text-xs text-on-surface input-ring">
                                        </td>
                                        <td class="py-2.5 px-4">
                                            <input type="text" name="rencana_tindakan[1][keterangan]" value="Evaluasi berkala" placeholder="Rutin / Berkala..." class="w-full bg-white border border-outline-variant rounded-lg px-3 py-1.5 text-xs text-on-surface input-ring">
                                        </td>
                                        <td class="py-2.5 px-4 text-center">
                                            <button type="button" onclick="hapusBarisRencana(this)" class="text-red-600 hover:bg-red-50 p-1.5 rounded-lg transition-colors" title="Hapus Tindakan">
                                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </section>
            </form>

        </div>

        <!-- ===================================== -->
        <!-- RIGHT COLUMN (RINGKASAN & ACTION) -->
        <!-- ===================================== -->
        <div class="xl:col-span-1">
            <div class="sticky top-6 flex flex-col gap-5">

                <!-- RINGKASAN DOKUMEN -->
                <div class="bg-white rounded-xl border border-outline-variant p-5 sm:p-6 card-shadow">
                    <h3 class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-4 border-b border-outline-variant/60 pb-2 flex items-center justify-between">
                        <span>Ringkasan Dokumen</span>
                        <span class="material-symbols-outlined text-primary text-base">clinical_notes</span>
                    </h3>

                    <div class="space-y-4 text-sm">
                        <div>
                            <p class="text-xs text-on-surface-variant font-medium">Pasien Terpilih</p>
                            <p id="summaryPasienName" class="text-sm font-bold text-on-surface mt-0.5">
                                {{ $selectedPasien ? $selectedPasien->nama_lengkap : 'Belum Memilih Pasien' }}
                            </p>
                            <p id="summaryNoRM" class="text-xs text-on-surface-variant">
                                {{ $selectedPasien ? $selectedPasien->no_rm : '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-on-surface-variant font-medium">Status Dokumen</p>
                            <div id="summaryStatusBadge" class="inline-flex items-center gap-1.5 {{ $selectedPasien ? 'bg-[#E5F5F0] text-primary' : 'bg-amber-50 text-amber-700 border border-amber-200' }} px-2.5 py-1 rounded-lg text-xs font-bold mt-1">
                                <span id="summaryStatusIcon" class="material-symbols-outlined text-[14px]">{{ $selectedPasien ? 'check_circle' : 'info' }}</span>
                                <span id="summaryStatusText">{{ $selectedPasien ? 'Siap Disimpan' : 'Pilih Pasien Dulu' }}</span>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs text-on-surface-variant font-medium">Jumlah Rencana Asuhan</p>
                            <p id="summaryJumlahRencana" class="text-sm font-semibold text-on-surface mt-0.5">
                                {{ $latestIntervensi->count() ?: 2 }} Tindakan
                            </p>
                        </div>

                        <div class="pt-3 border-t border-outline-variant/60 text-xs text-on-surface-variant flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">schedule</span>
                            <span id="summaryTimestamp">Terakhir: {{ \Carbon\Carbon::now()->translatedFormat('H:i') }} WIB</span>
                        </div>
                    </div>
                </div>

                <!-- ACTION BUTTONS -->
                <div class="flex flex-col gap-2.5">
                    <button
                        type="button"
                        id="btnSimpanAsuhan"
                        onclick="triggerSubmitForm()"
                        class="w-full bg-primary text-white py-3 rounded-xl font-bold hover:bg-[#005a3c] transition-all shadow-md flex items-center justify-center gap-2 text-sm">
                        <span class="material-symbols-outlined text-lg">save</span>
                        <span>Simpan Asuhan Keperawatan</span>
                    </button>

                    <a
                        id="btnSideLihatRM"
                        href="{{ $selectedPasien ? route('rekam-medis', ['id' => $selectedPasien->id_pasien]) : route('rekam-medis') }}"
                        class="w-full bg-white text-secondary border border-secondary/40 py-2.5 rounded-xl font-semibold hover:bg-secondary/5 transition-colors flex items-center justify-center gap-2 text-sm text-center">
                        <span class="material-symbols-outlined text-base">folder_shared</span>
                        <span>Lihat Riwayat Rekam Medis</span>
                    </a>

                    <button
                        type="button"
                        onclick="resetFormAsuhan()"
                        class="w-full bg-transparent text-on-surface-variant py-2 rounded-xl text-xs font-semibold hover:bg-surface-container-low transition-colors">
                        Bersihkan Form
                    </button>
                </div>

            </div>
        </div>

    </div>

</div>

{{-- ========================================================== --}}
{{-- JAVASCRIPT LOGIC --}}
{{-- ========================================================== --}}
<script>
let toastTimer = null;

// Tampilkan Toast Notifikasi
function showToastNotification(title, message, patientName, patientId) {
    const toast = document.getElementById('toastNotification');
    const titleEl = document.getElementById('toastTitle');
    const messageEl = document.getElementById('toastMessage');
    const nameEl = document.getElementById('toastPasienName');
    const linkRM = document.getElementById('toastLinkRM');
    const progress = document.getElementById('toastProgress');

    if (titleEl) titleEl.textContent = title;
    if (messageEl) messageEl.textContent = message;
    if (nameEl) nameEl.textContent = patientName;
    if (linkRM && patientId) {
        linkRM.href = "{{ url('/rekam-medis') }}?id=" + patientId;
    }

    toast.classList.remove('hidden');
    // Trigger animation
    setTimeout(() => {
        toast.classList.remove('-translate-y-16', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');
    }, 10);

    // Progress bar animation
    if (progress) {
        progress.style.width = '100%';
        setTimeout(() => { progress.style.width = '0%'; }, 50);
    }

    if (toastTimer) clearTimeout(toastTimer);
    toastTimer = setTimeout(() => {
        hideToastNotification();
    }, 4500);
}

function hideToastNotification() {
    const toast = document.getElementById('toastNotification');
    if (!toast) return;
    toast.classList.remove('translate-y-0', 'opacity-100');
    toast.classList.add('-translate-y-16', 'opacity-0');
    setTimeout(() => {
        toast.classList.add('hidden');
    }, 300);
}

// Dropdown Pencarian Pasien
function showPasienDropdown(event) {
    if (event) event.stopPropagation();
    const list = document.getElementById('pasienDropdownList');
    if (list) list.classList.remove('hidden');
}

function toggleOrFocusDropdown(event) {
    if (event) event.stopPropagation();
    const list = document.getElementById('pasienDropdownList');
    const input = document.getElementById('pasienSearchInput');
    if (list) {
        if (list.classList.contains('hidden')) {
            list.classList.remove('hidden');
            if (input) {
                input.focus();
                input.select();
            }
        } else {
            list.classList.add('hidden');
        }
    }
}

function hidePasienDropdown() {
    const list = document.getElementById('pasienDropdownList');
    if (list) list.classList.add('hidden');
}

function clearSearchPasien(event) {
    if (event) event.stopPropagation();
    const input = document.getElementById('pasienSearchInput');
    if (input) {
        input.value = '';
        input.focus();
    }
    filterPasienDropdown('');
    showPasienDropdown();
}

function filterPasienDropdown(keyword) {
    const term = keyword.toLowerCase().trim();
    showPasienDropdown();
    const items = document.querySelectorAll('.pasien-dropdown-item');
    items.forEach(item => {
        const text = item.textContent.toLowerCase();
        const nama = (item.getAttribute('data-nama') || '').toLowerCase();
        const nik = (item.getAttribute('data-nik') || '').toLowerCase();
        const rm = (item.getAttribute('data-rm') || '').toLowerCase();
        if (text.includes(term) || nama.includes(term) || nik.includes(term) || rm.includes(term)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
}

// Menutup dropdown saat klik di luar
document.addEventListener('click', function(e) {
    const input = document.getElementById('pasienSearchInput');
    const dropdown = document.getElementById('pasienDropdownList');
    const btn = document.getElementById('btnPilihPasien');
    
    if (dropdown && !dropdown.classList.contains('hidden')) {
        const isClickInsideInput = input && input.contains(e.target);
        const isClickInsideDropdown = dropdown.contains(e.target);
        const isClickInsideBtn = btn && btn.contains(e.target);
        
        if (!isClickInsideInput && !isClickInsideDropdown && !isClickInsideBtn) {
            dropdown.classList.add('hidden');
        }
    }
});

// Memilih pasien dan memuat datanya secara AJAX
function selectPasienById(id) {
    hidePasienDropdown();
    
    // Tampilkan loading feedback
    const btnSimpan = document.getElementById('btnSimpanAsuhan');
    if (btnSimpan) btnSimpan.disabled = true;

    fetch("{{ url('/asuhan-keperawatan/pasien') }}/" + id, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (btnSimpan) btnSimpan.disabled = false;
        if (!data.success) {
            alert(data.message || 'Gagal memuat data pasien.');
            return;
        }

        const p = data.pasien;
        const asuhan = data.asuhan;
        const intervensi = data.intervensi;

        // 1. Update Search Input & Selected Patient Card
        document.getElementById('pasienSearchInput').value = p.nama_lengkap;
        document.getElementById('hidden_id_pasien').value = p.id_pasien;
        document.getElementById('cardNama').textContent = p.nama_lengkap;
        document.getElementById('cardSub').textContent = `NIK: ${p.nik} • ${p.no_rm} • ${p.no_telp || 'Tidak ada no. telp'}`;
        document.getElementById('cardAvatar').textContent = p.initials;

        // 2. Update Patient Identity Section
        document.getElementById('infoTglLahir').textContent = `${p.formatted_tgl_lahir} (${p.age})`;
        document.getElementById('infoJk').textContent = p.formatted_jk;
        document.getElementById('infoGolDarah').textContent = p.golongan_darah || '-';
        const infoAlergi = document.getElementById('infoAlergi');
        if (infoAlergi) {
            infoAlergi.textContent = p.alergi || 'Tidak Ada';
        }
        document.getElementById('infoTglKunjungan').textContent = p.tgl_kunjungan;

        // 3. Update Rekam Medis Links
        const rmUrl = "{{ url('/rekam-medis') }}?id=" + p.id_pasien;
        const topLink = document.getElementById('btnTopRekamMedis');
        const cardLink = document.getElementById('linkLihatRM');
        const sideLink = document.getElementById('btnSideLihatRM');
        if (topLink) topLink.href = rmUrl;
        if (cardLink) cardLink.href = rmUrl;
        if (sideLink) sideLink.href = rmUrl;

        // 4. Update Summary & Badges
        document.getElementById('summaryPasienName').textContent = p.nama_lengkap;
        document.getElementById('summaryNoRM').textContent = p.no_rm;
        const badgeTerpilih = document.getElementById('badgeTerpilih');
        if (badgeTerpilih) badgeTerpilih.classList.remove('hidden');
        const statusBadge = document.getElementById('summaryStatusBadge');
        if (statusBadge) {
            statusBadge.className = "inline-flex items-center gap-1.5 bg-[#E5F5F0] text-primary px-2.5 py-1 rounded-lg text-xs font-bold mt-1";
            statusBadge.innerHTML = '<span class="material-symbols-outlined text-[14px]">check_circle</span> <span>Siap Disimpan</span>';
        }

        // 5. Prefill Form with existing Assessment if available, or clear
        if (asuhan) {
            document.getElementById('inputKeluhanUtama').value = asuhan.keluhan_utama || '';
            document.getElementById('inputRiwayatKeluhan').value = asuhan.riwayat_keluhan || '';
            document.getElementById('inputKondisiUmum').value = asuhan.kondisi_umum || 'Baik';
            document.getElementById('inputKesadaran').value = asuhan.kesadaran || 'Compos Mentis';
            document.getElementById('inputTD').value = asuhan.tekanan_darah || '120/80';
            document.getElementById('inputNadi').value = asuhan.nadi || '80';
            document.getElementById('inputSuhu').value = asuhan.suhu_tubuh || '36.5';
            document.getElementById('inputRR').value = asuhan.rr || '20';
            document.getElementById('inputSpO2').value = asuhan.spo2 || '98';
        } else {
            document.getElementById('inputKeluhanUtama').value = '';
            document.getElementById('inputRiwayatKeluhan').value = '';
            document.getElementById('inputKondisiUmum').value = 'Baik';
            document.getElementById('inputKesadaran').value = 'Compos Mentis';
            document.getElementById('inputTD').value = '120/80';
            document.getElementById('inputNadi').value = '80';
            document.getElementById('inputSuhu').value = '36.5';
            document.getElementById('inputRR').value = '20';
            document.getElementById('inputSpO2').value = '98';
        }

        // 6. Prefill Diagnosis & Intervensi
        const firstIntervensi = intervensi && intervensi.length > 0 ? intervensi[0] : null;
        document.getElementById('inputDiagnosaAwal').value = firstIntervensi ? (firstIntervensi.diagnosa_awal || '') : '';
        document.getElementById('inputFaktorTerkait').value = firstIntervensi ? (firstIntervensi.faktor_terkait || '') : '';
        document.getElementById('inputPrioritasDiagnosa').value = firstIntervensi ? (firstIntervensi.prioritas_diagnosa || '') : '';

        // 7. Render Intervensi Table Rows
        const tbody = document.getElementById('rencanaTbody');
        tbody.innerHTML = '';

        if (intervensi && intervensi.length > 0) {
            intervensi.forEach((item, idx) => {
                tambahBarisRencanaWithData(idx, item.rencana_tindakan, item.target, item.keterangan);
            });
        } else {
            tambahBarisRencanaWithData(0, 'Monitor tanda vital secara berkala', 'Tanda vital stabil', 'Rutin');
            tambahBarisRencanaWithData(1, 'Observasi keluhan dan respon terapi', 'Keluhan berkurang', 'Evaluasi berkala');
        }

        updateRencanaCounter();
    })
    .catch(err => {
        if (btnSimpan) btnSimpan.disabled = false;
        console.error(err);
        alert('Terjadi kesalahan saat memuat data pasien.');
    });
}

// Tambah Baris Intervensi
function tambahBarisRencana() {
    const tbody = document.getElementById('rencanaTbody');
    const index = tbody.querySelectorAll('.rencana-row').length;
    tambahBarisRencanaWithData(index, '', '', '');
    updateRencanaCounter();
}

function tambahBarisRencanaWithData(index, tindakan = '', target = '', keterangan = '') {
    const tbody = document.getElementById('rencanaTbody');
    const tr = document.createElement('tr');
    tr.className = 'hover:bg-surface-container-low transition-colors rencana-row';
    tr.innerHTML = `
        <td class="py-3 px-4 text-center font-semibold text-on-surface-variant row-number">
            ${index + 1}
        </td>
        <td class="py-2.5 px-4">
            <input type="text" name="rencana_tindakan[${index}][tindakan]" value="${tindakan}" placeholder="Deskripsi rencana tindakan..." class="w-full bg-white border border-outline-variant rounded-lg px-3 py-1.5 text-xs text-on-surface input-ring">
        </td>
        <td class="py-2.5 px-4">
            <input type="text" name="rencana_tindakan[${index}][target]" value="${target}" placeholder="Target hasil..." class="w-full bg-white border border-outline-variant rounded-lg px-3 py-1.5 text-xs text-on-surface input-ring">
        </td>
        <td class="py-2.5 px-4">
            <input type="text" name="rencana_tindakan[${index}][keterangan]" value="${keterangan}" placeholder="Rutin / Berkala..." class="w-full bg-white border border-outline-variant rounded-lg px-3 py-1.5 text-xs text-on-surface input-ring">
        </td>
        <td class="py-2.5 px-4 text-center">
            <button type="button" onclick="hapusBarisRencana(this)" class="text-red-600 hover:bg-red-50 p-1.5 rounded-lg transition-colors" title="Hapus Tindakan">
                <span class="material-symbols-outlined text-[18px]">delete</span>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
}

// Hapus Baris Intervensi
function hapusBarisRencana(btn) {
    const tbody = document.getElementById('rencanaTbody');
    const row = btn.closest('tr');
    if (tbody.querySelectorAll('.rencana-row').length <= 1) {
        alert('Minimal harus ada 1 baris rencana tindakan.');
        return;
    }
    row.remove();
    reindexRencanaRows();
    updateRencanaCounter();
}

function reindexRencanaRows() {
    const rows = document.querySelectorAll('#rencanaTbody .rencana-row');
    rows.forEach((row, idx) => {
        row.querySelector('.row-number').textContent = idx + 1;
        row.querySelector('input[name*="[tindakan]"]').name = `rencana_tindakan[${idx}][tindakan]`;
        row.querySelector('input[name*="[target]"]').name = `rencana_tindakan[${idx}][target]`;
        row.querySelector('input[name*="[keterangan]"]').name = `rencana_tindakan[${idx}][keterangan]`;
    });
}

function updateRencanaCounter() {
    const count = document.querySelectorAll('#rencanaTbody .rencana-row').length;
    const summary = document.getElementById('summaryJumlahRencana');
    if (summary) summary.textContent = `${count} Tindakan`;
}

function triggerSubmitForm() {
    const form = document.getElementById('formAsuhan');
    if (form) {
        form.requestSubmit();
    }
}

// Submit Form via AJAX
function submitFormAsuhan(event) {
    event.preventDefault();
    const form = document.getElementById('formAsuhan');
    const idPasien = document.getElementById('hidden_id_pasien').value;
    const btn = document.getElementById('btnSimpanAsuhan');

    if (!idPasien) {
        alert('Silakan pilih pasien terlebih dahulu.');
        showPasienDropdown();
        return;
    }

    const keluhan = document.getElementById('inputKeluhanUtama').value.trim();
    if (!keluhan) {
        alert('Keluhan Utama wajib diisi.');
        document.getElementById('inputKeluhanUtama').focus();
        return;
    }

    const originalBtnHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = `
        <span class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
        <span>Menyimpan ke Database...</span>
    `;

    const formData = new FormData(form);

    fetch("{{ route('asuhan-keperawatan.store') }}", {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = originalBtnHtml;

        if (data.success) {
            const patientName = document.getElementById('cardNama').textContent;
            showToastNotification('Berhasil Disimpan!', data.message, patientName, idPasien);

            // Update badge status
            const badge = document.getElementById('summaryStatusBadge');
            if (badge) {
                badge.className = 'inline-flex items-center gap-1.5 bg-[#E5F5F0] px-2.5 py-1 rounded-lg text-primary text-xs font-bold mt-1';
                badge.innerHTML = '<span class="material-symbols-outlined text-[14px]">check_circle</span> Tersimpan di Database';
            }

            const now = new Date();
            const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            document.getElementById('summaryTimestamp').textContent = `Terakhir: Hari ini, ${timeStr} WIB`;
        } else {
            alert(data.message || 'Gagal menyimpan data.');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = originalBtnHtml;
        console.error(err);
        alert('Terjadi kesalahan jaringan atau server.');
    });
}

function resetFormAsuhan() {
    if (confirm('Apakah Anda yakin ingin mengosongkan input form?')) {
        document.getElementById('inputKeluhanUtama').value = '';
        document.getElementById('inputRiwayatKeluhan').value = '';
        document.getElementById('inputDiagnosaAwal').value = '';
        document.getElementById('inputFaktorTerkait').value = '';
        document.getElementById('inputPrioritasDiagnosa').value = '';
    }
}
</script>

@endsection