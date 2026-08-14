@extends('layouts.app')

@section('title', 'Asuhan Keperawatan - Mandalacare')
@section('header_title', 'Asuhan Keperawatan')
@section('header_subtitle', 'Kelola rencana asuhan keperawatan pasien secara terstruktur.')

@section('content')
<div class="p-6 md:p-8 lg:p-10 max-w-container-max mx-auto w-full flex flex-col gap-8">
    <!-- Page Header -->
    <div>
        <h2 class="font-headline-lg text-headline-lg text-on-surface">Asuhan Keperawatan</h2>
        <p class="font-body-md text-body-md text-on-surface-variant mt-1">Kelola rencana asuhan keperawatan pasien secara terstruktur.</p>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-gutter">
        <!-- Left Column (Workflow) -->
        <div class="xl:col-span-2 flex flex-col gap-stack-lg">
            <!-- Section 1: Pilih Pasien -->
            <section class="bg-white rounded-xl border border-outline-variant/30 p-6 card-shadow">
                <h3 class="font-headline-md text-headline-md text-on-surface mb-4">1. Pilih Pasien</h3>
                <div class="relative mb-4">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                    <input id="askepSearchInput" onkeyup="searchPasienAskep()" class="w-full bg-surface border border-outline-variant rounded-xl py-3 pl-10 pr-4 font-body-md text-body-md text-on-surface input-ring" placeholder="Cari nama pasien, NIK, atau nomor telepon..." type="text" value="Andi Pratama">
                </div>
                <!-- Search Result Mock -->
                <div class="flex items-center justify-between p-4 bg-surface-container-low rounded-lg border border-primary/20">
                    <div class="flex items-center gap-4">
                        <div id="askepAvatar" class="w-12 h-12 bg-primary-container/20 rounded-full flex items-center justify-center text-primary font-bold text-lg flex-shrink-0">
                            AP
                        </div>
                        <div>
                            <p id="askepPatientName" class="font-label-md text-label-md text-on-surface">Andi Pratama</p>
                            <p id="askepPatientDetails" class="font-body-sm text-body-sm text-on-surface-variant">NIK: 3271••••890 • RM: 002-145</p>
                        </div>
                    </div>
                    <button type="button" class="bg-primary text-white px-4 py-2 rounded-lg font-label-md text-label-md hover:bg-primary/90 transition-colors">
                        Terpilih
                    </button>
                </div>
            </section>

            <!-- Section 2: Data Pasien -->
            <section class="bg-white rounded-xl border border-outline-variant/30 p-6 card-shadow">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-outline-variant/20">
                    <h3 class="font-headline-md text-headline-md text-on-surface">2. Data Pasien</h3>
                    <a href="{{ route('rekam-medis') }}" class="text-secondary border border-secondary px-4 py-2 rounded-lg font-label-md text-label-md hover:bg-secondary/10 transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">open_in_new</span>
                        Lihat Rekam Medis
                    </a>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div>
                        <p class="font-label-sm text-label-sm text-on-surface-variant mb-1">Tanggal Lahir</p>
                        <p id="askepDob" class="font-body-md text-body-md text-on-surface">15 Mei 1985 (38 thn)</p>
                    </div>
                    <div>
                        <p class="font-label-sm text-label-sm text-on-surface-variant mb-1">Jenis Kelamin</p>
                        <p id="askepGender" class="font-body-md text-body-md text-on-surface">Laki-laki</p>
                    </div>
                    <div>
                        <p class="font-label-sm text-label-sm text-on-surface-variant mb-1">Golongan Darah</p>
                        <p id="askepBlood" class="font-body-md text-body-md text-on-surface">O+</p>
                    </div>
                    <div>
                        <p class="font-label-sm text-label-sm text-on-surface-variant mb-1">Tanggal Kunjungan</p>
                        <p id="askepVisitDate" class="font-body-md text-body-md text-on-surface">24 Okt 2023</p>
                    </div>
                </div>
            </section>

            <!-- Section 3: Pengkajian Pasien -->
            <section class="bg-white rounded-xl border border-outline-variant/30 p-6 card-shadow">
                <h3 class="font-headline-md text-headline-md text-on-surface mb-6">3. Pengkajian Pasien</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block font-label-md text-label-md text-on-surface mb-2">Keluhan Utama</label>
                        <textarea id="askepKeluhanUtama" class="w-full bg-surface border border-outline-variant rounded-xl p-3 font-body-md text-body-md text-on-surface input-ring min-h-[100px]" placeholder="Deskripsikan keluhan utama pasien..."></textarea>
                    </div>
                    <div>
                        <label class="block font-label-md text-label-md text-on-surface mb-2">Riwayat Keluhan</label>
                        <textarea id="askepRiwayatKeluhan" class="w-full bg-surface border border-outline-variant rounded-xl p-3 font-body-md text-body-md text-on-surface input-ring min-h-[100px]" placeholder="Riwayat penyakit atau keluhan sebelumnya..."></textarea>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block font-label-md text-label-md text-on-surface mb-2">Kondisi Umum</label>
                        <select class="w-full bg-surface border border-outline-variant rounded-xl p-3 font-body-md text-body-md text-on-surface input-ring">
                            <option>Baik</option>
                            <option>Cukup</option>
                            <option>Lemah</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-label-md text-label-md text-on-surface mb-2">Kesadaran</label>
                        <select class="w-full bg-surface border border-outline-variant rounded-xl p-3 font-body-md text-body-md text-on-surface input-ring">
                            <option>Compos Mentis</option>
                            <option>Apatis</option>
                            <option>Delirium</option>
                            <option>Somnolen</option>
                            <option>Sopor</option>
                            <option>Koma</option>
                        </select>
                    </div>
                </div>
                <h4 class="font-label-md text-label-md text-on-surface mb-4">Tanda-tanda Vital</h4>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Tekanan Darah (mmHg)</label>
                        <input class="w-full bg-surface border border-outline-variant rounded-xl p-2 font-body-sm text-body-sm text-center input-ring" type="text" value="120/80">
                    </div>
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Nadi (x/mnt)</label>
                        <input class="w-full bg-surface border border-outline-variant rounded-xl p-2 font-body-sm text-body-sm text-center input-ring" type="text" value="82">
                    </div>
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Suhu (°C)</label>
                        <input class="w-full bg-surface border border-outline-variant rounded-xl p-2 font-body-sm text-body-sm text-center input-ring" type="text" value="36.5">
                    </div>
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Pernapasan (x/mnt)</label>
                        <input class="w-full bg-surface border border-outline-variant rounded-xl p-2 font-body-sm text-body-sm text-center input-ring" type="text" value="18">
                    </div>
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">SpO2 (%)</label>
                        <input class="w-full bg-surface border border-outline-variant rounded-xl p-2 font-body-sm text-body-sm text-center input-ring" type="text" value="98">
                    </div>
                </div>
            </section>

            <!-- Section 4: Diagnosis Keperawatan -->
            <section class="bg-white rounded-xl border border-outline-variant/30 p-6 card-shadow">
                <h3 class="font-headline-md text-headline-md text-on-surface mb-6">4. Diagnosis Keperawatan</h3>
                <div class="mb-6">
                    <label class="block font-label-md text-label-md text-on-surface mb-2">Pilih Diagnosis (SDKI)</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                        <input class="w-full bg-surface border border-outline-variant rounded-xl py-3 pl-10 pr-4 font-body-md text-body-md text-on-surface input-ring" placeholder="Cari kode atau nama diagnosis..." type="text">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block font-label-md text-label-md text-on-surface mb-2">Masalah / Keluhan Utama</label>
                        <textarea class="w-full bg-surface border border-outline-variant rounded-xl p-3 font-body-md text-body-md text-on-surface input-ring min-h-[100px]" placeholder="Jelaskan masalah spesifik..."></textarea>
                    </div>
                    <div>
                        <label class="block font-label-md text-label-md text-on-surface mb-2">Faktor Terkait (Etiologi)</label>
                        <textarea class="w-full bg-surface border border-outline-variant rounded-xl p-3 font-body-md text-body-md text-on-surface input-ring min-h-[100px]" placeholder="Penyebab atau faktor risiko..."></textarea>
                    </div>
                </div>
            </section>

            <!-- Section 5: Rencana Asuhan -->
            <section class="bg-white rounded-xl border border-outline-variant/30 p-6 card-shadow">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-headline-md text-headline-md text-on-surface">5. Rencana Asuhan (Intervensi)</h3>
                    <button type="button" onclick="openAddPlanModal()" class="bg-surface-container-low text-primary border border-primary/20 px-4 py-2 rounded-lg font-label-md text-label-md hover:bg-surface-container-high transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">add</span>
                        Tambah Rencana
                    </button>
                </div>
                <div class="overflow-x-auto rounded-lg border border-outline-variant/30">
                    <table class="w-full text-left border-collapse" id="planTable">
                        <thead>
                            <tr class="bg-surface-container-low border-b border-outline-variant/30">
                                <th class="py-3 px-4 font-label-md text-label-md text-on-surface w-12">No.</th>
                                <th class="py-3 px-4 font-label-md text-label-md text-on-surface">Rencana Tindakan</th>
                                <th class="py-3 px-4 font-label-md text-label-md text-on-surface">Target</th>
                                <th class="py-3 px-4 font-label-md text-label-md text-on-surface">Keterangan</th>
                                <th class="py-3 px-4 font-label-md text-label-md text-on-surface w-20 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="planTableBody">
                            <tr class="border-b border-outline-variant/20 hover:bg-surface/50">
                                <td class="py-3 px-4 font-body-md text-body-md text-on-surface-variant row-number">1</td>
                                <td class="py-3 px-4 font-body-md text-body-md text-on-surface">Monitor tanda vital setiap 4 jam</td>
                                <td class="py-3 px-4 font-body-md text-body-md text-on-surface-variant">TD stabil</td>
                                <td class="py-3 px-4 font-body-md text-body-md text-on-surface-variant">Rutin</td>
                                <td class="py-3 px-4 text-center">
                                    <button type="button" onclick="deletePlanRow(this)" class="text-error hover:bg-error-container p-1 rounded transition-colors">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </button>
                                </td>
                            </tr>
                            <tr class="hover:bg-surface/50">
                                <td class="py-3 px-4 font-body-md text-body-md text-on-surface-variant row-number">2</td>
                                <td class="py-3 px-4 font-body-md text-body-md text-on-surface">Observasi keluhan nyeri secara komprehensif</td>
                                <td class="py-3 px-4 font-body-md text-body-md text-on-surface-variant">Skala nyeri &lt; 3</td>
                                <td class="py-3 px-4 font-body-md text-body-md text-on-surface-variant">PQRST</td>
                                <td class="py-3 px-4 text-center">
                                    <button type="button" onclick="deletePlanRow(this)" class="text-error hover:bg-error-container p-1 rounded transition-colors">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <!-- Right Column (Summary & Actions) -->
        <div class="xl:col-span-1">
            <div class="sticky top-24 flex flex-col gap-6">
                <!-- Section 7: Ringkasan -->
                <div class="bg-white rounded-xl border border-outline-variant/30 p-6 card-shadow">
                    <h3 class="font-label-md text-label-md text-on-surface uppercase tracking-wider mb-4 border-b border-outline-variant/20 pb-2">Ringkasan Dokumen</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="font-label-sm text-label-sm text-on-surface-variant">Pasien</p>
                            <p id="summaryPatientName" class="font-body-md text-body-md text-on-surface font-medium">Andi Pratama</p>
                        </div>
                        <div>
                            <p class="font-label-sm text-label-sm text-on-surface-variant">Status Draft</p>
                            <div id="summaryDraftStatus" class="inline-flex items-center gap-1 bg-surface-container-high px-2 py-1 rounded text-primary text-xs font-medium mt-1">
                                <span class="material-symbols-outlined text-[14px]">edit_document</span>
                                Belum Tersimpan
                            </div>
                        </div>
                        <div>
                            <p class="font-label-sm text-label-sm text-on-surface-variant">Jumlah Rencana</p>
                            <p id="summaryPlanCount" class="font-body-md text-body-md text-on-surface">2 Tindakan</p>
                        </div>
                        <div class="pt-4 border-t border-outline-variant/20 text-xs text-on-surface-variant italic">
                            Terakhir diperbarui: Hari ini, 10:45 AM
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col gap-3">
                    <button type="button" onclick="saveAsuhanKeperawatan()" class="w-full bg-primary text-white py-3 rounded-xl font-label-md text-label-md hover:bg-primary/90 transition-colors shadow-sm flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-sm">save</span>
                        Simpan Asuhan Keperawatan
                    </button>
                    <button type="button" onclick="saveDraft()" class="w-full bg-white text-secondary border border-secondary py-3 rounded-xl font-label-md text-label-md hover:bg-secondary/5 transition-colors">
                        Simpan Draft
                    </button>
                    <a href="{{ route('dashboard') }}" class="w-full bg-transparent text-on-surface-variant py-3 rounded-xl font-label-md text-label-md hover:bg-surface-container-high transition-colors text-center block">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Rencana -->
