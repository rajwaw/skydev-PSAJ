@extends('layouts.app')

@section('title', 'Mandalacare - Evaluasi')
@section('header_title', 'Evaluasi')
@section('header_subtitle', 'Catat dan pantau hasil evaluasi pasien setelah tindakan.')

@section('content')
<div class="p-6 md:p-8 lg:p-10 w-full max-w-[1440px] mx-auto space-y-6">
    <!-- Section 1: Pilih Pasien -->
    <section class="bg-surface rounded-xl border border-outline-variant p-6 shadow-[0px_4px_20px_rgba(0,0,0,0.04)]">
        <h3 class="font-headline-md text-headline-md text-on-surface mb-stack-md">Pasien</h3>
        <div class="mb-stack-md relative max-w-2xl">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
            <input id="evalSearchInput" onkeyup="searchPasienEval()" class="w-full pl-10 pr-4 py-3 rounded-xl border border-outline-variant bg-surface font-body-md text-body-md focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all" placeholder="Cari nama pasien atau NIK..." type="text">
        </div>
        <!-- Selected Patient Card -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 bg-surface-container-low rounded-lg border border-outline-variant">
            <div class="flex items-center gap-4">
                <div id="evalAvatar" class="w-12 h-12 rounded-full bg-secondary-container text-on-secondary-container flex items-center justify-center font-bold text-lg flex-shrink-0">
                    AP
                </div>
                <div>
                    <h4 id="evalPatientName" class="font-label-md text-label-md font-bold text-on-surface">Andi Pratama</h4>
                    <p id="evalPatientInfo" class="font-body-sm text-body-sm text-on-surface-variant">NIK: 3271••••890 <span class="mx-2 text-outline-variant">•</span> Kunjungan: 7 Agustus 2026</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <span id="evalPatientBadge" class="px-3 py-1 bg-amber-100 text-amber-800 rounded-full font-label-sm text-label-sm font-semibold">Sedang Ditangani</span>
                <a href="{{ route('rekam-medis') }}" class="px-4 py-2 rounded-lg border border-secondary text-secondary font-label-md text-label-md hover:bg-secondary-fixed-dim transition-colors text-center inline-block">Lihat Rekam Medis</a>
            </div>
        </div>
    </section>

    <!-- Section 2: Ringkasan & Hasil -->
    <section class="grid grid-cols-1 lg:grid-cols-2 gap-gutter">
        <!-- Card 1 -->
        <div class="bg-surface rounded-xl border border-outline-variant p-6 shadow-[0px_4px_20px_rgba(0,0,0,0.04)] flex flex-col h-full">
            <h3 class="font-label-md text-label-md font-bold text-on-surface mb-stack-md pb-2 border-b border-outline-variant">Ringkasan Kunjungan</h3>
            <div class="space-y-4 flex-1">
                <div>
                    <p class="font-label-sm text-label-sm text-on-surface-variant">Keluhan Utama</p>
                    <p id="summaryKeluhan" class="font-body-md text-body-md text-on-surface">Demam dan batuk</p>
                </div>
                <div>
                    <p class="font-label-sm text-label-sm text-on-surface-variant">Diagnosis</p>
                    <p id="summaryDiagnosis" class="font-body-md text-body-md text-on-surface">Diagnosis awal</p>
                </div>
                <div>
                    <p class="font-label-sm text-label-sm text-on-surface-variant">Tindakan</p>
                    <p id="summaryTindakan" class="font-body-md text-body-md text-on-surface">Pemeriksaan umum</p>
                </div>
                <div>
                    <p class="font-label-sm text-label-sm text-on-surface-variant">Intervensi</p>
                    <p id="summaryIntervensi" class="font-body-md text-body-md text-on-surface">Observasi kondisi pasien dan pemberian obat sesuai resep.</p>
                </div>
            </div>
        </div>
        <!-- Card 2 -->
        <div class="bg-surface rounded-xl border border-outline-variant p-6 shadow-[0px_4px_20px_rgba(0,0,0,0.04)] flex flex-col h-full">
            <h3 class="font-label-md text-label-md font-bold text-on-surface mb-stack-md pb-2 border-b border-outline-variant">Hasil Tindakan</h3>
            <div class="space-y-4 flex-1">
                <div>
                    <p class="font-label-sm text-label-sm text-on-surface-variant">Kondisi Pasien</p>
                    <p id="resultKondisi" class="font-body-md text-body-md text-on-surface">Pasien dalam kondisi stabil.</p>
                </div>
                <div>
                    <p class="font-label-sm text-label-sm text-on-surface-variant">Keluhan Setelah Tindakan</p>
                    <p id="resultKeluhanSetelah" class="font-body-md text-body-md text-on-surface">Demam berkurang.</p>
                </div>
                <div>
                    <p class="font-label-sm text-label-sm text-on-surface-variant">Respons Pasien</p>
                    <p id="resultRespons" class="font-body-md text-body-md text-on-surface">Pasien merespons tindakan dengan baik.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 3: Form Evaluasi -->
    <section class="bg-surface rounded-xl border border-outline-variant p-6 shadow-[0px_4px_20px_rgba(0,0,0,0.04)]">
        <div class="mb-stack-lg">
            <h3 class="font-headline-md text-headline-md text-on-surface">Evaluasi Pasien</h3>
            <p class="font-body-sm text-body-sm text-on-surface-variant">Catat kondisi pasien setelah tindakan dilakukan.</p>
        </div>
        <form id="formEvaluasi" class="space-y-6" onsubmit="handleEvaluasiSubmit(event)">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kondisi -->
                <div class="col-span-1">
                    <label class="block font-label-md text-label-md text-on-surface mb-2">Kondisi Pasien</label>
                    <select id="evalKondisiSelect" class="w-full p-3 rounded-xl border border-outline-variant bg-surface font-body-md text-body-md focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all">
                        <option value="Stabil">Stabil</option>
                        <option value="Membaik">Membaik</option>
                        <option value="Tidak Berubah">Tidak Berubah</option>
                        <option value="Memburuk">Memburuk</option>
                    </select>
                </div>
                <!-- Status -->
                <div class="col-span-1">
                    <label class="block font-label-md text-label-md text-on-surface mb-2">Status Evaluasi</label>
                    <div class="flex flex-wrap items-center gap-4 mt-3">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input class="text-primary focus:ring-primary" name="status_eval" type="radio" value="Belum Dievaluasi">
                            <span class="font-body-sm text-body-sm">Belum Dievaluasi</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input checked="" class="text-primary focus:ring-primary" name="status_eval" type="radio" value="Sedang Dievaluasi">
                            <span class="font-body-sm text-body-sm">Sedang Dievaluasi</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input class="text-primary focus:ring-primary" name="status_eval" type="radio" value="Selesai">
                            <span class="font-body-sm text-body-sm">Selesai</span>
                        </label>
                    </div>
                </div>
            </div>
            <!-- Textareas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-2">Keluhan Setelah Tindakan</label>
                    <textarea id="evalKeluhanInput" class="w-full p-3 rounded-xl border border-outline-variant bg-surface font-body-md text-body-md focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all resize-none" placeholder="Masukkan keluhan pasien setelah tindakan..." rows="3"></textarea>
                </div>
                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-2">Respons Pasien</label>
                    <textarea id="evalResponsInput" class="w-full p-3 rounded-xl border border-outline-variant bg-surface font-body-md text-body-md focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all resize-none" placeholder="Masukkan respons pasien terhadap tindakan..." rows="3"></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block font-label-md text-label-md text-on-surface mb-2">Hasil Evaluasi</label>
                    <textarea id="evalHasilInput" class="w-full p-3 rounded-xl border border-outline-variant bg-surface font-body-md text-body-md focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all resize-none" placeholder="Masukkan hasil evaluasi..." rows="3"></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block font-label-md text-label-md text-on-surface mb-2">Rencana Selanjutnya</label>
                    <textarea id="evalRencanaInput" class="w-full p-3 rounded-xl border border-outline-variant bg-surface font-body-md text-body-md focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all resize-none" placeholder="Masukkan rencana tindak lanjut..." rows="3"></textarea>
                </div>
            </div>
            <!-- Actions -->
            <div class="flex justify-end gap-4 pt-4 border-t border-outline-variant">
                <button type="reset" class="px-6 py-2.5 rounded-lg border border-outline-variant text-on-surface font-label-md text-label-md hover:bg-surface-container-low transition-colors">Batal</button>
                <button class="px-6 py-2.5 rounded-lg bg-primary text-on-primary font-label-md text-label-md hover:bg-primary/90 transition-colors shadow-sm" type="submit">Simpan Evaluasi</button>
            </div>
        </form>
    </section>

    <!-- Section 4: Riwayat Evaluasi -->
    <section class="bg-surface rounded-xl border border-outline-variant p-6 shadow-[0px_4px_20px_rgba(0,0,0,0.04)]">
        <h3 class="font-headline-md text-headline-md text-on-surface mb-stack-md">Riwayat Evaluasi</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="evalHistoryTable">
                <thead>
                    <tr class="bg-slate-50 border-y border-outline-variant">
                        <th class="p-4 font-label-md text-label-md text-on-surface-variant">Tanggal</th>
                        <th class="p-4 font-label-md text-label-md text-on-surface-variant">Nama Pasien</th>
                        <th class="p-4 font-label-md text-label-md text-on-surface-variant">Kondisi</th>
                        <th class="p-4 font-label-md text-label-md text-on-surface-variant">Hasil</th>
                        <th class="p-4 font-label-md text-label-md text-on-surface-variant">Status</th>
                        <th class="p-4 font-label-md text-label-md text-on-surface-variant">Aksi</th>
                    </tr>
                </thead>
                <tbody class="font-body-sm text-body-sm" id="evalTableBody">
                    <tr class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors">
                        <td class="p-4 text-on-surface whitespace-nowrap">7 Agustus 2026</td>
                        <td class="p-4 text-on-surface font-medium">Andi Pratama</td>
                        <td class="p-4">
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full font-label-sm text-label-sm">Stabil</span>
                        </td>
                        <td class="p-4 text-on-surface-variant max-w-xs truncate" title="Pasien merespons tindakan...">Pasien merespons tindakan...</td>
                        <td class="p-4">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full font-label-sm text-label-sm">Selesai</span>
                        </td>
                        <td class="p-4">
                            <button type="button" onclick="viewHistoryDetail('Andi Pratama', '7 Agustus 2026', 'Stabil', 'Pasien merespons tindakan dengan baik.', 'Selesai')" class="text-secondary hover:text-secondary-container font-label-sm text-label-sm underline">Lihat</button>
                        </td>
                    </tr>
                    <!-- Example row 2 -->
                    <tr class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors">
                        <td class="p-4 text-on-surface whitespace-nowrap">6 Agustus 2026</td>
                        <td class="p-4 text-on-surface font-medium">Budi Santoso</td>
                        <td class="p-4">
                            <span class="px-2 py-1 bg-amber-100 text-amber-800 rounded-full font-label-sm text-label-sm">Belum Stabil</span>
                        </td>
                        <td class="p-4 text-on-surface-variant max-w-xs truncate" title="Perlu observasi lanjutan...">Perlu observasi lanjutan...</td>
                        <td class="p-4">
                            <span class="px-2 py-1 bg-amber-100 text-amber-800 rounded-full font-label-sm text-label-sm">Sedang Dievaluasi</span>
                        </td>
                        <td class="p-4">
                            <button type="button" onclick="viewHistoryDetail('Budi Santoso', '6 Agustus 2026', 'Belum Stabil', 'Perlu observasi lanjutan...', 'Sedang Dievaluasi')" class="text-secondary hover:text-secondary-container font-label-sm text-label-sm underline">Lihat</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</div>

