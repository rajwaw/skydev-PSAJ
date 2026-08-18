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
        @if($selectedPasien)
            <a href="{{ route('asuhan-keperawatan', ['pasien_id' => $selectedPasien->id_pasien]) }}" id="topBtnAsuhan" class="bg-primary text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-[#005a3c] transition-colors inline-flex items-center gap-2 shadow-sm">
                <span class="material-symbols-outlined text-base">clinical_notes</span>
                <span>Asuhan Keperawatan Baru</span>
            </a>
        @endif
    </div>

    <!-- Bento Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- Left Column (Search & Profile Summary) -->
        <div class="lg:col-span-4 flex flex-col gap-6 w-full min-w-0">

            <!-- Search Pasien Card -->
            <div class="bg-white rounded-xl border border-outline-variant shadow-sm p-4 sm:p-6">
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
                <div class="space-y-2 max-h-60 overflow-y-auto pr-1" id="pasienListRM">
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
            <div class="bg-white rounded-xl border border-outline-variant shadow-sm p-4 sm:p-6 flex flex-col">
                <div class="flex items-center gap-3.5 mb-5">
                    <div id="selectedAvatar" class="w-13 h-13 rounded-full bg-primary/10 text-primary flex items-center justify-center text-lg font-bold shrink-0 p-3">
                        {{ $selectedPasien ? $selectedPasien->initials : 'PS' }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 id="selectedName" class="text-lg font-bold text-on-surface leading-tight truncate">
                            {{ $selectedPasien ? $selectedPasien->nama_lengkap : 'Belum Memilih Pasien' }}
                        </h3>
                        <p id="selectedGenderAge" class="text-xs text-on-surface-variant mt-0.5 truncate">
                            {{ $selectedPasien ? $selectedPasien->formatted_jk . ', ' . $selectedPasien->age : '-' }}
                        </p>
                    </div>
                </div>

                <div class="space-y-3 mb-6 flex-1 text-xs sm:text-sm">
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
                </div>

                <div class="flex flex-col gap-2.5 mt-auto">
                    <a
                        id="btnActionAsuhan"
                        href="{{ $selectedPasien ? route('asuhan-keperawatan', ['pasien_id' => $selectedPasien->id_pasien]) : route('asuhan-keperawatan') }}"
                        class="w-full bg-primary text-white py-2.5 rounded-xl text-xs font-bold hover:bg-[#005a3c] transition-colors flex justify-center items-center gap-2 shadow-sm text-center">
                        <span class="material-symbols-outlined text-base">clinical_notes</span>
                        <span>Input Asuhan Keperawatan</span>
                    </a>

                    <a
                        href="{{ route('pasien') }}"
                        class="w-full bg-surface border border-outline-variant text-on-surface py-2.5 rounded-xl text-xs font-semibold hover:bg-surface-container-low transition-colors flex justify-center items-center gap-1.5 text-center">
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
                        <!-- Vertical Timeline Line -->
                        <div class="absolute left-[31px] sm:left-[39px] top-6 bottom-6 w-0.5 bg-outline-variant/60"></div>

                        <div class="space-y-6" id="visitTimelineList">
                            @foreach($riwayatKunjungan as $index => $rm)
                                @php
                                    $asuhan = $rm->asuhanMedis;
                                    $intervensiList = $rm->intervensi;
                                    $firstIntervensi = $intervensiList->first();
                                    $diagnosa = $firstIntervensi ? $firstIntervensi->diagnosa_awal : ($asuhan ? $asuhan->keluhan_utama : 'Pemeriksaan Umum');
                                    $planList = $intervensiList->pluck('rencana_tindakan')->filter()->values();
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
                                                    <span class="bg-[#E5F5F0] text-primary px-2.5 py-0.5 rounded-full text-[11px] font-bold">
                                                        Tersimpan
                                                    </span>
                                                </div>
                                                <p class="text-xs text-on-surface-variant flex items-center gap-1.5">
                                                    <span class="material-symbols-outlined text-[14px]">event</span>
                                                    {{ \Carbon\Carbon::parse($rm->tgl_pemeriksaan)->translatedFormat('d F Y, H:i') }} WIB
                                                    &bull; No. RM: {{ $selectedPasien->no_rm }}
                                                </p>
                                            </div>

                                            <a href="{{ route('asuhan-keperawatan', ['pasien_id' => $selectedPasien->id_pasien]) }}" class="text-primary hover:underline text-xs font-bold inline-flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[15px]">edit_note</span>
                                                Buka di Asuhan
                                            </a>
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
                                                    <p class="text-on-surface font-medium mt-0.5">{{ $diagnosa }}</p>
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
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Empty State If Patient Has No Medical Records Yet -->
                        <div class="text-center py-12 px-4" id="emptyStateContainer">
                            <div class="w-16 h-16 rounded-full bg-[#E5F5F0] text-primary flex items-center justify-center mx-auto mb-4">
                                <span class="material-symbols-outlined text-3xl">clinical_notes</span>
                            </div>
                            <h4 class="text-base font-bold text-on-surface mb-1">
                                Belum Ada Rekam Medis untuk <span id="emptyStatePasienName">{{ $selectedPasien ? $selectedPasien->nama_lengkap : 'Pasien Ini' }}</span>
                            </h4>
                            <p class="text-xs sm:text-sm text-on-surface-variant max-w-md mx-auto mb-5">
                                Pasien ini sudah terdaftar di database. Anda dapat langsung mengisi form pengkajian dan rencana asuhan keperawatan sekarang.
                            </p>
                            @if($selectedPasien)
                                <a id="emptyStateBtnAsuhan" href="{{ route('asuhan-keperawatan', ['pasien_id' => $selectedPasien->id_pasien]) }}" class="bg-primary text-white font-bold text-xs sm:text-sm px-5 py-2.5 rounded-xl hover:bg-[#005a3c] transition-colors inline-flex items-center gap-2 shadow-sm">
                                    <span class="material-symbols-outlined text-base">add</span>
                                    <span>Buat Asuhan Keperawatan Baru</span>
                                </a>
                            @endif
                        </div>
                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

{{-- ========================================================== --}}
{{-- JAVASCRIPT FOR REAL-TIME PATIENT SWITCHING --}}
{{-- ========================================================== --}}
<script>
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
        document.getElementById('headerPatientName').textContent = p.nama_lengkap;

        // Update URLs
        const asuhanUrl = "{{ url('/asuhan-keperawatan') }}?pasien_id=" + p.id_pasien;
        const topBtn = document.getElementById('topBtnAsuhan');
        const actionBtn = document.getElementById('btnActionAsuhan');
        if (topBtn) topBtn.href = asuhanUrl;
        if (actionBtn) actionBtn.href = asuhanUrl;

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
            return;
        }

        let html = `
            <div class="absolute left-[31px] sm:left-[39px] top-6 bottom-6 w-0.5 bg-outline-variant/60"></div>
            <div class="space-y-6">
        `;

        riwayat.forEach(item => {
            const planListHtml = (item.rencana_tindakan && item.rencana_tindakan.length > 0)
                ? `<ul class="list-disc list-inside mt-0.5 space-y-0.5 text-on-surface">${item.rencana_tindakan.map(pl => `<li>${pl}</li>`).join('')}</ul>`
                : `<p class="text-on-surface mt-0.5">Observasi dan tindakan keperawatan berkala.</p>`;

            const allergyWarningHtml = (p.alergi && p.alergi !== 'Tidak Ada') 
                ? `<div class="mb-3 p-2 bg-red-50 border border-red-200 rounded-lg flex items-center gap-2 text-xs font-semibold text-red-700"><span class="material-symbols-outlined text-[16px]">warning</span> Peringatan Alergi: <span class="font-bold underline">${p.alergi}</span></div>` 
                : '';

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
                                    <span class="bg-[#E5F5F0] text-primary px-2.5 py-0.5 rounded-full text-[11px] font-bold">
                                        Tersimpan
                                    </span>
                                </div>
                                <p class="text-xs text-on-surface-variant flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[14px]">event</span>
                                    ${item.tgl_pemeriksaan} &bull; No. RM: ${p.no_rm}
                                </p>
                            </div>

                            <a href="${asuhanUrl}" class="text-primary hover:underline text-xs font-bold inline-flex items-center gap-1">
                                <span class="material-symbols-outlined text-[15px]">edit_note</span>
                                Buka di Asuhan
                            </a>
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
                                    <p class="text-on-surface font-medium mt-0.5">${item.diagnosa}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-on-surface-variant">Rencana Tindakan:</p>
                                    ${planListHtml}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            `;
        });

        html += `</div>`;
        container.innerHTML = html;
    })
    .catch(err => {
        console.error(err);
        alert('Terjadi kesalahan saat memuat rekam medis pasien.');
    });
}
</script>
@endsection