<div id="addPlanModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full shadow-2xl border border-outline-variant/30">
        <h4 class="text-lg font-bold text-on-surface mb-4">Tambah Rencana Tindakan</h4>
        <div class="space-y-4">
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface mb-1">Rencana Tindakan</label>
                <input id="inputPlanTindakan" class="w-full bg-surface border border-outline-variant rounded-xl p-3 font-body-sm text-body-sm text-on-surface input-ring" placeholder="Contoh: Monitor tanda vital..." type="text">
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface mb-1">Target</label>
                <input id="inputPlanTarget" class="w-full bg-surface border border-outline-variant rounded-xl p-3 font-body-sm text-body-sm text-on-surface input-ring" placeholder="Contoh: TD stabil..." type="text">
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface mb-1">Keterangan</label>
                <input id="inputPlanKet" class="w-full bg-surface border border-outline-variant rounded-xl p-3 font-body-sm text-body-sm text-on-surface input-ring" placeholder="Contoh: Rutin, PQRST..." type="text">
            </div>
        </div>
        <div class="flex justify-end gap-3 mt-6">
            <button type="button" onclick="closeAddPlanModal()" class="px-4 py-2 text-on-surface-variant rounded-lg font-label-md text-label-md hover:bg-surface-container-low transition-colors">Batal</button>
            <button type="button" onclick="addPlanRow()" class="px-5 py-2 bg-primary text-white rounded-lg font-label-md text-label-md hover:bg-primary/90 transition-colors">Tambah</button>
        </div>
    </div>
