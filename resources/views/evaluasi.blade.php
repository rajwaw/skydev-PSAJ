@extends('layouts.app')

@section('title', 'Mandalacare - Evaluasi')
@section('header_title', 'Evaluasi')
@section('header_subtitle', 'Catat dan pantau hasil evaluasi pasien setelah tindakan.')

@section('content')
<div id="toastNotification" class="fixed top-6 left-1/2 -translate-x-1/2 z-50 w-[92%] max-w-lg pointer-events-none transition-all duration-300 transform -translate-y-16 opacity-0 hidden">
    <div id="toastCard" class="pointer-events-auto bg-white border-2 border-primary/40 rounded-2xl shadow-2xl p-4 md:p-5 flex items-start gap-4 backdrop-blur-md bg-white/95 relative overflow-hidden">
        <div id="toastAccentBar" class="absolute left-0 top-0 bottom-0 w-2 bg-primary"></div>
        <div id="toastIconContainer" class="w-11 h-11 rounded-xl bg-[#E5F5F0] text-primary flex items-center justify-center flex-shrink-0 shadow-sm">
            <span id="toastIcon" class="material-symbols-outlined text-2xl font-bold">check_circle</span>
        </div>
        <div class="flex-1 min-w-0 pr-2">
            <h4 class="text-base font-bold text-on-surface" id="toastTitle">Evaluasi Tersimpan!</h4>
            <p class="text-sm text-on-surface-variant mt-1" id="toastMessage">Data evaluasi berhasil dicatat.</p>
        </div>
        <button type="button" onclick="hideToast()" class="text-on-surface-variant hover:text-on-surface p-1 rounded-lg flex-shrink-0">
            <span class="material-symbols-outlined text-lg">close</span>
        </button>
    </div>
</div>

