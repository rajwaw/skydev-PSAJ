@extends('layouts.app')

@section('title', 'Rekam Medis - Mandalacare')

@section('content')
<div class="p-4 sm:p-6 md:p-8 lg:p-10 w-full max-w-7xl mx-auto flex-1 flex flex-col gap-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-on-surface tracking-tight">Rekam Medis Pasien</h1>
            <p class="text-sm sm:text-base text-on-surface-variant mt-1">
                Kelola dan pantau riwayat pemeriksaan serta keluhan setiap pasien secara rinci dari database.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ $selectedPasien ? route('asuhan-keperawatan', ['pasien_id' => $selectedPasien->id_pasien]) : '#' }}" id="topBtnAsuhan" class="bg-primary text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-[#005a3c] transition-colors inline-flex items-center gap-2 shadow-sm {{ $selectedPasien ? '' : 'hidden' }}">
                <span class="material-symbols-outlined text-base">clinical_notes</span>
                <span>Asuhan Keperawatan Baru</span>
            </a>
        </div>
    </div>

    <!-- Bento Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Left Column (Search & Profile Summary) -->
        <div class="lg:col-span-4 w-full min-w-0">
            <div class="lg:sticky lg:top-20 flex flex-col gap-4 sm:gap-5 z-20 lg:max-h-[calc(100vh-6rem)] lg:overflow-y-auto pr-1">

                <!-- Search Pasien Card -->
                <div class="bg-white rounded-xl border border-outline-variant shadow-sm p-4 sm:p-5">
                    <label class="block text-sm font-bold text-on-surface mb-2">Cari Pasien di Database</label>
                <div class="relative mb-3">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-sm">search</span>
                    <input
                        id="rekamSearchInput"
                        class="w-full pl-9 pr-3 py-2.5 bg-surface border border-outline-variant rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all placeholder-on-surface-variant/60"
                        placeholder="Ketik nama atau NIK pasien..."
                        type="text"
                        value="{{ $selectedPasien ? $selectedPasien->nama_lengkap : '' }}"
                        oninput="filterPasienRM(this.value)"
                    >
                </div>

                <!-- Pasien List for Selection -->
                <div class="space-y-2 max-h-48 overflow-y-auto pr-1" id="pasienListRM">
                    @forelse($daftarPasien as $p)
                        <div
                            class="pasien-rm-card border {{ ($selectedPasien && $selectedPasien->id_pasien == $p->id_pasien) ? 'border-primary bg-[#E5F5F0]/30' : 'border-outline-variant/70 bg-white' }} rounded-xl p-3.5 cursor-pointer hover:border-primary transition-all group"
                            data-id="{{ $p->id_pasien }}"
                            data-name="{{ $p->nama_lengkap }}"
                            data-nik="{{ $p->nik }}"
                            onclick="selectPasienRM({{ $p->id_pasien }})"
                        >
                            <div class="flex justify-between items-start">
                                <div class="min-w-0 flex-1 pr-2">
                                    <div class="flex items-center gap-1.5">
                                        <h4 class="text-sm font-bold text-on-surface group-hover:text-primary transition-colors truncate">
                                            {{ $p->nama_lengkap }}
                                        </h4>
                                        @if($selectedPasien && $selectedPasien->id_pasien == $p->id_pasien)
                                            <span class="bg-primary text-white text-[10px] font-bold px-1.5 py-0.2 rounded">Aktif</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-on-surface-variant mt-0.5 truncate">
                                        NIK: {{ $p->nik }} &bull; {{ $p->no_rm }}
                                    </p>
                                </div>
                                <span class="text-xs font-semibold text-primary shrink-0 flex items-center">
                                    Pilih <span class="material-symbols-outlined text-[14px] ml-0.5">arrow_forward</span>
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-xs text-on-surface-variant">
                            Belum ada pasien terdaftar di sistem.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Pasien Terpilih Profile Card -->
            <div class="bg-white rounded-xl border border-outline-variant shadow-sm p-4 sm:p-5 flex flex-col">
                <div class="flex items-center gap-3.5 mb-4">
                    <div id="selectedAvatar" class="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center text-base font-bold shrink-0 p-2.5">
                        {{ $selectedPasien ? $selectedPasien->initials : 'PS' }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 id="selectedName" class="text-base font-bold text-on-surface leading-tight truncate">
                            {{ $selectedPasien ? $selectedPasien->nama_lengkap : 'Belum Memilih Pasien' }}
                        </h3>
                        <p id="selectedGenderAge" class="text-xs text-on-surface-variant mt-0.5 truncate">
                            {{ $selectedPasien ? $selectedPasien->formatted_jk . ', ' . $selectedPasien->age : '-' }}
                        </p>
                    </div>
                </div>

                <div class="space-y-2.5 mb-5 flex-1 text-xs sm:text-sm">
                    <div class="flex justify-between border-b border-outline-variant/60 pb-2">
                        <span class="text-on-surface-variant">No. Rekam Medis</span>
                        <span id="selectedRM" class="font-bold text-on-surface">
                            {{ $selectedPasien ? $selectedPasien->no_rm : '-' }}
                        </span>
                    </div>

                    <div class="flex justify-between border-b border-outline-variant/60 pb-2">
                        <span class="text-on-surface-variant">NIK</span>
                        <span id="selectedNik" class="font-semibold text-on-surface">
                            {{ $selectedPasien ? $selectedPasien->nik : '-' }}
                        </span>
                    </div>

                    <div class="flex justify-between border-b border-outline-variant/60 pb-2">
                        <span class="text-on-surface-variant">Gol. Darah</span>
                        <span id="selectedBlood" class="font-bold text-red-600 flex items-center gap-1">
                            <span class="material-symbols-outlined text-[15px]">bloodtype</span>
                            <span>{{ ($selectedPasien && $selectedPasien->golongan_darah) ? $selectedPasien->golongan_darah : '-' }}</span>
                        </span>
                    </div>

                    <!-- Alergi Obat (Prominent Badge) -->
                    <div class="flex justify-between items-center border-b border-outline-variant/60 pb-2">
                        <span class="text-on-surface-variant font-medium flex items-center gap-1">
                            <span class="material-symbols-outlined text-[15px] text-amber-600">medication</span>
                            Alergi Obat
                        </span>
                        <span id="selectedAllergy">
                            @if($alergiText && $alergiText !== 'Tidak Ada')
                                <span class="bg-red-50 text-red-700 border border-red-200 px-2 py-0.5 rounded text-xs font-bold inline-flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">warning</span>
                                    {{ $alergiText }}
                                </span>
                            @else
                                <span class="text-on-surface font-semibold text-xs">Tidak Ada</span>
                            @endif
                        </span>
                    </div>

                    <div class="flex justify-between border-b border-outline-variant/60 pb-2">
                        <span class="text-on-surface-variant">Kunjungan Terakhir</span>
                        <span id="selectedLastVisit" class="font-semibold text-on-surface">
                            {{ $lastVisitDate }}
                        </span>
                    </div>

                    <div class="flex justify-between border-b border-outline-variant/60 pb-2">
                        <span class="text-on-surface-variant">TB / BB</span>
                        <span id="selectedTBBB" class="font-semibold text-on-surface">
                            @if($latestAsuhan && ($latestAsuhan->tinggi_badan || $latestAsuhan->berat_badan))
                                {{ $latestAsuhan->tinggi_badan ? $latestAsuhan->tinggi_badan . ' cm' : '-' }} / {{ $latestAsuhan->berat_badan ? $latestAsuhan->berat_badan . ' kg' : '-' }}
                                @if($latestAsuhan->imt)
                                    <span class="text-purple-700 text-xs font-bold">(IMT: {{ $latestAsuhan->imt }})</span>
                                @endif
                            @else
                                -
                            @endif
                        </span>
                    </div>
                </div>

                    <button
                        type="button"
                        id="btnDownloadPdf"
                        onclick="openModalCetakRM()"
                        class="w-full bg-emerald-50 text-emerald-800 border border-emerald-300 py-2.5 rounded-xl text-xs font-bold hover:bg-emerald-100 transition-colors flex justify-center items-center gap-2 shadow-sm text-center cursor-pointer {{ ($selectedPasien && $riwayatKunjungan->isNotEmpty()) ? '' : 'hidden' }}">
                        <span class="material-symbols-outlined text-base text-emerald-700">picture_as_pdf</span>
                        <span>Cetak Ringkasan Medis (PDF)</span>
                    </button>

                    <a
                        id="btnActionAsuhan"
                        href="{{ $selectedPasien ? route('asuhan-keperawatan', ['pasien_id' => $selectedPasien->id_pasien]) : route('asuhan-keperawatan') }}"
                        class="w-full bg-primary text-white py-2.5 rounded-xl text-xs font-bold hover:bg-[#005a3c] transition-colors flex justify-center items-center gap-2 shadow-sm text-center">
                        <span class="material-symbols-outlined text-base">clinical_notes</span>
                        <span>Input Asuhan Keperawatan</span>
                    </a>

                    <a
                        href="{{ route('pasien') }}"
                        class="w-full bg-surface border border-outline-variant text-on-surface py-2 rounded-xl text-xs font-semibold hover:bg-surface-container-low transition-colors flex justify-center items-center gap-1.5 text-center">
                        <span class="material-symbols-outlined text-base">person</span>
                        <span>Daftar Seluruh Pasien</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Column (Riwayat Kunjungan & Keluhan Tiap Pasien) -->
        <div class="lg:col-span-8 flex flex-col gap-6 w-full min-w-0">

            <div class="bg-white rounded-xl border border-outline-variant shadow-sm flex-1 flex flex-col overflow-hidden">

                <!-- Header Panel -->
                <div class="p-4 sm:p-6 border-b border-outline-variant flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-[#F8FAFC]">
                    <div>
                        <h3 class="text-lg sm:text-xl font-bold text-on-surface flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">history</span>
                            Riwayat Pemeriksaan &amp; Keluhan Pasien
                        </h3>
                        <p class="text-xs text-on-surface-variant mt-0.5">
                            Menampilkan seluruh riwayat keluhan dan asuhan keperawatan pasien <strong id="headerPatientName" class="text-on-surface">{{ $selectedPasien ? $selectedPasien->nama_lengkap : '' }}</strong>.
                        </p>
                    </div>

                    <span id="visitCountBadge" class="bg-surface-container px-3 py-1 rounded-full text-xs font-bold text-on-surface-variant border border-outline-variant">
                        {{ $riwayatKunjungan->count() }} Kunjungan
                    </span>
                </div>

                <!-- Timeline Body -->
                <div class="p-4 sm:p-6 flex-1 relative" id="timelineContainer">

                    @if($riwayatKunjungan->isNotEmpty())
                        <div class="relative" id="timelineContentWrapper">
                            <!-- Vertical Timeline Line -->
                            <div class="absolute left-[31px] sm:left-[39px] top-6 bottom-6 w-0.5 bg-outline-variant/60"></div>

                            <div class="space-y-6" id="visitTimelineList">
                            @foreach($riwayatKunjungan as $index => $rm)
                                @php
                                    $asuhan = $rm->asuhanMedis;
                                    $intervensiList = $rm->intervensi;
                                    $firstIntervensi = $intervensiList->first();
                                    $diagnosa = $firstIntervensi ? $firstIntervensi->diagnosa_awal : ($asuhan ? $asuhan->keluhan_utama : 'Pemeriksaan Umum');
                                    $prioritas = $intervensiList->pluck('prioritas_diagnosa')->filter()->first();
                                    $planList = $intervensiList->pluck('rencana_tindakan')->filter()->values();
                                    $implementasi = $rm->implementasi;
                                    $evaluasi = $rm->evaluasi;
                                @endphp

                                <div class="relative pl-10 sm:pl-12 group visit-entry">
                                    <!-- Timeline Node Icon -->
                                    <div class="absolute left-[16px] sm:left-[24px] top-1 w-8 h-8 rounded-full bg-[#E5F5F0] text-primary flex items-center justify-center border-4 border-white shadow-sm z-10 font-bold">
                                        <span class="material-symbols-outlined text-[16px]">stethoscope</span>
                                    </div>

                                    <div class="bg-surface border border-outline-variant/80 rounded-xl p-4 sm:p-5 shadow-sm hover:shadow-md transition-all relative overflow-hidden">
                                        <!-- Active Green Left Bar -->
                                        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-primary"></div>

                                        <!-- Visit Header -->
                                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-4 gap-2 border-b border-outline-variant/50 pb-3">
                                            <div>
                                                <div class="flex items-center gap-2 mb-1">
                                                    <h4 class="text-sm sm:text-base font-bold text-on-surface">
                                                        Kunjungan Pemeriksaan
                                                    </h4>
                                                    @if($loop->first)
                                                        <span class="bg-emerald-100 text-emerald-800 border border-emerald-300 px-2 py-0.5 rounded-full text-[10px] font-bold">
                                                            Pemeriksaan Terkini
                                                        </span>
                                                    @else
                                                        <span class="bg-slate-100 text-slate-700 border border-slate-200 px-2 py-0.5 rounded-full text-[10px] font-semibold">
                                                            Riwayat Sebelumnya
                                                        </span>
                                                    @endif
                                                    <span class="bg-[#E5F5F0] text-primary px-2.5 py-0.5 rounded-full text-[10px] font-bold">
                                                        Tersimpan
                                                    </span>
                                                </div>
                                                <p class="text-xs text-on-surface-variant flex items-center gap-1.5">
                                                    <span class="material-symbols-outlined text-[14px]">event</span>
                                                    {{ \Carbon\Carbon::parse($rm->tgl_pemeriksaan)->translatedFormat('d F Y, H:i') }} WIB
                                                    &bull; No. RM: {{ $selectedPasien->no_rm }}
                                                </p>
                                            </div>

                                            <div class="flex items-center gap-2 shrink-0">
                                                <a href="{{ route('rekam-medis.kunjungan.pdf', ['id_rekam_medis' => $rm->id_rekam_medis]) }}" target="_blank" class="text-white bg-emerald-700 hover:bg-emerald-800 px-3.5 py-1.5 rounded-lg text-xs font-bold inline-flex items-center gap-1.5 transition-all shadow-sm" title="Cetak rangkuman rekam medis khusus pemeriksaan tanggal ini saja">
                                                    <span class="material-symbols-outlined text-[15px]">picture_as_pdf</span>
                                                    <span>{{ \Carbon\Carbon::parse($rm->tgl_pemeriksaan)->isToday() ? 'Cetak Rangkuman Hari Ini' : 'Cetak Rangkuman Ini' }}</span>
                                                </a>
                                                <a href="{{ route('asuhan-keperawatan', ['pasien_id' => $selectedPasien->id_pasien]) }}" class="text-primary hover:underline text-xs font-bold inline-flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-[15px]">edit_note</span>
                                                    <span>Buka di Asuhan</span>
                                                </a>
                                            </div>
                                        </div>

                                        <!-- Keluhan & Tanda Vital Grid -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                            <!-- Keluhan Utama Pasien -->
                                            <div class="bg-surface-container-low p-3.5 rounded-xl border border-outline-variant/50">
                                                <p class="text-xs font-bold text-primary mb-1 flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-[15px]">chat_bubble</span>
                                                    Keluhan Pasien (Spesifik)
                                                </p>
                                                <p class="text-xs sm:text-sm font-semibold text-on-surface">
                                                    {{ $asuhan && $asuhan->keluhan_utama ? $asuhan->keluhan_utama : 'Tidak ada catatan keluhan khusus.' }}
                                                </p>
                                                @if($asuhan && $asuhan->riwayat_keluhan)
                                                    <p class="text-xs text-on-surface-variant mt-1 italic">
                                                        Riwayat: {{ $asuhan->riwayat_keluhan }}
                                                    </p>
                                                @endif
                                            </div>

                                            <!-- Tanda Vital -->
                                            <div class="bg-surface-container-low p-3.5 rounded-xl border border-outline-variant/50">
                                                <p class="text-xs font-bold text-on-surface-variant mb-2 flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-[15px]">vital_signs</span>
                                                    Tanda-tanda Vital
                                                </p>
                                                <div class="flex flex-wrap gap-1.5">
                                                    <span class="bg-white px-2.5 py-1 rounded-lg text-xs font-semibold text-on-surface border border-outline-variant/60">
                                                        TD: {{ $asuhan && $asuhan->tekanan_darah ? $asuhan->tekanan_darah : '120/80' }}
                                                    </span>
                                                    <span class="bg-white px-2.5 py-1 rounded-lg text-xs font-semibold text-red-600 border border-red-200">
                                                        Suhu: {{ $asuhan && $asuhan->suhu_tubuh ? $asuhan->suhu_tubuh . '°C' : '36.5°C' }}
                                                    </span>
                                                    <span class="bg-white px-2.5 py-1 rounded-lg text-xs font-semibold text-on-surface border border-outline-variant/60">
                                                        Nadi: {{ $asuhan && $asuhan->nadi ? $asuhan->nadi . ' x/m' : '80 x/m' }}
                                                    </span>
                                                    <span class="bg-white px-2.5 py-1 rounded-lg text-xs font-semibold text-on-surface border border-outline-variant/60">
                                                        RR: {{ $asuhan && $asuhan->rr ? $asuhan->rr . ' x/m' : '20 x/m' }}
                                                    </span>
                                                    @if($asuhan && $asuhan->spo2)
                                                        <span class="bg-white px-2.5 py-1 rounded-lg text-xs font-semibold text-blue-600 border border-blue-200">
                                                            SpO2: {{ $asuhan->spo2 }}%
                                                        </span>
                                                    @endif
                                                    @if($asuhan && $asuhan->tinggi_badan)
                                                        <span class="bg-white px-2.5 py-1 rounded-lg text-xs font-semibold text-emerald-700 border border-emerald-200">
                                                            TB: {{ $asuhan->tinggi_badan }} cm
                                                        </span>
                                                    @endif
                                                    @if($asuhan && $asuhan->berat_badan)
                                                        <span class="bg-white px-2.5 py-1 rounded-lg text-xs font-semibold text-emerald-700 border border-emerald-200">
                                                            BB: {{ $asuhan->berat_badan }} kg
                                                        </span>
                                                    @endif
                                                    @if($asuhan && $asuhan->imt)
                                                        <span class="bg-white px-2.5 py-1 rounded-lg text-xs font-semibold text-purple-700 border border-purple-200">
                                                            IMT: {{ $asuhan->imt }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Diagnosis & Rencana Intervensi -->
                                        <div class="bg-[#F8FAFC] rounded-xl p-3.5 sm:p-4 border border-outline-variant/60">
                                            <h5 class="text-xs font-bold text-on-surface mb-2 flex items-center gap-1.5">
                                                <span class="material-symbols-outlined text-primary text-base">assignment</span>
                                                Diagnosis &amp; Rencana Tindakan
                                            </h5>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs sm:text-sm">
                                                <div>
                                                    <p class="text-xs font-bold text-on-surface-variant">Diagnosis / Masalah:</p>
                                                    <p class="text-on-surface font-medium mt-0.5 whitespace-pre-line">{{ $diagnosa }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-bold text-on-surface-variant">Rencana Tindakan:</p>
                                                    @if($planList->isNotEmpty())
                                                        <ul class="list-disc list-inside mt-0.5 space-y-0.5 text-on-surface">
                                                            @foreach($planList as $plan)
                                                                <li>{{ $plan }}</li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <p class="text-on-surface mt-0.5">Observasi dan pemberian edukasi kesehatan.</p>
                                                    @endif
                                                </div>
                                            </div>

                                            @if($prioritas)
                                                <div class="mt-3 pt-2.5 border-t border-outline-variant/50">
                                                    <div class="flex items-start gap-2 bg-amber-50/80 border border-amber-200 rounded-lg p-2.5 text-xs">
                                                        <span class="material-symbols-outlined text-amber-700 text-base flex-shrink-0 mt-0.5">push_pin</span>
                                                        <div class="min-w-0 flex-1">
                                                            <span class="font-bold text-amber-900 block mb-0.5">Prioritas Diagnosa &amp; Catatan Khusus:</span>
                                                            <p class="text-slate-800 whitespace-pre-line leading-relaxed">{{ $prioritas }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        @if($implementasi)
                                            <div class="bg-[#F8FAFC] rounded-xl p-3.5 sm:p-4 border border-outline-variant/60 mt-3">
                                                <h5 class="text-xs font-bold text-on-surface mb-2 flex items-center gap-1.5">
                                                    <span class="material-symbols-outlined text-primary text-base">task_alt</span>
                                                    Tindakan &amp; Terapi Obat (Implementasi)
                                                </h5>
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs sm:text-sm">
                                                    <div>
                                                        <p class="text-xs font-bold text-on-surface-variant">Tindakan Keperawatan:</p>
                                                        <p class="text-on-surface font-semibold mt-0.5 whitespace-pre-line">{{ $implementasi->tindakan_dilakukan }}</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs font-bold text-on-surface-variant">Resep / Pemberian Obat:</p>
                                                        <p class="text-on-surface font-semibold mt-0.5 whitespace-pre-line">{{ $implementasi->resep_obat ?: '-' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if($evaluasi)
                                            <div class="bg-surface-container-low rounded-xl p-3.5 border border-outline-variant/60 mt-3">
                                                <div class="flex items-center justify-between mb-2.5 pb-2 border-b border-outline-variant/40">
                                                    <h5 class="text-xs font-bold text-on-surface flex items-center gap-1.5">
                                                        <span class="material-symbols-outlined text-primary text-[17px]">rate_review</span>
                                                        Evaluasi (SOAP)
                                                    </h5>
                                                    <div class="flex items-center gap-1.5">
                                                        <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-green-50 text-green-700 border border-green-200">{{ $evaluasi->status_kondisi }}</span>
                                                        <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">{{ $evaluasi->status_evaluasi }}</span>
                                                    </div>
                                                </div>

                                                <div class="space-y-2 text-xs sm:text-sm">
                                                    <div class="flex items-start gap-2.5">
                                                        <span class="w-6 h-6 rounded-md bg-primary text-white font-bold flex items-center justify-center shrink-0 text-xs mt-0.5">S</span>
                                                        <p class="text-on-surface font-medium leading-relaxed">{{ $evaluasi->soap_s ?: ($evaluasi->keluhan_setelah_tindakan ?: '-') }}</p>
                                                    </div>
                                                    <div class="flex items-start gap-2.5">
                                                        <span class="w-6 h-6 rounded-md bg-primary text-white font-bold flex items-center justify-center shrink-0 text-xs mt-0.5">O</span>
                                                        <p class="text-on-surface font-medium leading-relaxed">{{ $evaluasi->soap_o ?: ($evaluasi->respon_pasien ?: '-') }}</p>
                                                    </div>
                                                    <div class="flex items-start gap-2.5">
                                                        <span class="w-6 h-6 rounded-md bg-primary text-white font-bold flex items-center justify-center shrink-0 text-xs mt-0.5">A</span>
                                                        <p class="text-on-surface font-medium leading-relaxed">{{ $evaluasi->soap_a ?: ($evaluasi->hasil_evaluasi ?: '-') }}</p>
                                                    </div>
                                                    <div class="flex items-start gap-2.5">
                                                        <span class="w-6 h-6 rounded-md bg-primary text-white font-bold flex items-center justify-center shrink-0 text-xs mt-0.5">P</span>
                                                        <p class="text-on-surface font-medium leading-relaxed">{{ $evaluasi->soap_p ?: ($evaluasi->rencana_selanjutnya ?: '-') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @elseif($selectedPasien)
                        <!-- Empty State If Patient Has No Medical Records Yet -->
                        <div class="text-center py-12 px-4" id="emptyStateContainer">
                            <div class="w-16 h-16 rounded-full bg-[#E5F5F0] text-primary flex items-center justify-center mx-auto mb-4">
                                <span class="material-symbols-outlined text-3xl">clinical_notes</span>
                            </div>
                            <h4 class="text-base font-bold text-on-surface mb-1">
                                Belum Ada Rekam Medis untuk <span id="emptyStatePasienName">{{ $selectedPasien->nama_lengkap }}</span>
                            </h4>
                            <p class="text-xs sm:text-sm text-on-surface-variant max-w-md mx-auto mb-5">
                                Pasien ini sudah terdaftar di database. Anda dapat langsung mengisi form pengkajian dan rencana asuhan keperawatan sekarang.
                            </p>
                            <a id="emptyStateBtnAsuhan" href="{{ route('asuhan-keperawatan', ['pasien_id' => $selectedPasien->id_pasien]) }}" class="bg-primary text-white font-bold text-xs sm:text-sm px-5 py-2.5 rounded-xl hover:bg-[#005a3c] transition-colors inline-flex items-center gap-2 shadow-sm">
                                <span class="material-symbols-outlined text-base">add</span>
                                <span>Buat Asuhan Keperawatan Baru</span>
                            </a>
                        </div>
                    @else
                        <!-- Initial Prompt State When No Patient Selected Yet -->
                        <div class="text-center py-16 px-4" id="emptyStateContainer">
                            <div class="w-16 h-16 rounded-full bg-[#E5F5F0] text-primary flex items-center justify-center mx-auto mb-4">
                                <span class="material-symbols-outlined text-3xl">touch_app</span>
                            </div>
                            <h4 class="text-base font-bold text-on-surface mb-1">
                                Pilih Pasien Terlebih Dahulu
                            </h4>
                            <p class="text-xs sm:text-sm text-on-surface-variant max-w-md mx-auto">
                                Silakan pilih salah satu pasien dari daftar di sebelah kiri untuk melihat rekam medis, profil, dan riwayat pemeriksaan.
                            </p>
                        </div>
                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

{{-- ========================================================== --}}
{{-- MODAL PILIHAN CETAK PDF REKAM MEDIS --}}
{{-- ========================================================== --}}
<div id="modalCetakRM" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop blur -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModalCetakRM()"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-outline-variant">
            
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-emerald-800 to-emerald-700 p-5 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center text-white backdrop-blur-sm">
                            <span class="material-symbols-outlined text-2xl">picture_as_pdf</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white leading-snug">Cetak PDF Rekam Medis</h3>
                            <p class="text-xs text-emerald-100 mt-0.5">
                                Pasien: <strong id="modalPasienNama" class="text-white">{{ $selectedPasien ? $selectedPasien->nama_lengkap : '-' }}</strong> &bull; RM: <span id="modalPasienRM" class="font-mono font-semibold">{{ $selectedPasien ? $selectedPasien->no_rm : '-' }}</span>
                            </p>
                        </div>
                    </div>
                    <button type="button" onclick="closeModalCetakRM()" class="text-white/80 hover:text-white rounded-lg p-1.5 hover:bg-white/10 transition-colors cursor-pointer">
                        <span class="material-symbols-outlined text-xl">close</span>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-5 sm:p-6 space-y-4">
                <p class="text-xs text-on-surface-variant font-medium">
                    Pilih format riwayat pemeriksaan yang ingin dicetak ke dalam format dokumen PDF:
                </p>

                <!-- Pilihan 1: Pemeriksaan Terkini / Hari Ini (Default & Recommended) -->
                <label class="block border-2 border-primary bg-emerald-50/50 rounded-xl p-3.5 cursor-pointer hover:bg-emerald-50 transition-all option-card" id="card_opt_latest">
                    <div class="flex items-start gap-3">
                        <input type="radio" name="pilihan_cetak_rm" value="latest" id="radio_latest" checked class="mt-1 text-primary focus:ring-primary h-4 w-4" onchange="handleOptionChange()">
                        <div class="flex-1">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-sm font-bold text-on-surface">Pemeriksaan Hari Ini / Terkini</span>
                                <span class="bg-emerald-100 text-emerald-800 border border-emerald-300 px-2 py-0.5 rounded-full text-[10px] font-bold">Rekomendasi</span>
                            </div>
                            <p class="text-xs text-on-surface-variant mt-1 leading-relaxed">
                                Hanya mencetak rangkuman pemeriksaan tanggal <strong id="modalLatestDate" class="text-on-surface">{{ $riwayatKunjungan->isNotEmpty() ? \Carbon\Carbon::parse($riwayatKunjungan->first()->tgl_pemeriksaan)->translatedFormat('d F Y') : '-' }}</strong> saja <em>tanpa mencetak riwayat hari sebelumnya</em>.
                            </p>
                        </div>
                    </div>
                </label>

                <!-- Pilihan 2: Pilih Kunjungan Tertentu -->
                <label class="block border border-outline-variant rounded-xl p-3.5 cursor-pointer hover:border-primary/60 transition-all option-card" id="card_opt_specific">
                    <div class="flex items-start gap-3">
                        <input type="radio" name="pilihan_cetak_rm" value="specific" id="radio_specific" class="mt-1 text-primary focus:ring-primary h-4 w-4" onchange="handleOptionChange()">
                        <div class="flex-1">
                            <span class="text-sm font-bold text-on-surface">Pilih Riwayat Pemeriksaan Tertentu</span>
                            <p class="text-xs text-on-surface-variant mt-0.5 leading-relaxed">
                                Pilih salah satu dari riwayat kunjungan pemeriksaan pasien di hari sebelumnya.
                            </p>
                            
                            <!-- Dropdown Tanggal Kunjungan -->
                            <div class="mt-2.5">
                                <select id="modalSelectKunjungan" class="w-full text-xs bg-white border border-outline-variant rounded-lg p-2 focus:ring-2 focus:ring-primary focus:outline-none" onfocus="document.getElementById('radio_specific').checked = true; handleOptionChange();">
                                    @forelse($riwayatKunjungan as $rm)
                                        <option value="{{ $rm->id_rekam_medis }}">
                                            {{ \Carbon\Carbon::parse($rm->tgl_pemeriksaan)->translatedFormat('d M Y, H:i') }} WIB &bull; Keluhan: {{ Str::limit($rm->asuhanMedis ? $rm->asuhanMedis->keluhan_utama : '-', 35) }}
                                        </option>
                                    @empty
                                        <option value="">Belum ada riwayat pemeriksaan</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                    </div>
                </label>

                <!-- Pilihan 3: Semua Riwayat Pemeriksaan -->
                <label class="block border border-outline-variant rounded-xl p-3.5 cursor-pointer hover:border-primary/60 transition-all option-card" id="card_opt_all">
                    <div class="flex items-start gap-3">
                        <input type="radio" name="pilihan_cetak_rm" value="all" id="radio_all" class="mt-1 text-primary focus:ring-primary h-4 w-4" onchange="handleOptionChange()">
                        <div class="flex-1">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-sm font-bold text-on-surface">Semua Riwayat Pemeriksaan (Lengkap)</span>
                                <span class="bg-slate-100 text-slate-700 border border-slate-200 px-2 py-0.5 rounded-full text-[10px] font-bold">
                                    <span id="modalTotalKunjunganBadge">{{ $riwayatKunjungan->count() }}</span> Kunjungan
                                </span>
                            </div>
                            <p class="text-xs text-on-surface-variant mt-1 leading-relaxed">
                                Mencetak seluruh riwayat pemeriksaan rekam medis pasien dari awal terdaftar hingga kunjungan paling baru sekaligus.
                            </p>
                        </div>
                    </div>
                </label>
            </div>

            <!-- Modal Footer -->
            <div class="bg-surface-container-low px-5 py-4 border-t border-outline-variant flex flex-col-reverse sm:flex-row sm:justify-between items-center gap-2">
                <button type="button" onclick="closeModalCetakRM()" class="w-full sm:w-auto px-4 py-2 border border-outline-variant rounded-xl text-xs font-semibold text-on-surface hover:bg-white transition-colors cursor-pointer">
                    Batal
                </button>
                <div class="w-full sm:w-auto flex items-center gap-2">
                    <button type="button" onclick="prosesCetakPDF(true)" class="flex-1 sm:flex-initial px-3.5 py-2 border border-emerald-300 text-emerald-800 bg-emerald-50 hover:bg-emerald-100 rounded-xl text-xs font-bold inline-flex items-center justify-center gap-1.5 transition-colors cursor-pointer">
                        <span class="material-symbols-outlined text-[15px]">download</span>
                        <span>Unduh PDF</span>
                    </button>
                    <button type="button" onclick="prosesCetakPDF(false)" class="flex-1 sm:flex-initial px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl text-xs font-bold inline-flex items-center justify-center gap-1.5 transition-colors shadow-sm cursor-pointer">
                        <span class="material-symbols-outlined text-[15px]">print</span>
                        <span>Buka / Cetak PDF</span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ========================================================== --}}
{{-- JAVASCRIPT FOR REAL-TIME PATIENT SWITCHING --}}
{{-- ========================================================== --}}
<script>
let currentSelectedPasienId = {{ $selectedPasien ? $selectedPasien->id_pasien : 'null' }};
let currentRiwayatList = @json($riwayatKunjungan->map(function($rm) {
    return [
        'id_rekam_medis' => $rm->id_rekam_medis,
        'tgl_pemeriksaan' => \Carbon\Carbon::parse($rm->tgl_pemeriksaan)->translatedFormat('d F Y'),
        'keluhan_utama' => $rm->asuhanMedis ? $rm->asuhanMedis->keluhan_utama : '-'
    ];
}));

function openModalCetakRM() {
    if (!currentSelectedPasienId) {
        alert('Silakan pilih salah satu pasien terlebih dahulu.');
        return;
    }
    const modal = document.getElementById('modalCetakRM');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
}

function closeModalCetakRM() {
    const modal = document.getElementById('modalCetakRM');
    if (modal) {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
}

function handleOptionChange() {
    const selected = document.querySelector('input[name="pilihan_cetak_rm"]:checked')?.value;
    
    document.querySelectorAll('.option-card').forEach(card => {
        card.classList.remove('border-2', 'border-primary', 'bg-emerald-50/50');
        card.classList.add('border', 'border-outline-variant');
    });
    
    if (selected === 'latest') {
        const card = document.getElementById('card_opt_latest');
        if (card) {
            card.classList.add('border-2', 'border-primary', 'bg-emerald-50/50');
            card.classList.remove('border-outline-variant');
        }
    } else if (selected === 'specific') {
        const card = document.getElementById('card_opt_specific');
        if (card) {
            card.classList.add('border-2', 'border-primary', 'bg-emerald-50/50');
            card.classList.remove('border-outline-variant');
        }
    } else if (selected === 'all') {
        const card = document.getElementById('card_opt_all');
        if (card) {
            card.classList.add('border-2', 'border-primary', 'bg-emerald-50/50');
            card.classList.remove('border-outline-variant');
        }
    }
}

function prosesCetakPDF(isDownload = false) {
    if (!currentSelectedPasienId) {
        alert('Silakan pilih pasien terlebih dahulu.');
        return;
    }

    const pilihan = document.querySelector('input[name="pilihan_cetak_rm"]:checked')?.value || 'latest';
    let url = '';

    if (pilihan === 'latest') {
        if (currentRiwayatList && currentRiwayatList.length > 0) {
            url = "{{ url('/rekam-medis/kunjungan') }}/" + currentRiwayatList[0].id_rekam_medis + "/pdf";
        } else {
            url = "{{ url('/rekam-medis/pasien') }}/" + currentSelectedPasienId + "/pdf?mode=latest";
        }
    } else if (pilihan === 'specific') {
        const select = document.getElementById('modalSelectKunjungan');
        const rmId = select ? select.value : null;
        if (!rmId) {
            alert('Silakan pilih salah satu riwayat pemeriksaan dari dropdown.');
            return;
        }
        url = "{{ url('/rekam-medis/kunjungan') }}/" + rmId + "/pdf";
    } else if (pilihan === 'all') {
        url = "{{ url('/rekam-medis/pasien') }}/" + currentSelectedPasienId + "/pdf?mode=all";
    }

    if (isDownload) {
        url += (url.includes('?') ? '&' : '?') + 'download=1';
    }

    window.open(url, '_blank');
    closeModalCetakRM();
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModalCetakRM();
    }
});
function filterPasienRM(keyword) {
    const term = keyword.toLowerCase().trim();
    const cards = document.querySelectorAll('.pasien-rm-card');
    cards.forEach(card => {
        const text = card.textContent.toLowerCase();
        if (text.includes(term)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

function escapeHtml(text) {
    if (!text) return '';
    return String(text)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function selectPasienRM(id) {
    // Highlight selected card in sidebar
    document.querySelectorAll('.pasien-rm-card').forEach(c => {
        if (c.getAttribute('data-id') == id) {
            c.className = 'pasien-rm-card border border-primary bg-[#E5F5F0]/30 rounded-xl p-3.5 cursor-pointer hover:border-primary transition-all group';
        } else {
            c.className = 'pasien-rm-card border border-outline-variant/70 bg-white rounded-xl p-3.5 cursor-pointer hover:border-primary transition-all group';
        }
    });

    fetch("{{ url('/rekam-medis/pasien') }}/" + id, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) {
            alert(data.message || 'Gagal mengambil data.');
            return;
        }

        const p = data.pasien;
        const riwayat = data.riwayat;

        // 1. Update Profile Card
        document.getElementById('selectedName').textContent = p.nama_lengkap;
        document.getElementById('selectedAvatar').textContent = p.initials;
        document.getElementById('selectedGenderAge').textContent = p.gender_age;
        document.getElementById('selectedRM').textContent = p.no_rm;
        document.getElementById('selectedNik').textContent = p.nik;
        document.getElementById('selectedBlood').innerHTML = '<span class="material-symbols-outlined text-[15px]">bloodtype</span> <span>' + p.golongan_darah + '</span>';
        
        // Alergi Obat Display
        const allergyContainer = document.getElementById('selectedAllergy');
        if (p.alergi && p.alergi !== 'Tidak Ada') {
            allergyContainer.innerHTML = `
                <span class="bg-red-50 text-red-700 border border-red-200 px-2 py-0.5 rounded text-xs font-bold inline-flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">warning</span>
                    ${p.alergi}
                </span>
            `;
        } else {
            allergyContainer.innerHTML = '<span class="text-on-surface font-semibold text-xs">Tidak Ada</span>';
        }

        document.getElementById('selectedLastVisit').textContent = p.last_visit;
        const tbbbElem = document.getElementById('selectedTBBB');
        if (tbbbElem) {
            if (riwayat && riwayat.length > 0 && (riwayat[0].tanda_vital.tb || riwayat[0].tanda_vital.bb)) {
                let txt = `${riwayat[0].tanda_vital.tb ? riwayat[0].tanda_vital.tb + ' cm' : '-'} / ${riwayat[0].tanda_vital.bb ? riwayat[0].tanda_vital.bb + ' kg' : '-'}`;
                if (riwayat[0].tanda_vital.imt) {
                    txt += ` <span class="text-purple-700 text-xs font-bold">(IMT: ${riwayat[0].tanda_vital.imt})</span>`;
                }
                tbbbElem.innerHTML = txt;
            } else {
                tbbbElem.textContent = '-';
            }
        }
        document.getElementById('headerPatientName').textContent = p.nama_lengkap;

        // Update URLs and current patient state
        const asuhanUrl = "{{ url('/asuhan-keperawatan') }}?pasien_id=" + p.id_pasien;
        currentSelectedPasienId = p.id_pasien;
        currentRiwayatList = riwayat;

        const topBtn = document.getElementById('topBtnAsuhan');
        const actionBtn = document.getElementById('btnActionAsuhan');
        if (topBtn) {
            topBtn.href = asuhanUrl;
            topBtn.classList.remove('hidden');
        }
        if (actionBtn) actionBtn.href = asuhanUrl;

        // Update modal data
        const modalNama = document.getElementById('modalPasienNama');
        if (modalNama) modalNama.textContent = p.nama_lengkap;
        const modalRM = document.getElementById('modalPasienRM');
        if (modalRM) modalRM.textContent = p.no_rm;
        const modalDate = document.getElementById('modalLatestDate');
        if (modalDate) modalDate.textContent = riwayat.length > 0 ? riwayat[0].tgl_pemeriksaan : '-';
        const modalBadge = document.getElementById('modalTotalKunjunganBadge');
        if (modalBadge) modalBadge.textContent = riwayat.length;

        // Update select options in modal
        const selectKunjungan = document.getElementById('modalSelectKunjungan');
        if (selectKunjungan) {
            selectKunjungan.innerHTML = '';
            if (riwayat.length === 0) {
                selectKunjungan.innerHTML = '<option value="">Belum ada riwayat pemeriksaan</option>';
            } else {
                riwayat.forEach((item, idx) => {
                    const opt = document.createElement('option');
                    opt.value = item.id_rekam_medis;
                    const keluhan = item.keluhan_utama ? item.keluhan_utama.substring(0, 35) : '-';
                    opt.textContent = `${item.tgl_pemeriksaan} • Keluhan: ${keluhan}`;
                    selectKunjungan.appendChild(opt);
                });
            }
        }

        const actionPdfBtn = document.getElementById('btnDownloadPdf');
        if (riwayat.length > 0) {
            if (actionPdfBtn) actionPdfBtn.classList.remove('hidden');
        } else {
            if (actionPdfBtn) actionPdfBtn.classList.add('hidden');
        }

        // 2. Render Timeline Visits
        document.getElementById('visitCountBadge').textContent = `${riwayat.length} Kunjungan`;
        const container = document.getElementById('timelineContainer');

        if (riwayat.length === 0) {
            container.innerHTML = `
                <div class="text-center py-12 px-4" id="emptyStateContainer">
                    <div class="w-16 h-16 rounded-full bg-[#E5F5F0] text-primary flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined text-3xl">clinical_notes</span>
                    </div>
                    <h4 class="text-base font-bold text-on-surface mb-1">
                        Belum Ada Rekam Medis untuk ${p.nama_lengkap}
                    </h4>
                    <p class="text-xs sm:text-sm text-on-surface-variant max-w-md mx-auto mb-5">
                        Pasien ini sudah terdaftar di database. Anda dapat langsung mengisi form pengkajian dan rencana asuhan keperawatan sekarang.
                    </p>
                    <a href="${asuhanUrl}" class="bg-primary text-white font-bold text-xs sm:text-sm px-5 py-2.5 rounded-xl hover:bg-[#005a3c] transition-colors inline-flex items-center gap-2 shadow-sm">
                        <span class="material-symbols-outlined text-base">add</span>
                        <span>Buat Asuhan Keperawatan Baru</span>
                    </a>
                </div>
            `;
            container.scrollTop = 0;
            return;
        }

        let html = `
            <div class="relative" id="timelineContentWrapper">
                <div class="absolute left-[31px] sm:left-[39px] top-6 bottom-6 w-0.5 bg-outline-variant/60"></div>
                <div class="space-y-6" id="visitTimelineList">
        `;

        riwayat.forEach((item, idx) => {
            const planListHtml = (item.rencana_tindakan && item.rencana_tindakan.length > 0)
                ? `<ul class="list-disc list-inside mt-0.5 space-y-0.5 text-on-surface">${item.rencana_tindakan.map(pl => `<li>${pl}</li>`).join('')}</ul>`
                : `<p class="text-on-surface mt-0.5">Observasi dan tindakan keperawatan berkala.</p>`;

            const allergyWarningHtml = (p.alergi && p.alergi !== 'Tidak Ada') 
                ? `<div class="mb-3 p-2 bg-red-50 border border-red-200 rounded-lg flex items-center gap-2 text-xs font-semibold text-red-700"><span class="material-symbols-outlined text-[16px]">warning</span> Peringatan Alergi: <span class="font-bold underline">${p.alergi}</span></div>` 
                : '';

            const badgeStatus = (idx === 0)
                ? `<span class="bg-emerald-100 text-emerald-800 border border-emerald-300 px-2 py-0.5 rounded-full text-[10px] font-bold">Pemeriksaan Terkini</span>`
                : `<span class="bg-slate-100 text-slate-700 border border-slate-200 px-2 py-0.5 rounded-full text-[10px] font-semibold">Riwayat Sebelumnya</span>`;

            const btnText = (idx === 0) ? 'Cetak Rangkuman Hari Ini' : 'Cetak Rangkuman Ini';

            html += `
                <div class="relative pl-10 sm:pl-12 group visit-entry">
                    <div class="absolute left-[16px] sm:left-[24px] top-1 w-8 h-8 rounded-full bg-[#E5F5F0] text-primary flex items-center justify-center border-4 border-white shadow-sm z-10 font-bold">
                        <span class="material-symbols-outlined text-[16px]">stethoscope</span>
                    </div>

                    <div class="bg-surface border border-outline-variant/80 rounded-xl p-4 sm:p-5 shadow-sm hover:shadow-md transition-all relative overflow-hidden">
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-primary"></div>

                        ${allergyWarningHtml}

                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-4 gap-2 border-b border-outline-variant/50 pb-3">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <h4 class="text-sm sm:text-base font-bold text-on-surface">
                                        Kunjungan Pemeriksaan
                                    </h4>
                                    ${badgeStatus}
                                    <span class="bg-[#E5F5F0] text-primary px-2.5 py-0.5 rounded-full text-[10px] font-bold">
                                        Tersimpan
                                    </span>
                                </div>
                                <p class="text-xs text-on-surface-variant flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[14px]">event</span>
                                    ${item.tgl_pemeriksaan} &bull; No. RM: ${p.no_rm}
                                </p>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <a href="{{ url('/rekam-medis/kunjungan') }}/${item.id_rekam_medis}/pdf" target="_blank" class="text-white bg-emerald-700 hover:bg-emerald-800 px-3.5 py-1.5 rounded-lg text-xs font-bold inline-flex items-center gap-1.5 transition-all shadow-sm" title="Cetak rangkuman rekam medis khusus pemeriksaan tanggal ini saja">
                                    <span class="material-symbols-outlined text-[15px]">picture_as_pdf</span>
                                    <span>${btnText}</span>
                                </a>
                                <a href="${asuhanUrl}" class="text-primary hover:underline text-xs font-bold inline-flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[15px]">edit_note</span>
                                    <span>Buka di Asuhan</span>
                                </a>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="bg-surface-container-low p-3.5 rounded-xl border border-outline-variant/50">
                                <p class="text-xs font-bold text-primary mb-1 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[15px]">chat_bubble</span>
                                    Keluhan Pasien (Spesifik)
                                </p>
                                <p class="text-xs sm:text-sm font-semibold text-on-surface">
                                    ${item.keluhan_utama}
                                </p>
                                ${item.riwayat_keluhan && item.riwayat_keluhan !== '-' ? `<p class="text-xs text-on-surface-variant mt-1 italic">Riwayat: ${item.riwayat_keluhan}</p>` : ''}
                            </div>

                            <div class="bg-surface-container-low p-3.5 rounded-xl border border-outline-variant/50">
                                <p class="text-xs font-bold text-on-surface-variant mb-2 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[15px]">vital_signs</span>
                                    Tanda-tanda Vital
                                </p>
                                <div class="flex flex-wrap gap-1.5">
                                    <span class="bg-white px-2.5 py-1 rounded-lg text-xs font-semibold text-on-surface border border-outline-variant/60">
                                        TD: ${item.tanda_vital.td}
                                    </span>
                                    <span class="bg-white px-2.5 py-1 rounded-lg text-xs font-semibold text-red-600 border border-red-200">
                                        Suhu: ${item.tanda_vital.suhu}°C
                                    </span>
                                    <span class="bg-white px-2.5 py-1 rounded-lg text-xs font-semibold text-on-surface border border-outline-variant/60">
                                        Nadi: ${item.tanda_vital.nadi} x/m
                                    </span>
                                    <span class="bg-white px-2.5 py-1 rounded-lg text-xs font-semibold text-on-surface border border-outline-variant/60">
                                        RR: ${item.tanda_vital.rr} x/m
                                    </span>
                                    ${item.tanda_vital.spo2 ? `<span class="bg-white px-2.5 py-1 rounded-lg text-xs font-semibold text-blue-600 border border-blue-200">SpO2: ${item.tanda_vital.spo2}%</span>` : ''}
                                    ${item.tanda_vital.tb ? `<span class="bg-white px-2.5 py-1 rounded-lg text-xs font-semibold text-emerald-700 border border-emerald-200">TB: ${item.tanda_vital.tb} cm</span>` : ''}
                                    ${item.tanda_vital.bb ? `<span class="bg-white px-2.5 py-1 rounded-lg text-xs font-semibold text-emerald-700 border border-emerald-200">BB: ${item.tanda_vital.bb} kg</span>` : ''}
                                    ${item.tanda_vital.imt ? `<span class="bg-white px-2.5 py-1 rounded-lg text-xs font-semibold text-purple-700 border border-purple-200">IMT: ${item.tanda_vital.imt}</span>` : ''}
                                </div>
                            </div>
                        </div>

                        <div class="bg-[#F8FAFC] rounded-xl p-3.5 sm:p-4 border border-outline-variant/60">
                            <h5 class="text-xs font-bold text-on-surface mb-2 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-primary text-base">assignment</span>
                                Diagnosis &amp; Rencana Tindakan
                            </h5>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs sm:text-sm">
                                <div>
                                    <p class="text-xs font-bold text-on-surface-variant">Diagnosis / Masalah:</p>
                                    <p class="text-on-surface font-medium mt-0.5 whitespace-pre-line">${escapeHtml(item.diagnosa)}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-on-surface-variant">Rencana Tindakan:</p>
                                    ${planListHtml}
                                </div>
                            </div>
                            ${item.prioritas_diagnosa ? `
                            <div class="mt-3 pt-2.5 border-t border-outline-variant/50">
                                <div class="flex items-start gap-2 bg-amber-50/80 border border-amber-200 rounded-lg p-2.5 text-xs">
                                    <span class="material-symbols-outlined text-amber-700 text-base flex-shrink-0 mt-0.5">push_pin</span>
                                    <div class="min-w-0 flex-1">
                                        <span class="font-bold text-amber-900 block mb-0.5">Prioritas Diagnosa &amp; Catatan Khusus:</span>
                                        <p class="text-slate-800 whitespace-pre-line leading-relaxed">${escapeHtml(item.prioritas_diagnosa)}</p>
                                    </div>
                                </div>
                            </div>
                            ` : ''}
                        </div>

                        ${item.implementasi ? `
                        <div class="bg-[#F8FAFC] rounded-xl p-3.5 sm:p-4 border border-outline-variant/60 mt-3">
                            <h5 class="text-xs font-bold text-on-surface mb-2 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-primary text-base">task_alt</span>
                                Tindakan &amp; Terapi Obat (Implementasi)
                            </h5>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs sm:text-sm">
                                <div>
                                    <p class="text-xs font-bold text-on-surface-variant">Tindakan Keperawatan:</p>
                                    <p class="text-on-surface font-semibold text-on-surface mt-0.5 whitespace-pre-line">${item.implementasi.tindakan_dilakukan}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-on-surface-variant">Resep / Pemberian Obat:</p>
                                    <p class="text-on-surface font-semibold text-on-surface mt-0.5 whitespace-pre-line">${item.implementasi.resep_obat}</p>
                                </div>
                            </div>
                        </div>
                        ` : ''}

                        ${item.evaluasi ? `
                        <div class="bg-surface-container-low rounded-xl p-3.5 border border-outline-variant/60 mt-3">
                            <div class="flex items-center justify-between mb-2.5 pb-2 border-b border-outline-variant/40">
                                <h5 class="text-xs font-bold text-on-surface flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-primary text-[17px]">rate_review</span>
                                    Evaluasi (SOAP)
                                </h5>
                                <div class="flex items-center gap-1.5">
                                    <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-green-50 text-green-700 border border-green-200">${item.evaluasi.status_kondisi}</span>
                                    <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">${item.evaluasi.status_evaluasi}</span>
                                </div>
                            </div>

                            <div class="space-y-2 text-xs sm:text-sm">
                                <div class="flex items-start gap-2.5">
                                    <span class="w-6 h-6 rounded-md bg-primary text-white font-bold flex items-center justify-center shrink-0 text-xs mt-0.5">S</span>
                                    <p class="text-on-surface font-medium leading-relaxed">${escapeHtml(item.evaluasi.soap_s || item.evaluasi.keluhan_setelah_tindakan || '-')}</p>
                                </div>
                                <div class="flex items-start gap-2.5">
                                    <span class="w-6 h-6 rounded-md bg-primary text-white font-bold flex items-center justify-center shrink-0 text-xs mt-0.5">O</span>
                                    <p class="text-on-surface font-medium leading-relaxed">${escapeHtml(item.evaluasi.soap_o || item.evaluasi.respon_pasien || '-')}</p>
                                </div>
                                <div class="flex items-start gap-2.5">
                                    <span class="w-6 h-6 rounded-md bg-primary text-white font-bold flex items-center justify-center shrink-0 text-xs mt-0.5">A</span>
                                    <p class="text-on-surface font-medium leading-relaxed">${escapeHtml(item.evaluasi.soap_a || item.evaluasi.hasil_evaluasi || '-')}</p>
                                </div>
                                <div class="flex items-start gap-2.5">
                                    <span class="w-6 h-6 rounded-md bg-primary text-white font-bold flex items-center justify-center shrink-0 text-xs mt-0.5">P</span>
                                    <p class="text-on-surface font-medium leading-relaxed">${escapeHtml(item.evaluasi.soap_p || item.evaluasi.rencana_selanjutnya || '-')}</p>
                                </div>
                            </div>
                        </div>
                        ` : ''}

                    </div>
                </div>
            `;
        });

        html += `
                </div>
            </div>
        `;
        container.innerHTML = html;
        container.scrollTop = 0;
    })
    .catch(err => {
        console.error(err);
        alert('Terjadi kesalahan saat memuat rekam medis pasien.');
    });
}
</script>
@endsection