</div>

<!-- Modal Notifikasi -->
<div id="askepNotificationModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full shadow-2xl border border-outline-variant">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center">
                <span class="material-symbols-outlined">check_circle</span>
            </div>
            <h4 id="askepNotifTitle" class="text-lg font-bold text-on-surface">Berhasil Disimpan</h4>
        </div>
        <p id="askepNotifMessage" class="text-sm text-on-surface-variant mb-6">Dokumen Asuhan Keperawatan berhasil disimpan ke rekam medis pasien.</p>
        <div class="flex justify-end">
            <button type="button" onclick="closeAskepNotif()" class="px-5 py-2.5 bg-primary text-white rounded-lg font-semibold text-sm hover:bg-[#005a3c] transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
function searchPasienAskep() {
    const q = document.getElementById('askepSearchInput').value.trim();
    if (!q) return;
    document.getElementById('askepPatientName').textContent = q;
    document.getElementById('summaryPatientName').textContent = q;
    const initials = q.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
    document.getElementById('askepAvatar').textContent = initials || 'PS';
}

function updatePlanCount() {
    const rows = document.querySelectorAll('#planTableBody tr');
    document.getElementById('summaryPlanCount').textContent = rows.length + ' Tindakan';
    rows.forEach((row, idx) => {
        const numCell = row.querySelector('.row-number');
        if (numCell) numCell.textContent = idx + 1;
    });
}

