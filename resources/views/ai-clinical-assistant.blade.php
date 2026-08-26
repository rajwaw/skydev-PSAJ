@extends('layouts.app')

@section('header_title', 'AI Clinical Assistant')
@section('header_subtitle', 'Ringkasan riwayat medis pasien otomatis')

@section('content')
<div class="p-4 sm:p-6 flex flex-col lg:flex-row gap-4 lg:gap-6">

    <!-- KIRI: DAFTAR PASIEN -->
    <div class="w-full lg:w-80 shrink-0">
        <div class="bg-surface rounded-2xl card-shadow p-4">
            <h3 class="text-sm font-semibold text-on-surface mb-3">Pilih Pasien</h3>

            <div class="relative mb-3">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg">search</span>
                <input
                    type="text"
                    id="search-pasien"
                    placeholder="Cari nama pasien..."
                    class="w-full pl-9 pr-3 py-2 text-sm rounded-lg border border-outline-variant input-ring"
                >
            </div>

            <div id="daftar-pasien" class="space-y-1 max-h-[65vh] overflow-y-auto">
                @forelse($daftarPasien as $pasien)
                    <button
                        type="button"
                        onclick="pilihPasien({{ $pasien->id_pasien }}, '{{ addslashes($pasien->nama_lengkap) }}', this)"
                        data-nama="{{ strtolower($pasien->nama_lengkap) }}"
                        data-id="{{ $pasien->id_pasien }}"
                        class="pasien-item w-full flex items-center gap-3 text-left px-3 py-2.5 rounded-lg hover:bg-surface-container-low transition-colors"
                    >
                        <div class="w-9 h-9 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center text-xs font-bold shrink-0">
                            {{ $pasien->initials }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-on-surface truncate">{{ $pasien->nama_lengkap }}</p>
                            <p class="text-xs text-on-surface-variant">{{ $pasien->no_rm }}</p>
                        </div>
                    </button>
                @empty
                    <p class="text-sm text-on-surface-variant text-center py-4">Belum ada data pasien</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- KANAN: HASIL RINGKASAN -->
    <div class="flex-1 min-w-0">
        <div id="empty-state" class="bg-surface rounded-2xl card-shadow p-10 text-center">
            <span class="material-symbols-outlined text-5xl text-outline mb-3 block">smart_toy</span>
            <p class="text-sm font-semibold text-on-surface mb-1">Belum ada pasien dipilih</p>
            <p class="text-sm text-on-surface-variant">Pilih pasien di sebelah kiri untuk melihat ringkasan riwayat medisnya</p>
        </div>

        <div id="loading-state" class="hidden bg-surface rounded-2xl card-shadow p-10 text-center">
            <div class="inline-flex items-center gap-2 text-on-surface-variant">
                <span class="material-symbols-outlined animate-spin text-xl">progress_activity</span>
                <span class="text-sm font-medium">Sedang membuat ringkasan...</span>
            </div>
        </div>

        <div id="result-state" class="hidden bg-surface rounded-2xl card-shadow p-5 sm:p-6">
            <div class="flex items-start justify-between gap-3 mb-4 pb-4 border-b border-outline-variant">
                <div class="flex items-center gap-3">
                    <div id="avatar-pasien-aktif" class="w-11 h-11 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center text-sm font-bold shrink-0"></div>
                    <div>
                        <h3 id="nama-pasien-aktif" class="text-base font-bold text-on-surface leading-tight"></h3>
                        <p class="text-xs text-on-surface-variant">Ringkasan dibuat oleh AI</p>
                    </div>
                </div>
                <button
                    type="button"
                    id="btn-generate-ulang"
                    class="flex items-center gap-1 text-xs font-semibold text-primary hover:bg-primary-container/40 px-2.5 py-1.5 rounded-lg transition-colors shrink-0"
                >
                    <span class="material-symbols-outlined text-base">refresh</span>
                    Generate Ulang
                </button>
            </div>

            <div class="flex items-start gap-2 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2.5 mb-4">
                <span class="material-symbols-outlined text-amber-600 text-lg shrink-0">warning</span>
                <p class="text-xs text-amber-800 leading-relaxed">
                    Ringkasan ini dibuat otomatis oleh AI dan wajib diverifikasi oleh tenaga medis sebelum digunakan untuk pengambilan keputusan klinis.
                </p>
            </div>

            <div id="isi-ringkasan" class="text-sm text-on-surface leading-relaxed space-y-3"></div>
        </div>
    </div>

</div>

<script>
let pasienAktifId = null;

function pilihPasien(id, nama, el) {
    pasienAktifId = id;

    document.querySelectorAll('.pasien-item').forEach(item => {
        item.classList.remove('bg-primary-container', 'text-on-primary-container');
    });
    if (el) el.classList.add('bg-primary-container', 'text-on-primary-container');

    const initials = nama.split(' ').filter(Boolean).slice(0, 2).map(w => w[0]).join('').toUpperCase();
    document.getElementById('avatar-pasien-aktif').textContent = initials || 'PS';
    document.getElementById('nama-pasien-aktif').textContent = nama;

    ambilRingkasan(id);
}

function ambilRingkasan(id) {
    document.getElementById('empty-state').classList.add('hidden');
    document.getElementById('result-state').classList.add('hidden');
    document.getElementById('loading-state').classList.remove('hidden');

    fetch(`/ai-clinical-assistant/pasien/${id}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
        .then(res => res.json())
        .then(data => {
            document.getElementById('loading-state').classList.add('hidden');
            document.getElementById('result-state').classList.remove('hidden');
            renderRingkasan(data.summary ?? 'Gagal memuat ringkasan.');
        })
        .catch(() => {
            document.getElementById('loading-state').classList.add('hidden');
            document.getElementById('result-state').classList.remove('hidden');
            renderRingkasan('Terjadi kesalahan saat memuat ringkasan.');
        });
}

// Konversi markdown sederhana (bold, heading, list) dari AI jadi HTML yang rapi & aman
function renderRingkasan(rawText) {
    const container = document.getElementById('isi-ringkasan');
    container.innerHTML = '';

    // escape HTML dulu biar aman dari injeksi
    const escapeHtml = (str) => str
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');

    const paragraphs = rawText.split(/\n\s*\n/); // pisah per paragraf (baris kosong)

    paragraphs.forEach(para => {
        const trimmed = para.trim();
        if (!trimmed) return;

        const lines = trimmed.split('\n');
        const isList = lines.every(l => /^[-*]\s+/.test(l.trim()));

        if (isList) {
            const ul = document.createElement('ul');
            ul.className = 'list-disc list-inside space-y-1';
            lines.forEach(line => {
                const li = document.createElement('li');
                li.innerHTML = formatInline(escapeHtml(line.replace(/^[-*]\s+/, '')));
                ul.appendChild(li);
            });
            container.appendChild(ul);
        } else {
            const p = document.createElement('p');
            p.innerHTML = lines.map(l => formatInline(escapeHtml(l))).join('<br>');
            container.appendChild(p);
        }
    });
}

// **bold** -> <strong>, jadi teks tebal beneran
function formatInline(text) {
    return text.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
}

document.getElementById('btn-generate-ulang').addEventListener('click', function () {
    if (pasienAktifId) ambilRingkasan(pasienAktifId);
});

document.getElementById('search-pasien').addEventListener('input', function (e) {
    const keyword = e.target.value.toLowerCase();
    document.querySelectorAll('.pasien-item').forEach(item => {
        const nama = item.getAttribute('data-nama');
        item.style.display = nama.includes(keyword) ? '' : 'none';
    });
});
</script>
@endsection