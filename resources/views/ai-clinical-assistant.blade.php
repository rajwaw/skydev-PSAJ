@extends('layouts.app')

@section('header_title', 'AI Clinical Assistant')
@section('header_subtitle', 'Ringkasan & Chat AI untuk tenaga medis')

@section('content')
<div class="p-4 sm:p-6 flex flex-col lg:flex-row gap-4 lg:gap-6 h-[calc(100vh-180px)] min-h-[600px]">

    <!-- KIRI: DAFTAR PASIEN -->
    <div class="w-full lg:w-80 shrink-0 flex flex-col">
        <div class="bg-surface rounded-2xl card-shadow p-4 flex-1 min-h-0 flex flex-col">
            <!-- Tab Navigation -->
            <div class="flex gap-1 mb-3 border-b border-outline-variant pb-2">
                <button
                    type="button"
                    id="tab-ringkasan"
                    onclick="switchTab('ringkasan')"
                    class="tab-btn flex-1 py-2 px-3 text-sm font-semibold rounded-lg transition-colors bg-primary-container text-on-primary-container"
                >
                    <span class="material-symbols-outlined text-base align-middle me-1">summarize</span>
                    Ringkasan
                </button>
                <button
                    type="button"
                    id="tab-chat"
                    onclick="switchTab('chat')"
                    class="tab-btn flex-1 py-2 px-3 text-sm font-semibold rounded-lg transition-colors text-on-surface-variant hover:bg-surface-container-low"
                >
                    <span class="material-symbols-outlined text-base align-middle me-1">chat</span>
                    Chat
                </button>
            </div>

            <!-- Content for Ringkasan Tab -->
            <div id="panel-ringkasan" class="flex-1 min-h-0 overflow-hidden">
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

                <div id="daftar-pasien" class="space-y-1 max-h-[60vh] overflow-y-auto">
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

            <!-- Content for Chat Tab -->
            <div id="panel-chat" class="hidden flex-1 min-h-0 overflow-hidden flex flex-col">
                <div class="mb-3">
                    <label class="block text-xs font-medium text-on-surface-variant mb-1">Riwayat Percakapan</label>
                    <div class="relative mb-2">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg">search</span>
                        <input
                            type="text"
                            id="search-riwayat-chat"
                            placeholder="Cari nama pasien atau isi pesan..."
                            class="w-full pl-9 pr-3 py-2 text-sm rounded-lg border border-outline-variant input-ring"
                        >
                    </div>
                    <div id="chat-history" class="space-y-1 max-h-[55vh] overflow-y-auto bg-surface-container-lowest rounded-xl p-2">
                        <!-- Riwayat percakapan akan di-load via JS -->
                    </div>
                    <p id="chat-history-empty-result" class="hidden text-xs text-on-surface-variant text-center mt-2">
                        Tidak ada percakapan yang cocok
                    </p>
                </div>
                <button
                    type="button"
                    id="btn-baru-percakapan"
                    onclick="mulaiPercakapanBaruUI()"
                    class="w-full flex items-center justify-center gap-2 text-sm font-semibold text-primary hover:bg-primary-container/40 px-3 py-2 rounded-lg transition-colors"
                >
                    <span class="material-symbols-outlined text-base">add</span>
                    Percakapan Baru
                </button>
            </div>
        </div>
    </div>

    <!-- KANAN: KONTEN UTAMA (Ringkasan / Chat) -->
    <div class="flex-1 min-w-0 flex flex-col">

        <!-- PANEL RINGKASAN -->
        <div id="content-ringkasan">
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

                <div class="mt-6 pt-6 border-t border-outline-variant">
                    <h4 class="text-sm font-semibold text-on-surface mb-3">Grafik Riwayat</h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-surface-container-low rounded-xl p-4">
                            <p class="text-xs font-semibold text-on-surface-variant mb-2">Tren Tanda Vital</p>
                            <div id="chart-vital-empty" class="hidden text-xs text-on-surface-variant text-center py-8">
                                Belum ada data untuk ditampilkan
                            </div>
                            <canvas id="chart-tanda-vital" height="180"></canvas>
                        </div>

                        <div class="bg-surface-container-low rounded-xl p-4">
                            <p class="text-xs font-semibold text-on-surface-variant mb-2">Status Kondisi</p>
                            <div id="chart-evaluasi-empty" class="hidden text-xs text-on-surface-variant text-center py-8">
                                Belum ada data untuk ditampilkan
                            </div>
                            <canvas id="chart-status-evaluasi" height="180"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PANEL CHAT -->
        <div id="content-chat" class="hidden flex-1 min-h-0 flex flex-col">
            <div id="chat-empty-state" class="flex-1 bg-surface rounded-2xl card-shadow flex flex-col items-center justify-center p-6">
                <span class="material-symbols-outlined text-5xl text-outline mb-3">chat_bubble_outline</span>
                <p class="text-sm font-semibold text-on-surface mb-1">Belum ada percakapan dipilih</p>
                <p class="text-sm text-on-surface-variant text-center px-4">Klik "Percakapan Baru" di sidebar kiri untuk memulai</p>
            </div>

            <div id="chat-active-state" class="hidden flex-1 min-h-0 flex flex-col bg-surface rounded-2xl card-shadow overflow-hidden">
                <!-- Header Chat -->
                <div class="flex items-center gap-3 px-4 py-3 border-b border-outline-variant bg-surface-container-lowest">
                    <!-- Mode tampilan biasa (percakapan sudah ada isinya) -->
                    <div id="chat-header-display" class="flex items-center gap-3 flex-1 min-w-0">
                        <div id="chat-pasien-avatar" class="w-9 h-9 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center text-sm font-bold shrink-0"></div>
                        <div class="flex-1 min-w-0">
                            <h4 id="chat-pasien-nama" class="text-sm font-semibold text-on-surface truncate"></h4>
                            <p id="chat-pasien-konteks" class="text-xs text-on-surface-variant truncate"></p>
                        </div>
                    </div>

                    <!-- Mode pilih pasien (percakapan baru, belum ada pesan) -->
                    <div id="chat-header-select" class="hidden flex-1 min-w-0">
                        <label class="block text-xs font-medium text-on-surface-variant mb-1">Konteks Pasien (opsional)</label>
                        <select id="chat-context-pasien" class="w-full px-3 py-1.5 text-sm rounded-lg border border-outline-variant input-ring bg-white">
                            <option value="">Tanpa konteks pasien</option>
                            @foreach($daftarPasien as $p)
                                <option value="{{ $p->id_pasien }}" data-nama="{{ addslashes($p->nama_lengkap) }}" data-no-rm="{{ $p->no_rm }}">{{ $p->nama_lengkap }} • {{ $p->no_rm }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button
                        type="button"
                        onclick="bukaRiwayatPercakapan()"
                        class="text-on-surface-variant hover:text-on-surface p-1.5 rounded-lg hover:bg-surface-container transition-colors shrink-0"
                        title="Kembali ke daftar percakapan"
                    >
                        <span class="material-symbols-outlined text-xl">arrow_back</span>
                    </button>
                </div>

                <!-- Chat Messages Area -->
                <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4" style="scroll-behavior: smooth;">
                    <!-- Pesan akan di-render di sini -->
                </div>

                <!-- Quick Prompts (hanya tampil saat pesan kosong) -->
                <div id="chat-quick-prompts" class="px-4 pb-3 space-y-2">
                    <p class="text-xs text-on-surface-variant px-2">Saran pertanyaan:</p>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" class="quick-prompt-btn text-xs px-3 py-1.5 rounded-full border border-outline-variant bg-surface-container-low hover:bg-surface-container text-on-surface-variant hover:text-on-surface transition-colors" data-prompt="Pasien ini alergi apa?">Alergi</button>
                        <button type="button" class="quick-prompt-btn text-xs px-3 py-1.5 rounded-full border border-outline-variant bg-surface-container-low hover:bg-surface-container text-on-surface-variant hover:text-on-surface transition-colors" data-prompt="Ringkas riwayat kunjungan terakhir pasien ini">Riwayat Terakhir</button>
                        <button type="button" class="quick-prompt-btn text-xs px-3 py-1.5 rounded-full border border-outline-variant bg-surface-container-low hover:bg-surface-container text-on-surface-variant hover:text-on-surface transition-colors" data-prompt="Apa saran tindak lanjut untuk pasien ini berdasarkan data?">Tindak Lanjut</button>
                        <button type="button" class="quick-prompt-btn text-xs px-3 py-1.5 rounded-full border border-outline-variant bg-surface-container-low hover:bg-surface-container text-on-surface-variant hover:text-on-surface transition-colors" data-prompt="Apakah ada interaksi obat yang perlu diwaspadai?">Interaksi Obat</button>
                    </div>
                </div>

                <!-- Input Area -->
                <div class="border-t border-outline-variant bg-surface-container-lowest p-3">
                    <div class="flex items-end gap-2 mb-1">
                        <div class="flex-1 relative">
                            <textarea
                                id="chat-input"
                                rows="1"
                                placeholder="Ketik pertanyaan Anda..."
                                class="w-full px-4 py-2.5 text-sm bg-white border border-outline-variant rounded-2xl resize-none input-ring focus:ring-2 focus:ring-primary"
                                style="min-height: 44px; max-height: 150px;"
                            ></textarea>
                        </div>
                        <button
                            type="button"
                            id="btn-kirim-chat"
                            onclick="kirimPesanChat()"
                            class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center hover:bg-primary-container/70 transition-colors shrink-0 disabled:bg-surface-container-high disabled:text-on-surface-variant"
                            disabled
                        >
                            <span class="material-symbols-outlined text-lg">send</span>
                        </button>
                    </div>
                    <p class="text-xs text-on-surface-variant text-center">
                        Gunakan AI sebagai alat bantu informasi klinis. Selalu verifikasi informasi sebelum digunakan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let pasienAktifId = null;
let percakapanAktifId = null;
let isSending = false;

// ========== TAB SWITCHING ==========
function switchTab(tab) {
    const tabs = ['ringkasan', 'chat'];
    tabs.forEach(t => {
        const btn = document.getElementById('tab-' + t);
        const panel = document.getElementById('panel-' + t);
        const content = document.getElementById('content-' + t);
        if (t === tab) {
            btn?.classList.add('bg-primary-container', 'text-on-primary-container');
            btn?.classList.remove('text-on-surface-variant', 'hover:bg-surface-container-low');
            panel?.classList.remove('hidden');
            content?.classList.remove('hidden');
        } else {
            btn?.classList.remove('bg-primary-container', 'text-on-primary-container');
            btn?.classList.add('text-on-surface-variant', 'hover:bg-surface-container-low');
            panel?.classList.add('hidden');
            content?.classList.add('hidden');
        }
    });
}

// ========== RINGKASAN (Existing) ==========
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
    ambilDataGrafik(id);
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

function renderRingkasan(rawText) {
    const container = document.getElementById('isi-ringkasan');
    container.innerHTML = '';

    const escapeHtml = (str) => str
        .replace(/&/g, '&')
        .replace(/</g, '<')
        .replace(/>/g, '>');

    const paragraphs = rawText.split(/\n\s*\n/);

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

function formatInline(text) {
    return text.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
}

// ========== GRAFIK RIAWAYAT (Tanda Vital & Status) ==========
let chartVital = null;
let chartEvaluasi = null;

function ambilDataGrafik(id) {
    fetch(`/ai-clinical-assistant/pasien/${id}/grafik`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
        .then(res => res.json())
        .then(data => {
            renderChartVital(data.tanda_vital ?? []);
            renderChartEvaluasi(data.status_evaluasi ?? {});
        })
        .catch(() => {
            renderChartVital([]);
            renderChartEvaluasi({});
        });
}

function renderChartVital(dataVital) {
    const emptyEl = document.getElementById('chart-vital-empty');
    const canvas = document.getElementById('chart-tanda-vital');

    if (chartVital) { chartVital.destroy(); chartVital = null; }

    if (!dataVital.length) {
        emptyEl.classList.remove('hidden');
        canvas.classList.add('hidden');
        return;
    }
    emptyEl.classList.add('hidden');
    canvas.classList.remove('hidden');

    const labels = dataVital.map(d => d.tanggal);

    chartVital = new Chart(canvas, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                { label: 'Sistol (mmHg)', data: dataVital.map(d => d.tekanan_darah_sistol), borderColor: '#006c49', tension: 0.3 },
                { label: 'Nadi (x/menit)', data: dataVital.map(d => d.nadi), borderColor: '#0058be', tension: 0.3 },
                { label: 'Suhu (°C)', data: dataVital.map(d => d.suhu), borderColor: '#494bd6', tension: 0.3 },
                { label: 'SpO2 (%)', data: dataVital.map(d => d.spo2), borderColor: '#ba1a1a', tension: 0.3 },
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom', labels: { font: { size: 10 } } } },
            scales: { y: { beginAtZero: false } }
        }
    });
}

function renderChartEvaluasi(dataEvaluasi) {
    const emptyEl = document.getElementById('chart-evaluasi-empty');
    const canvas = document.getElementById('chart-status-evaluasi');

    if (chartEvaluasi) { chartEvaluasi.destroy(); chartEvaluasi = null; }

    const total = Object.values(dataEvaluasi).reduce((a, b) => a + b, 0);
    if (!total) {
        emptyEl.classList.remove('hidden');
        canvas.classList.add('hidden');
        return;
    }
    emptyEl.classList.add('hidden');
    canvas.classList.remove('hidden');

    chartEvaluasi = new Chart(canvas, {
        type: 'bar',
        data: {
            labels: Object.keys(dataEvaluasi),
            datasets: [{
                label: 'Jumlah Kunjungan',
                data: Object.values(dataEvaluasi),
                backgroundColor: ['#94a3b8', '#10b981', '#adc6ff', '#ffdad6'],
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
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

// ========== CHAT FEATURE ==========

// Load riwayat percakapan saat tab chat dibuka
document.getElementById('tab-chat').addEventListener('click', function() {
    if (document.getElementById('chat-history').children.length === 0) {
        loadDaftarPercakapan();
    }
});

document.getElementById('search-riwayat-chat').addEventListener('input', filterRiwayatChat);

function loadDaftarPercakapan() {
    fetch('/ai-clinical-assistant/percakapan', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                renderDaftarPercakapan(data.data);
            }
        })
        .catch(() => {
            console.error('Gagal memuat riwayat percakapan');
        });
}

function renderDaftarPercakapan(percakapanList) {
    const container = document.getElementById('chat-history');
    container.innerHTML = '';

    if (percakapanList.length === 0) {
        container.innerHTML = '<p class="text-xs text-on-surface-variant text-center py-4">Belum ada percakapan</p>';
        return;
    }

    percakapanList.forEach(p => {
        const item = document.createElement('button');
        item.type = 'button';
        item.onclick = () => bukaPercakapan(p.id_percakapan);
        item.className = 'percakapan-item w-full text-left px-3 py-2 rounded-lg hover:bg-surface-container transition-colors';
        const namaPasien = p.pasien ? `${p.pasien.nama_lengkap} ${p.pasien.no_rm}` : '';
        item.setAttribute('data-nama', `${p.judul || ''} ${namaPasien}`.toLowerCase());
        item.innerHTML = `
            <p class="text-sm font-medium text-on-surface truncate">${escapeHtml(p.judul || 'Percakapan baru')}</p>
            <p class="text-xs text-on-surface-variant truncate">${p.pasien ? escapeHtml(p.pasien.nama_lengkap + ' • ' + p.pasien.no_rm) : 'Chat umum (tanpa pasien)'}</p>
        `;
        container.appendChild(item);
    });

    filterRiwayatChat();
}

function filterRiwayatChat() {
    const keyword = document.getElementById('search-riwayat-chat').value.toLowerCase();
    const items = document.querySelectorAll('#chat-history .percakapan-item');
    let visible = 0;

    items.forEach(item => {
        const match = item.getAttribute('data-nama').includes(keyword);
        item.style.display = match ? '' : 'none';
        if (match) visible++;
    });

    const emptyNote = document.getElementById('chat-history-empty-result');
    emptyNote.classList.toggle('hidden', !(items.length > 0 && keyword && visible === 0));
}

function escapeHtml(str) {
    return str
        .replace(/&/g, '&')
        .replace(/</g, '<')
        .replace(/>/g, '>')
        .replace(/"/g, '"')
        .replace(/'/g, '&#039;');
}

function mulaiPercakapanBaruUI() {
    percakapanAktifId = null;

    document.getElementById('chat-empty-state').classList.add('hidden');
    document.getElementById('chat-active-state').classList.remove('hidden');
    document.getElementById('chat-active-state').classList.add('flex');

    document.getElementById('chat-header-display').classList.add('hidden');
    document.getElementById('chat-header-select').classList.remove('hidden');

    // Reset dropdown pasien ke "Tanpa konteks pasien" — mencegah state lama
    // dari percakapan sebelumnya ikut terbawa
    document.getElementById('chat-context-pasien').value = '';

    document.getElementById('chat-messages').innerHTML = '';
    document.getElementById('chat-quick-prompts').classList.remove('hidden');
    document.getElementById('chat-input').value = '';
    document.getElementById('chat-input').focus();
}

function buatPercakapanBaruDiBackend(idPasien) {
    return fetch('/ai-clinical-assistant/percakapan', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: JSON.stringify({ id_pasien: idPasien })
    }).then(res => res.json());
}

function bukaPercakapan(id) {
    percakapanAktifId = id;

    fetch(`/ai-clinical-assistant/percakapan/${id}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                renderPercakapan(data.data);
            }
        })
        .catch(() => {
            alert('Gagal memuat percakapan');
        });
}

function renderPercakapan(data) {
    // Update header
    const messagesContainer = document.getElementById('chat-messages');
    const emptyState = document.getElementById('chat-empty-state');
    const activeState = document.getElementById('chat-active-state');
    const quickPrompts = document.getElementById('chat-quick-prompts');

    document.getElementById('chat-header-select').classList.add('hidden');
    document.getElementById('chat-header-display').classList.remove('hidden');
    document.getElementById('chat-context-pasien').value = '';

    if (data.pasien) {
        const initials = data.pasien.nama_lengkap.split(' ').filter(Boolean).slice(0, 2).map(w => w[0]).join('').toUpperCase();
        document.getElementById('chat-pasien-avatar').textContent = initials || 'PS';
        document.getElementById('chat-pasien-nama').textContent = data.pasien.nama_lengkap;
        document.getElementById('chat-pasien-konteks').textContent = `Konteks: ${data.pasien.nama_lengkap} • ${data.pasien.no_rm}`;
    } else {
        document.getElementById('chat-pasien-avatar').textContent = 'AI';
        document.getElementById('chat-pasien-nama').textContent = 'Chat Umum';
        document.getElementById('chat-pasien-konteks').textContent = 'Tanpa konteks pasien';
    }

    // Render messages
    messagesContainer.innerHTML = '';
    if (data.pesan && data.pesan.length > 0) {
        data.pesan.forEach(pesan => {
            appendMessage(pesan.role, pesan.isi, false);
        });
        quickPrompts.classList.add('hidden');
    } else {
        quickPrompts.classList.remove('hidden');
    }

    emptyState.classList.add('hidden');
    activeState.classList.remove('hidden');
    activeState.classList.add('flex');

    // Scroll to bottom
    scrollToBottom();
}

function appendMessage(role, text, animate = true) {
    const container = document.getElementById('chat-messages');
    const div = document.createElement('div');
    div.className = `flex ${role === 'user' ? 'justify-end' : 'justify-start'}`;

    const bubbleClass = role === 'user'
        ? 'bg-primary-container text-on-primary-container rounded-br-md'
        : 'bg-surface-bright border border-outline-variant text-on-surface rounded-bl-md';

    const bubble = document.createElement('div');
    bubble.className = `${bubbleClass} max-w-[80%] rounded-2xl px-4 py-2.5`;

    const contentEl = document.createElement('div');
    contentEl.className = 'text-sm leading-relaxed space-y-2';

    if (role === 'user') {
        // Pesan user: tampilkan apa adanya, tidak perlu parsing markdown
        contentEl.innerHTML = `<p class="whitespace-pre-wrap">${escapeHtml(text)}</p>`;
    } else {
        // Balasan AI: render markdown dasar (bold, list, horizontal rule, italic)
        renderMarkdownInto(contentEl, text);
    }

    bubble.appendChild(contentEl);
    div.appendChild(bubble);
    container.appendChild(div);

    if (animate) {
        div.style.opacity = '0';
        div.style.transform = 'translateY(10px)';
        requestAnimationFrame(() => {
            div.style.transition = 'opacity 0.2s, transform 0.2s';
            div.style.opacity = '1';
            div.style.transform = 'translateY(0)';
        });
    }
    scrollToBottom();
}

function renderMarkdownInto(container, rawText) {
    container.innerHTML = '';

    const escapeHtml = (str) => str
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');

    const formatInline = (text) => {
        let out = escapeHtml(text);
        out = out.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
        out = out.replace(/(?<!\*)\*(?!\*)(.+?)(?<!\*)\*(?!\*)/g, '<em>$1</em>');
        return out;
    };

    const blocks = rawText.split(/\n\s*\n/);

    blocks.forEach(block => {
        const trimmed = block.trim();
        if (!trimmed) return;

        // Garis pemisah "---"
        if (/^-{3,}$/.test(trimmed)) {
            const hr = document.createElement('hr');
            hr.className = 'border-outline-variant my-2';
            container.appendChild(hr);
            return;
        }

        const lines = trimmed.split('\n');
        const isList = lines.every(l => /^[-*]\s+/.test(l.trim()));

        if (isList) {
            const ul = document.createElement('ul');
            ul.className = 'list-disc list-inside space-y-1';
            lines.forEach(line => {
                const li = document.createElement('li');
                li.innerHTML = formatInline(line.replace(/^[-*]\s+/, ''));
                ul.appendChild(li);
            });
            container.appendChild(ul);
        } else {
            const p = document.createElement('p');
            p.innerHTML = lines.map(formatInline).join('<br>');
            container.appendChild(p);
        }
    });
}

function scrollToBottom() {
    const container = document.getElementById('chat-messages');
    container.scrollTop = container.scrollHeight;
}

function kirimPesanChat() {
    const input = document.getElementById('chat-input');
    const pesan = input.value.trim();
    if (!pesan || isSending) return;

    isSending = true;
    const btn = document.getElementById('btn-kirim-chat');
    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-lg">progress_activity</span>';

    document.getElementById('chat-quick-prompts').classList.add('hidden');
    appendMessage('user', pesan);
    input.value = '';
    input.style.height = 'auto';

    const kirimKePercakapan = (idPercakapan) => {
        return fetch(`/ai-clinical-assistant/percakapan/${idPercakapan}/pesan`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({ pesan })
        }).then(res => res.json());
    };

    let alurPromise;

    if (percakapanAktifId) {
        alurPromise = kirimKePercakapan(percakapanAktifId);
    } else {
        const selectEl = document.getElementById('chat-context-pasien');
        const idPasien = selectEl.value || null;
        const selectedOption = selectEl.options[selectEl.selectedIndex];

        alurPromise = buatPercakapanBaruDiBackend(idPasien).then(dataBuat => {
            if (!dataBuat.success) throw new Error('Gagal membuat percakapan');
            percakapanAktifId = dataBuat.id_percakapan;

            document.getElementById('chat-header-select').classList.add('hidden');
            document.getElementById('chat-header-display').classList.remove('hidden');

            if (idPasien && selectedOption) {
                const nama = selectedOption.dataset.nama;
                const noRm = selectedOption.dataset.noRm;
                const initials = nama.split(' ').filter(Boolean).slice(0, 2).map(w => w[0]).join('').toUpperCase();
                document.getElementById('chat-pasien-avatar').textContent = initials || 'PS';
                document.getElementById('chat-pasien-nama').textContent = nama;
                document.getElementById('chat-pasien-konteks').textContent = `Konteks: ${nama} • ${noRm}`;
            } else {
                document.getElementById('chat-pasien-avatar').textContent = 'AI';
                document.getElementById('chat-pasien-nama').textContent = 'Chat Umum';
                document.getElementById('chat-pasien-konteks').textContent = 'Tanpa konteks pasien';
            }

            return kirimKePercakapan(percakapanAktifId);
        });
    }

    alurPromise
        .then(data => {
            isSending = false;
            btn.disabled = false;
            btn.innerHTML = '<span class="material-symbols-outlined text-lg">send</span>';

            if (data.success) {
                appendMessage('assistant', data.balasan);
                loadDaftarPercakapan();
            } else {
                appendMessage('assistant', 'Error: ' + (data.message || 'Gagal mendapatkan balasan'));
            }
        })
        .catch(() => {
            isSending = false;
            btn.disabled = false;
            btn.innerHTML = '<span class="material-symbols-outlined text-lg">send</span>';
            appendMessage('assistant', 'Terjadi kesalahan jaringan. Silakan coba lagi.');
        });
}

// Auto-resize textarea
document.getElementById('chat-input').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 150) + 'px';

    const btn = document.getElementById('btn-kirim-chat');
    btn.disabled = this.value.trim() === '' || isSending;
});

// Send on Enter (without Shift)
document.getElementById('chat-input').addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        kirimPesanChat();
    }
});

// Quick prompt buttons
document.querySelectorAll('.quick-prompt-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const prompt = this.dataset.prompt;
        document.getElementById('chat-input').value = prompt;
        document.getElementById('chat-input').dispatchEvent(new Event('input'));
        kirimPesanChat();
    });
});

function bukaRiwayatPercakapan() {
    // Switch back to showing history - just reload the list
    document.getElementById('chat-empty-state').classList.remove('hidden');
    document.getElementById('chat-active-state').classList.add('hidden');
    document.getElementById('chat-active-state').classList.remove('flex');
    percakapanAktifId = null;
    loadDaftarPercakapan();
}
</script>
@endsection