function deletePlanRow(btn) {
    const row = btn.closest('tr');
    row.remove();
    updatePlanCount();
}

function openAddPlanModal() {
    document.getElementById('inputPlanTindakan').value = '';
    document.getElementById('inputPlanTarget').value = '';
    document.getElementById('inputPlanKet').value = '';
    const m = document.getElementById('addPlanModal');
    m.classList.remove('hidden');
    m.classList.add('flex');
}

function closeAddPlanModal() {
    const m = document.getElementById('addPlanModal');
    m.classList.add('hidden');
    m.classList.remove('flex');
}

function addPlanRow() {
    const tindakan = document.getElementById('inputPlanTindakan').value.trim();
    const target = document.getElementById('inputPlanTarget').value.trim() || '-';
    const ket = document.getElementById('inputPlanKet').value.trim() || '-';
    
    if (!tindakan) {
        alert('Silakan masukkan rencana tindakan.');
        return;
    }
    
    const tbody = document.getElementById('planTableBody');
    const newRow = document.createElement('tr');
    newRow.className = "border-b border-outline-variant/20 hover:bg-surface/50";
    newRow.innerHTML = `
        <td class="py-3 px-4 font-body-md text-body-md text-on-surface-variant row-number"></td>
        <td class="py-3 px-4 font-body-md text-body-md text-on-surface">${tindakan}</td>
        <td class="py-3 px-4 font-body-md text-body-md text-on-surface-variant">${target}</td>
        <td class="py-3 px-4 font-body-md text-body-md text-on-surface-variant">${ket}</td>
        <td class="py-3 px-4 text-center">
            <button type="button" onclick="deletePlanRow(this)" class="text-error hover:bg-error-container p-1 rounded transition-colors">
                <span class="material-symbols-outlined text-sm">delete</span>
            </button>
        </td>
    `;
    tbody.appendChild(newRow);
    updatePlanCount();
    closeAddPlanModal();
}