<!-- Modal Notifikasi / Detail -->
<div id="evalModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full shadow-2xl border border-outline-variant">
        <div class="flex items-center gap-3 mb-4">
            <div id="modalIconContainer" class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center">
                <span id="modalIcon" class="material-symbols-outlined">check_circle</span>
            </div>
            <h4 id="modalTitle" class="text-lg font-bold text-on-surface">Evaluasi Disimpan</h4>
        </div>
        <p id="modalMessage" class="text-sm text-on-surface-variant mb-6">Data evaluasi pasien berhasil dicatat dan diperbarui dalam sistem.</p>
        <div class="flex justify-end">
            <button type="button" onclick="closeEvalModal()" class="px-5 py-2.5 bg-primary text-white rounded-lg font-semibold text-sm hover:bg-[#005a3c] transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
function searchPasienEval() {
    const q = document.getElementById('evalSearchInput').value.trim();
    if (!q) return;
    document.getElementById('evalPatientName').textContent = q;
    const initials = q.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
    document.getElementById('evalAvatar').textContent = initials || 'PS';
}

function handleEvaluasiSubmit(e) {
    e.preventDefault();
    const kondisi = document.getElementById('evalKondisiSelect').value;
    const statusRadio = document.querySelector('input[name="status_eval"]:checked');
    const status = statusRadio ? statusRadio.value : 'Sedang Dievaluasi';
    const patientName = document.getElementById('evalPatientName').textContent;
    const hasil = document.getElementById('evalHasilInput').value || 'Kondisi ' + kondisi.toLowerCase() + ', observasi berjalan baik.';
    
    // Add row to table
    const tbody = document.getElementById('evalTableBody');
    const newRow = document.createElement('tr');
    newRow.className = "border-b border-outline-variant hover:bg-surface-container-lowest transition-colors";
    
    const today = new Date();
    const dateStr = today.toLocaleDateString('id-ID', { day: 'numeric', month: 'Long', year: 'numeric' });
    
    const badgeColor = kondisi === 'Stabil' || kondisi === 'Membaik' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800';
    const statusBadge = status === 'Selesai' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800';
    
    newRow.innerHTML = `
        <td class="p-4 text-on-surface whitespace-nowrap">${dateStr}</td>
        <td class="p-4 text-on-surface font-medium">${patientName}</td>
        <td class="p-4">
            <span class="px-2 py-1 ${badgeColor} rounded-full font-label-sm text-label-sm">${kondisi}</span>
        </td>
        <td class="p-4 text-on-surface-variant max-w-xs truncate" title="${hasil}">${hasil}</td>
        <td class="p-4">
            <span class="px-2 py-1 ${statusBadge} rounded-full font-label-sm text-label-sm">${status}</span>
        </td>
        <td class="p-4">
            <button type="button" onclick="viewHistoryDetail('${patientName}', '${dateStr}', '${kondisi}', '${hasil.replace(/'/g, "\\'")}', '${status}')" class="text-secondary hover:text-secondary-container font-label-sm text-label-sm underline">Lihat</button>
        </td>
    `;
    
    tbody.insertBefore(newRow, tbody.firstChild);
    
    // Update summary
    document.getElementById('resultKondisi').textContent = 'Pasien dalam kondisi ' + kondisi.toLowerCase() + '.';
    if (document.getElementById('evalKeluhanInput').value) {
        document.getElementById('resultKeluhanSetelah').textContent = document.getElementById('evalKeluhanInput').value;
    }
    if (document.getElementById('evalResponsInput').value) {
        document.getElementById('resultRespons').textContent = document.getElementById('evalResponsInput').value;
    }
    
    // Show modal
    openEvalModal('Berhasil Disimpan', `Evaluasi untuk pasien ${patientName} (${kondisi}, Status: ${status}) berhasil dicatat.`);
    document.getElementById('formEvaluasi').reset();
}

function viewHistoryDetail(name, date, kondisi, hasil, status) {
    openEvalModal(`Detail Evaluasi - ${name}`, `Tanggal: ${date}\nKondisi: ${kondisi}\nStatus: ${status}\nHasil Evaluasi: ${hasil}`);
}

function openEvalModal(title, msg) {
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modalMessage').innerText = msg;
    const modal = document.getElementById('evalModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeEvalModal() {
    const modal = document.getElementById('evalModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>
@endsection