<div class="p-4 sm:p-6 md:p-8 lg:p-10 w-full max-w-7xl mx-auto flex-1 flex flex-col gap-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-on-surface tracking-tight">Evaluasi Pasien</h1>
            <p class="text-sm text-on-surface-variant mt-1">Catat hasil evaluasi pasien setelah tindakan dilakukan.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('asuhan-keperawatan') }}" class="bg-surface border border-outline-variant hover:border-primary text-on-surface px-4 py-2 rounded-xl text-sm font-semibold transition-colors inline-flex items-center gap-1.5 shadow-sm">
                <span class="material-symbols-outlined text-base text-primary">medical_services</span>
                Asuhan Keperawatan
            </a>
            <a id="btnLihatRM" href="{{ $selectedPasien ? route('rekam-medis', ['id' => $selectedPasien->id_pasien]) : route('rekam-medis') }}" class="bg-primary text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-[#005a3c] transition-colors inline-flex items-center gap-1.5 shadow-sm">
                <span class="material-symbols-outlined text-base">folder_open</span>
                Rekam Medis
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start">
        <div class="xl:col-span-2 flex flex-col gap-6">
            {{-- 1. PILIH PASIEN --}}
            <section class="bg-white rounded-xl border border-outline-variant p-5 sm:p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-on-surface flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-primary/10 text-primary flex items-center justify-center text-sm font-bold">1</span>
                        Pilih Pasien Terdaftar
                    </h3>
                    <span class="text-xs text-on-surface-variant bg-surface-container px-2.5 py-1 rounded-full font-medium">{{ $daftarPasien->count() }} Pasien</span>
                </div>

                <div class="relative mb-4">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                    <input id="pasienSearchInput" type="text" placeholder="Ketik nama pasien, NIK, atau nomor telepon..."
                        value="{{ $selectedPasien ? $selectedPasien->nama_lengkap : '' }}"
                        autocomplete="off" onfocus="showDropdown()" oninput="filterDropdown(this.value)"
                        class="w-full bg-surface border border-outline-variant rounded-xl py-3 pl-11 pr-10 text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all">
                    <button type="button" onclick="clearSearch()" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface p-1 rounded-lg">
                        <span class="material-symbols-outlined text-sm">close</span>
                    </button>
                    <div id="pasienDropdownList" class="absolute left-0 right-0 top-full mt-1.5 bg-white border border-outline-variant rounded-xl shadow-xl z-30 max-h-64 overflow-y-auto hidden">
                        @forelse ($daftarPasien as $p)
                            <div class="pasien-dropdown-item flex items-center justify-between p-3.5 hover:bg-surface-container-low cursor-pointer border-b border-outline-variant/40 transition-colors"
                                data-id="{{ $p->id_pasien }}" data-nama="{{ $p->nama_lengkap }}" data-nik="{{ $p->nik }}"
                                onclick="selectPasien({{ $p->id_pasien }})">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-[#E5F5F0] text-primary flex items-center justify-center text-xs font-bold shrink-0">{{ $p->initials }}</div>
                                    <div>
                                        <h4 class="text-sm font-semibold text-on-surface">{{ $p->nama_lengkap }}</h4>
                                        <p class="text-xs text-on-surface-variant">NIK: {{ $p->nik }} &bull; {{ $p->no_rm }}</p>
                                    </div>
                                </div>
                                <span class="material-symbols-outlined text-primary text-sm">arrow_forward</span>
                            </div>
                        @empty
                            <div class="p-4 text-center text-xs text-on-surface-variant">
                                Belum ada pasien. <a href="{{ route('pendaftaran') }}" class="text-primary underline font-semibold">Daftarkan pasien baru</a>.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 bg-surface-container-low rounded-xl border border-primary/20">
                    <div class="flex items-center gap-3.5">
                        <div id="cardAvatar" class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center text-primary font-bold text-base shrink-0">
                            {{ $selectedPasien ? $selectedPasien->initials : 'PS' }}
                        </div>
                        <div>
                            <h4 id="cardNama" class="font-bold text-on-surface text-base">{{ $selectedPasien ? $selectedPasien->nama_lengkap : 'Belum Memilih Pasien' }}</h4>
                            <p id="cardSub" class="text-xs text-on-surface-variant mt-0.5">
                                @if($selectedPasien)
                                    NIK: {{ $selectedPasien->nik }} &bull; {{ $selectedPasien->no_rm }}
                                    @if($selectedPasien->pendaftaranTerbaru)
                                     &bull; Status: {{ $selectedPasien->pendaftaranTerbaru->status_kunjungan }}
                                    @endif
                                @else
                                    Pilih pasien dari daftar untuk mengisi evaluasi
                                @endif
                            </p>
                        </div>
                    </div>
                    <button type="button" onclick="showDropdown()" class="bg-white border border-outline-variant hover:border-primary text-on-surface text-xs font-semibold px-3.5 py-2 rounded-lg transition-colors flex items-center gap-1.5 shadow-sm">
                        <span class="material-symbols-outlined text-[16px]">swap_horiz</span>
                        Ganti Pasien
                    </button>
                </div>
            </section>

            {{-- 2. RINGKASAN --}}
            <section class="bg-white rounded-xl border border-outline-variant p-5 sm:p-6 shadow-sm">
                <h3 class="text-lg font-bold text-on-surface mb-4 pb-3 border-b border-outline-variant/60 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-full bg-primary/10 text-primary flex items-center justify-center text-sm font-bold">2</span>
                    Ringkasan Kunjungan Terakhir
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-surface p-4 rounded-xl border border-outline-variant/60">
                        <p class="text-xs text-on-surface-variant mb-1 font-medium">Keluhan Utama</p>
                        <p id="summaryKeluhan" class="text-sm font-semibold text-on-surface">{{ $latestAsuhan ? $latestAsuhan->keluhan_utama : '-' }}</p>
                    </div>
                    <div class="bg-surface p-4 rounded-xl border border-outline-variant/60">
                        <p class="text-xs text-on-surface-variant mb-1 font-medium">Diagnosa</p>
                        <p id="summaryDiagnosa" class="text-sm font-semibold text-on-surface">
                            {{ ($latestIntervensi->isNotEmpty() && $latestIntervensi->first()->diagnosa_awal) ? $latestIntervensi->first()->diagnosa_awal : ($latestAsuhan ? $latestAsuhan->keluhan_utama : '-') }}
                        </p>
                    </div>
                    <div class="bg-surface p-4 rounded-xl border border-outline-variant/60">
                        <p class="text-xs text-on-surface-variant mb-1 font-medium">Tindakan Dilakukan</p>
                        <p id="summaryTindakan" class="text-sm font-semibold text-on-surface">{{ $latestImplementasi ? $latestImplementasi->tindakan_dilakukan : '-' }}</p>
                    </div>
                    <div class="bg-surface p-4 rounded-xl border border-outline-variant/60">
                        <p class="text-xs text-on-surface-variant mb-1 font-medium">Rencana Intervensi</p>
                        <p id="summaryIntervensi" class="text-sm font-semibold text-on-surface">{{ $latestIntervensi->isNotEmpty() ? $latestIntervensi->pluck('rencana_tindakan')->filter()->join('; ') : '-' }}</p>
                    </div>
                </div>
            </section>

            {{-- 3. FORM EVALUASI --}}
            <form id="formEvaluasi" action="{{ route('evaluasi.store') }}" method="POST" onsubmit="submitEvaluasi(event)">
                @csrf
                <input type="hidden" name="id_pasien" id="hidden_id_pasien" value="{{ $selectedPasien ? $selectedPasien->id_pasien : '' }}">
                <input type="hidden" name="id_rekam_medis" id="hidden_id_rekam_medis" value="{{ $latestRekamMedis ? $latestRekamMedis->id_rekam_medis : '' }}">

                <section class="bg-white rounded-xl border border-outline-variant p-5 sm:p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-on-surface mb-5 pb-3 border-b border-outline-variant/60 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-primary/10 text-primary flex items-center justify-center text-sm font-bold">3</span>
                        Form Evaluasi Pasien
                    </h3>

                    {{-- Tindakan / Implementasi Section --}}
                    <div class="bg-surface-container-low p-4 rounded-xl border border-outline-variant mb-6 space-y-4">
                        <h4 class="text-sm font-bold text-on-surface flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-primary text-base">task_alt</span>
                            Tindakan yang Dilakukan (Implementasi)
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-on-surface-variant mb-1">Tindakan Keperawatan <span class="text-red-500">*</span></label>
                                <textarea id="implTindakanInput" name="tindakan_dilakukan" rows="3" class="w-full bg-white border border-outline-variant rounded-xl p-3 text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all resize-none" placeholder="Masukkan detail tindakan yang telah diberikan..." required>{{ $latestImplementasi ? $latestImplementasi->tindakan_dilakukan : '' }}</textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-on-surface-variant mb-1">Resep / Pemberian Obat</label>
                                <textarea id="implResepInput" name="resep_obat" rows="3" class="w-full bg-white border border-outline-variant rounded-xl p-3 text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all resize-none" placeholder="Masukkan detail resep obat atau terapi obat jika ada...">{{ $latestImplementasi ? $latestImplementasi->resep_obat : '' }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-semibold text-on-surface mb-2">Kondisi Pasien</label>
                            <select id="evalKondisiSelect" name="status_kondisi" class="w-full bg-surface border border-outline-variant rounded-xl p-3 text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all">
                                <option value="Stabil" {{ ($latestEvaluasi && $latestEvaluasi->status_kondisi === 'Stabil') ? 'selected' : '' }}>Stabil</option>
                                <option value="Membaik" {{ ($latestEvaluasi && $latestEvaluasi->status_kondisi === 'Membaik') ? 'selected' : '' }}>Membaik</option>
                                <option value="Tidak Berubah" {{ ($latestEvaluasi && $latestEvaluasi->status_kondisi === 'Tidak Berubah') ? 'selected' : '' }}>Tidak Berubah</option>
                                <option value="Memburuk" {{ ($latestEvaluasi && $latestEvaluasi->status_kondisi === 'Memburuk') ? 'selected' : '' }}>Memburuk</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-on-surface mb-2">Status Evaluasi</label>
                            <div class="flex flex-wrap items-center gap-4 mt-3">
                                @foreach(['Belum Dievaluasi', 'Sedang Dievaluasi', 'Selesai'] as $statusEv)
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input class="text-primary focus:ring-primary" name="status_evaluasi" type="radio" value="{{ $statusEv }}"
                                            {{ ($latestEvaluasi && $latestEvaluasi->status_evaluasi === $statusEv) || (!$latestEvaluasi && $statusEv === 'Sedang Dievaluasi') ? 'checked' : '' }}>
                                        <span class="text-sm">{{ $statusEv }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-on-surface mb-2">Keluhan Setelah Tindakan</label>
                            <textarea id="evalKeluhanInput" name="keluhan_setelah_tindakan" rows="3" class="w-full bg-surface border border-outline-variant rounded-xl p-3 text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all resize-none" placeholder="Keluhan pasien setelah tindakan...">{{ $latestEvaluasi ? $latestEvaluasi->keluhan_setelah_tindakan : '' }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-on-surface mb-2">Respons Pasien</label>
                            <textarea id="evalResponsInput" name="respon_pasien" rows="3" class="w-full bg-surface border border-outline-variant rounded-xl p-3 text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all resize-none" placeholder="Respons pasien terhadap tindakan...">{{ $latestEvaluasi ? $latestEvaluasi->respon_pasien : '' }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-on-surface mb-2">Hasil Evaluasi</label>
                            <textarea id="evalHasilInput" name="hasil_evaluasi" rows="3" class="w-full bg-surface border border-outline-variant rounded-xl p-3 text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all resize-none" placeholder="Hasil evaluasi keseluruhan...">{{ $latestEvaluasi ? $latestEvaluasi->hasil_evaluasi : '' }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-on-surface mb-2">Rencana Selanjutnya</label>
                            <textarea id="evalRencanaInput" name="rencana_selanjutnya" rows="3" class="w-full bg-surface border border-outline-variant rounded-xl p-3 text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all resize-none" placeholder="Rencana tindak lanjut...">{{ $latestEvaluasi ? $latestEvaluasi->rencana_selanjutnya : '' }}</textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-4 pt-5 mt-5 border-t border-outline-variant">
                        <button type="reset" class="px-6 py-2.5 rounded-lg border border-outline-variant text-on-surface text-sm font-semibold hover:bg-surface-container-low transition-colors">Batal</button>
                        <button type="submit" id="btnSimpanEvaluasi" class="px-6 py-2.5 rounded-lg bg-primary text-on-primary text-sm font-semibold hover:bg-[#005a3c] transition-colors shadow-sm flex items-center gap-2">
                            <span class="material-symbols-outlined text-base">save</span>
                            Simpan Evaluasi
                        </button>
                    </div>
                </section>
            </form>
        </div>

        {{-- RIGHT COLUMN --}}
        <div class="xl:col-span-1 flex flex-col gap-6">
            <section class="bg-white rounded-xl border border-outline-variant p-5 shadow-sm">
                <h3 class="text-sm font-bold text-on-surface-variant uppercase tracking-wide mb-3">Info Pasien Terpilih</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-on-surface-variant">Nama</span>
                        <span id="infoNama" class="text-xs font-semibold text-on-surface text-right max-w-[60%]">{{ $selectedPasien ? $selectedPasien->nama_lengkap : '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-on-surface-variant">No. RM</span>
                        <span id="infoRM" class="text-xs font-semibold text-primary">{{ $selectedPasien ? $selectedPasien->no_rm : '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-on-surface-variant">Kunjungan</span>
                        <span id="infoKunjungan" class="text-xs font-semibold text-on-surface">
                            @if($selectedPasien && $selectedPasien->pendaftaranTerbaru)
                                {{ \Carbon\Carbon::parse($selectedPasien->pendaftaranTerbaru->tgl_daftar)->translatedFormat('d M Y') }}
                            @else
                                -
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-on-surface-variant">Status</span>
                        <span id="infoBadge" class="text-xs font-semibold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800">
                            {{ ($selectedPasien && $selectedPasien->pendaftaranTerbaru) ? $selectedPasien->pendaftaranTerbaru->status_kunjungan : '-' }}
                        </span>
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-xl border border-outline-variant shadow-sm overflow-hidden">
                <div class="p-5 border-b border-outline-variant">
                    <h3 class="text-lg font-bold text-on-surface">Riwayat Evaluasi</h3>
                    <p class="text-xs text-on-surface-variant mt-0.5">30 evaluasi terakhir semua pasien</p>
                </div>
                <div class="divide-y divide-outline-variant max-h-[520px] overflow-y-auto">
                    @forelse($riwayatEvaluasi as $rm)
                        @php
                            $ev = $rm->evaluasi;
                            $ps = $rm->pasien;
                            $kondisiBadge = match($ev->status_kondisi ?? '') {
                                'Stabil', 'Membaik' => 'bg-green-100 text-green-800',
                                'Memburuk' => 'bg-red-100 text-red-800',
                                default => 'bg-amber-100 text-amber-800',
                            };
                            $statusBadge = match($ev->status_evaluasi ?? '') {
                                'Selesai' => 'bg-blue-100 text-blue-800',
                                'Sedang Dievaluasi' => 'bg-amber-100 text-amber-800',
                                default => 'bg-surface-container text-on-surface-variant',
                            };
                        @endphp
                        <div class="p-4 hover:bg-surface-container-lowest transition-colors cursor-pointer" onclick="selectPasien({{ $ps->id_pasien }})">
                            <div class="flex items-start justify-between gap-2 mb-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-bold shrink-0">{{ $ps->initials }}</div>
                                    <div>
                                        <p class="text-sm font-semibold text-on-surface leading-tight">{{ $ps->nama_lengkap }}</p>
                                        <p class="text-xs text-on-surface-variant">{{ \Carbon\Carbon::parse($rm->tgl_pemeriksaan)->translatedFormat('d M Y') }}</p>
                                    </div>
                                </div>
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $kondisiBadge }} whitespace-nowrap">{{ $ev->status_kondisi }}</span>
                            </div>
                            <p class="text-xs text-on-surface-variant truncate mb-1.5">{{ $ev->hasil_evaluasi ?: '-' }}</p>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $statusBadge }}">{{ $ev->status_evaluasi }}</span>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <span class="material-symbols-outlined text-4xl text-on-surface-variant/40">assignment</span>
                            <p class="text-sm text-on-surface-variant mt-2">Belum ada riwayat evaluasi</p>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</div>

<script>
const ROUTE_EVAL_PASIEN = '{{ route("evaluasi.pasien.detail", ["id" => "__ID__"]) }}';
const ROUTE_RM_PASIEN   = '{{ route("rekam-medis", ["id" => "__ID__"]) }}';

function showDropdown() {
    document.getElementById('pasienDropdownList').classList.remove('hidden');
    setTimeout(() => document.addEventListener('click', closeDropdownOutside), 50);
}
function closeDropdownOutside(e) {
    const d = document.getElementById('pasienDropdownList');
    const i = document.getElementById('pasienSearchInput');
    if (!d.contains(e.target) && e.target !== i) {
        d.classList.add('hidden');
        document.removeEventListener('click', closeDropdownOutside);
    }
}
function filterDropdown(val) {
    const q = val.toLowerCase();
    showDropdown();
    document.querySelectorAll('.pasien-dropdown-item').forEach(item => {
        const ok = (item.dataset.nama || '').toLowerCase().includes(q) || (item.dataset.nik || '').toLowerCase().includes(q);
        item.style.display = ok ? '' : 'none';
    });
}
function clearSearch() { document.getElementById('pasienSearchInput').value = ''; filterDropdown(''); }

function selectPasien(id) {
    document.getElementById('pasienDropdownList').classList.add('hidden');
    fetch(ROUTE_EVAL_PASIEN.replace('__ID__', id), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(data => {
            if (!data.success) { alert(data.message); return; }
            const p = data.pasien, r = data.ringkasan, ev = data.evaluasi, im = data.implementasi;

            document.getElementById('cardAvatar').textContent  = p.initials;
            document.getElementById('cardNama').textContent    = p.nama_lengkap;
            document.getElementById('cardSub').textContent     = `NIK: ${p.nik} • ${p.no_rm} • Status: ${p.status_kunjungan}`;
            document.getElementById('pasienSearchInput').value = p.nama_lengkap;
            document.getElementById('infoNama').textContent    = p.nama_lengkap;
            document.getElementById('infoRM').textContent      = p.no_rm;
            document.getElementById('infoKunjungan').textContent = p.tgl_kunjungan;
            document.getElementById('infoBadge').textContent   = p.status_kunjungan;
            document.getElementById('btnLihatRM').href         = ROUTE_RM_PASIEN.replace('__ID__', id);

            document.getElementById('summaryKeluhan').textContent   = r.keluhan_utama || '-';
            document.getElementById('summaryDiagnosa').textContent  = r.diagnosa || '-';
            document.getElementById('summaryTindakan').textContent  = r.tindakan || '-';
            document.getElementById('summaryIntervensi').textContent = r.intervensi || '-';

            document.getElementById('hidden_id_pasien').value      = p.id_pasien;
            document.getElementById('hidden_id_rekam_medis').value = p.id_rekam_medis || '';

            // Prefill Implementasi jika ada
            if (im) {
                document.getElementById('implTindakanInput').value = im.tindakan_dilakukan || '';
                document.getElementById('implResepInput').value    = im.resep_obat || '';
            } else {
                document.getElementById('implTindakanInput').value = '';
                document.getElementById('implResepInput').value    = '';
            }

            if (ev) {
                document.getElementById('evalKondisiSelect').value = ev.status_kondisi;
                const radio = document.querySelector(`input[name="status_evaluasi"][value="${ev.status_evaluasi}"]`);
                if (radio) radio.checked = true;
                document.getElementById('evalKeluhanInput').value = ev.keluhan_setelah_tindakan || '';
                document.getElementById('evalResponsInput').value = ev.respon_pasien || '';
                document.getElementById('evalHasilInput').value   = ev.hasil_evaluasi || '';
                document.getElementById('evalRencanaInput').value = ev.rencana_selanjutnya || '';
            } else {
                // Jangan reset form as-is karena akan membersihkan hidden fields, tapi reset manual fields evaluasi saja
                document.getElementById('evalKondisiSelect').value = 'Stabil';
                const defaultRadio = document.querySelector('input[name="status_evaluasi"][value="Sedang Dievaluasi"]');
                if (defaultRadio) defaultRadio.checked = true;
                document.getElementById('evalKeluhanInput').value = '';
                document.getElementById('evalResponsInput').value = '';
                document.getElementById('evalHasilInput').value   = '';
                document.getElementById('evalRencanaInput').value = '';
            }
        })
        .catch(err => console.error(err));
}

function submitEvaluasi(e) {
    e.preventDefault();
    const idPasien = document.getElementById('hidden_id_pasien').value;
    const idRM     = document.getElementById('hidden_id_rekam_medis').value;
    if (!idPasien) { showToast('Pilih Pasien Dulu', 'Pilih pasien dari daftar sebelum menyimpan.', 'error'); return; }
    if (!idRM)     { showToast('Rekam Medis Tidak Ada', 'Lakukan asuhan keperawatan terlebih dahulu untuk pasien ini.', 'warning'); return; }

    const btn = document.getElementById('btnSimpanEvaluasi');
    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined text-base" style="animation:spin 1s linear infinite">autorenew</span> Menyimpan...';

    fetch('{{ route("evaluasi.store") }}', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value },
        body: new FormData(document.getElementById('formEvaluasi')),
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-outlined text-base">save</span> Simpan Evaluasi';
        showToast(data.success ? 'Evaluasi Tersimpan!' : 'Gagal Menyimpan', data.message, data.success ? 'success' : 'error');
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-outlined text-base">save</span> Simpan Evaluasi';
        showToast('Terjadi Kesalahan', 'Periksa koneksi dan coba lagi.', 'error');
    });
}

let toastTimer;
function showToast(title, msg, type) {
    const toast = document.getElementById('toastNotification');
    const icon  = document.getElementById('toastIcon');
    const bar   = document.getElementById('toastAccentBar');
    const ic    = document.getElementById('toastIconContainer');
    document.getElementById('toastTitle').textContent   = title;
    document.getElementById('toastMessage').textContent = msg;
    if (type === 'success') { icon.textContent='check_circle'; bar.style.background='#006c47'; ic.style.color='#006c47'; ic.style.background='#E5F5F0'; }
    else if (type === 'error') { icon.textContent='error'; bar.style.background='#ef4444'; ic.style.color='#ef4444'; ic.style.background='#fef2f2'; }
    else { icon.textContent='warning'; bar.style.background='#f59e0b'; ic.style.color='#f59e0b'; ic.style.background='#fffbeb'; }
    toast.classList.remove('hidden','-translate-y-16','opacity-0');
    toast.classList.add('translate-y-0','opacity-100');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(hideToast, 5000);
}
function hideToast() {
    const toast = document.getElementById('toastNotification');
    toast.classList.remove('translate-y-0','opacity-100');
    toast.classList.add('-translate-y-16','opacity-0');
    setTimeout(() => toast.classList.add('hidden'), 350);
}
@if(session('success'))
document.addEventListener('DOMContentLoaded', () => showToast('Berhasil!', '{{ session("success") }}', 'success'));
@endif
@if(session('error'))
document.addEventListener('DOMContentLoaded', () => showToast('Gagal!', '{{ session("error") }}', 'error'));
@endif
</script>
@endsection