function saveAsuhanKeperawatan() {
    const name = document.getElementById('askepPatientName').textContent;
    document.getElementById('summaryDraftStatus').innerHTML = `
        <span class="material-symbols-outlined text-[14px]">check_circle</span>
        Tersimpan
    `;
    document.getElementById('summaryDraftStatus').className = "inline-flex items-center gap-1 bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium mt-1";
    
    showAskepNotif('Berhasil Disimpan', `Asuhan Keperawatan untuk pasien ${name} berhasil disimpan secara terstruktur.`);
}

function saveDraft() {
    const name = document.getElementById('askepPatientName').textContent;
    document.getElementById('summaryDraftStatus').innerHTML = `
        <span class="material-symbols-outlined text-[14px]">draft</span>
        Draft Tersimpan
    `;
    document.getElementById('summaryDraftStatus').className = "inline-flex items-center gap-1 bg-amber-100 text-amber-800 px-2 py-1 rounded text-xs font-medium mt-1";
    
    showAskepNotif('Draft Tersimpan', `Draft Asuhan Keperawatan untuk pasien ${name} berhasil diperbarui.`);
}

function showAskepNotif(title, msg) {
    document.getElementById('askepNotifTitle').textContent = title;
    document.getElementById('askepNotifMessage').textContent = msg;
    const modal = document.getElementById('askepNotificationModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeAskepNotif() {
    const modal = document.getElementById('askepNotificationModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>
@endsection
