@extends('layouts.app')

@section('title', 'Rekam Medis - Mandalacare')

@section('content')
<div class="p-6 md:p-8 lg:p-10 w-full max-w-container-max mx-auto flex-1 flex flex-col gap-6">
    <!-- Page Header -->
    <div>
        <h1 class="text-3xl font-bold text-on-surface">Rekam Medis</h1>
        <p class="text-base text-on-surface-variant mt-1">Kelola dan lihat riwayat pemeriksaan medis pasien</p>
    </div>

    <!-- Bento Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Left Column (Search & Profile Summary) -->
        <div class="lg:col-span-4 flex flex-col gap-6">
            <!-- Search Pasien Card -->
            <div class="bg-white rounded-xl border border-outline-variant shadow-sm p-6">
                <label class="block text-sm font-semibold text-on-surface mb-2">Cari Pasien</label>
                <div class="flex gap-2 mb-4">
                    <div class="relative flex-1">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-sm">search</span>
                        <input id="rekamSearchInput" class="w-full pl-9 pr-3 py-2 bg-surface-container-low border border-outline-variant rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="Cari nama, NIK..." type="text" value="Andi Pratama">
                    </div>
                    <button type="button" onclick="searchPasienRM()" class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#005a3c] transition-colors">
                        Cari
                    </button>
                </div>

                <!-- Search Result -->
                <div class="border border-outline-variant rounded-lg p-4 bg-surface-container-low/50 cursor-pointer hover:border-primary transition-colors group" onclick="selectPasienRM('Andi Pratama', '3271••••890', 'RM-2026-089', 'Laki-laki, 34 Tahun', 'O+', 'Tidak Ada', '7 Agt 2026')">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h4 id="searchResultName" class="text-sm font-semibold text-on-surface group-hover:text-primary transition-colors">Andi Pratama</h4>
                            <p id="searchResultNik" class="text-xs text-on-surface-variant">NIK: 3271••••890</p>
                        </div>
                        <span class="bg-surface-container border border-outline-variant text-on-surface-variant px-2 py-0.5 rounded-md text-xs font-medium">Terpilih</span>
                    </div>
                    <div class="flex justify-between items-center mt-3 pt-3 border-t border-outline-variant/50">
                        <span class="text-xs text-on-surface-variant">Kunjungan: 7 Agt 2026</span>
                        <button type="button" class="text-primary text-xs font-semibold flex items-center hover:underline">
                            Lihat <span class="material-symbols-outlined text-[16px] ml-1">arrow_forward</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Pasien Terpilih Card -->
            <div class="bg-white rounded-xl border border-outline-variant shadow-sm p-6 flex-1 flex flex-col">
                <div class="flex items-center gap-4 mb-6">
                    <div id="selectedAvatar" class="w-16 h-16 rounded-full bg-secondary-container text-white flex items-center justify-center text-xl font-bold">
                        AP
                    </div>
                    <div>
                        <h3 id="selectedName" class="text-xl font-semibold text-on-surface leading-tight">Andi Pratama</h3>
                        <p id="selectedGenderAge" class="text-sm text-on-surface-variant">Laki-laki, 34 Tahun</p>
                    </div>
                </div>

                <div class="space-y-4 mb-8 flex-1">
                    <div class="flex justify-between border-b border-outline-variant/50 pb-2">
                        <span class="text-sm text-on-surface-variant">No. RM</span>
                        <span id="selectedRM" class="text-sm font-semibold text-on-surface">RM-2026-089</span>
                    </div>
                    <div class="flex justify-between border-b border-outline-variant/50 pb-2">
                        <span class="text-sm text-on-surface-variant">Gol. Darah</span>
                        <span id="selectedBlood" class="text-sm font-bold text-red-600 flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">bloodtype</span> O+
                        </span>
                    </div>
                    <div class="flex justify-between border-b border-outline-variant/50 pb-2">
                        <span class="text-sm text-on-surface-variant">Alergi</span>
                        <span id="selectedAllergy" class="text-sm font-semibold text-on-surface">Tidak Ada</span>
                    </div>
                    <div class="flex justify-between border-b border-outline-variant/50 pb-2">
                        <span class="text-sm text-on-surface-variant">Terakhir Datang</span>
                        <span id="selectedLastVisit" class="text-sm font-semibold text-on-surface">7 Agt 2026</span>
                    </div>
                </div>

                <div class="flex flex-col gap-3 mt-auto">
                    <a href="{{ route('pendaftaran') }}" class="w-full bg-primary text-white py-3 rounded-lg text-sm font-semibold hover:bg-[#005a3c] transition-colors flex justify-center items-center gap-2 shadow-sm">
                        <span class="material-symbols-outlined text-[20px]">add</span>
                        Pemeriksaan Baru
                    </a>
                    <a href="{{ route('pasien') }}" class="w-full bg-transparent border border-secondary text-secondary py-3 rounded-lg text-sm font-semibold hover:bg-secondary/5 transition-colors flex justify-center items-center">
                        Detail Pasien
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Column (Riwayat Kunjungan) -->
        <div class="lg:col-span-8 flex flex-col gap-6">
            <!-- Timeline Card -->
            <div class="bg-white rounded-xl border border-outline-variant shadow-sm flex-1 flex flex-col">
                <div class="p-6 border-b border-outline-variant flex justify-between items-center bg-surface-container-low/30 rounded-t-xl">
                    <h3 class="text-xl font-semibold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">history</span>
                        Riwayat Kunjungan
                    </h3>
                    <button type="button" class="text-on-surface-variant hover:text-primary p-2 rounded-full transition-colors" title="Filter Riwayat">
                        <span class="material-symbols-outlined">filter_list</span>
                    </button>
                </div>
                <div class="p-6 flex-1 relative">
                    <!-- Vertical Timeline Line -->
                    <div class="absolute left-[39px] top-6 bottom-6 w-0.5 bg-outline-variant/50"></div>

                    <!-- Visit 1 (Expanded) -->
                    <div class="relative pl-12 mb-8 group">
                        <!-- Timeline Node -->
                        <div class="absolute left-[24px] top-1 w-8 h-8 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center border-4 border-white shadow-sm z-10">
                            <span class="material-symbols-outlined text-[16px]">stethoscope</span>
                        </div>
                        <div class="bg-surface border border-primary/20 rounded-xl p-5 shadow-sm relative overflow-hidden">
                            <!-- Subtle active indicator -->
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary"></div>
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <div class="flex items-center gap-3 mb-1">
                                        <h4 class="text-base font-semibold text-on-surface">Pemeriksaan Umum</h4>
                                        <span class="bg-primary-container/20 text-primary px-2.5 py-0.5 rounded-full text-xs font-semibold">Selesai</span>
                                    </div>
                                    <p class="text-xs text-on-surface-variant">7 Agustus 2026 • dr. Budi Santoso</p>
                                </div>
                            </div>
                            <!-- Quick Summary -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <p class="text-xs font-medium text-on-surface-variant mb-1">Keluhan Utama</p>
                                    <p class="text-sm text-on-surface">Demam dan batuk berdahak sejak 3 hari lalu.</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-on-surface-variant mb-1">Tanda Vital</p>
                                    <div class="flex flex-wrap gap-2">
                                        <span class="bg-surface-container-high px-2 py-1 rounded text-xs text-on-surface font-medium border border-outline-variant/50">TD 120/80</span>
                                        <span class="bg-red-50 px-2 py-1 rounded text-xs text-red-600 font-medium border border-red-200">Suhu 37.8°C</span>
                                        <span class="bg-surface-container-high px-2 py-1 rounded text-xs text-on-surface font-medium border border-outline-variant/50">Nadi 82</span>
                                        <span class="bg-surface-container-high px-2 py-1 rounded text-xs text-on-surface font-medium border border-outline-variant/50">RR 20x</span>
                                    </div>
                                </div>
                            </div>
                            <!-- SOAP Section (Compact) -->
                            <div class="bg-surface-container-low rounded-lg p-4 mt-4 border border-outline-variant/50">
                                <h5 class="text-sm font-semibold text-on-surface mb-3 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[18px]">assignment</span>
                                    Evaluasi SOAP
                                </h5>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                                    <div>
                                        <p class="text-xs font-medium text-on-surface-variant">Assessment (A)</p>
                                        <p class="text-sm text-on-surface mt-1">Diagnosis Awal: J06.9 - Acute upper respiratory infection.</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-on-surface-variant">Plan (P)</p>
                                        <p class="text-sm text-on-surface mt-1">Paracetamol 500mg (3x1), Istirahat cukup, edukasi batuk.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 pt-4 border-t border-outline-variant/50 flex justify-end">
                                <button type="button" onclick="alert('Detail SOAP lengkap siap diunduh / dicetak.')" class="text-secondary text-sm font-semibold hover:underline flex items-center gap-1">
                                    Lihat Detail Lengkap
                                    <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Visit 2 (Collapsible) -->
                    <div class="relative pl-12 group">
                        <!-- Timeline Node -->
                        <div class="absolute left-[24px] top-1 w-8 h-8 rounded-full bg-surface text-on-surface-variant flex items-center justify-center border-4 border-white shadow-sm z-10 group-hover:border-primary/20 transition-colors">
                            <span class="material-symbols-outlined text-[16px]">stethoscope</span>
                        </div>
                        <div class="bg-white border border-outline-variant rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-center cursor-pointer" onclick="toggleVisit2Details()">
                                <div>
                                    <div class="flex items-center gap-3 mb-1">
                                        <h4 class="text-base font-semibold text-on-surface">Pemeriksaan Umum</h4>
                                        <span class="bg-primary-container/20 text-primary px-2.5 py-0.5 rounded-full text-xs font-semibold">Selesai</span>
                                    </div>
                                    <p class="text-xs text-on-surface-variant">1 Agustus 2026 • dr. Siti Aminah</p>
                                </div>
                                <span id="visit2ToggleIcon" class="material-symbols-outlined text-on-surface-variant transition-transform">expand_more</span>
                            </div>
                            <div class="mt-3 flex flex-wrap gap-x-6 gap-y-2">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[16px] text-on-surface-variant">chat_bubble</span>
                                    <span class="text-sm text-on-surface">Keluhan: Sakit kepala</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[16px] text-on-surface-variant">favorite</span>
                                    <span class="text-sm text-on-surface">TD: 118/78, Suhu: 36.8°C</span>
                                </div>
                            </div>
                            <!-- Expandable content for Visit 2 -->
                            <div id="visit2Details" class="hidden mt-4 pt-4 border-t border-outline-variant/50 space-y-3">
                                <div>
                                    <p class="text-xs font-medium text-on-surface-variant">Diagnosis</p>
                                    <p class="text-sm text-on-surface">Tension Headache (G44.2)</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-on-surface-variant">Tindakan / Resep</p>
                                    <p class="text-sm text-on-surface">Ibuprofen 400mg (2x1), Istirahat dan hidrasi yang cukup.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleVisit2Details() {
    const details = document.getElementById('visit2Details');
    const icon = document.getElementById('visit2ToggleIcon');
    if (details.classList.contains('hidden')) {
        details.classList.remove('hidden');
        icon.classList.add('rotate-180');
    } else {
        details.classList.add('hidden');
        icon.classList.remove('rotate-180');
    }
}

function searchPasienRM() {
    const query = document.getElementById('rekamSearchInput').value.trim();
    if (!query) return;
    document.getElementById('searchResultName').textContent = query;
    document.getElementById('selectedName').textContent = query;
    const initials = query.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
    document.getElementById('selectedAvatar').textContent = initials || 'PS';
}

function selectPasienRM(name, nik, rm, genderAge, blood, allergy, lastVisit) {
    document.getElementById('selectedName').textContent = name;
    document.getElementById('searchResultName').textContent = name;
    document.getElementById('searchResultNik').textContent = 'NIK: ' + nik;
    document.getElementById('selectedGenderAge').textContent = genderAge;
    document.getElementById('selectedRM').textContent = rm;
    document.getElementById('selectedBlood').innerHTML = '<span class="material-symbols-outlined text-[16px]">bloodtype</span> ' + blood;
    document.getElementById('selectedAllergy').textContent = allergy;
    document.getElementById('selectedLastVisit').textContent = lastVisit;
    const initials = name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
    document.getElementById('selectedAvatar').textContent = initials;
}
</script>
@endsection
