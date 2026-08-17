@extends('layouts.app')

@section('title', 'Pasien - Mandalacare')

@section('content')

{{-- ========================================================== --}}
{{-- NOTIFIKASI TOAST (BAGIAN ATAS DENGAN AKSEN WARNA HIJAU) --}}
{{-- ========================================================== --}}
<div id="toastNotification" class="fixed top-6 left-1/2 -translate-x-1/2 z-50 w-[92%] max-w-lg pointer-events-none transition-all duration-300 transform -translate-y-16 opacity-0 hidden">
    <div id="toastCard" class="pointer-events-auto bg-white border-2 border-primary/40 rounded-2xl shadow-2xl p-4 md:p-5 flex items-start gap-4 backdrop-blur-md bg-white/95 relative overflow-hidden">
        <!-- Green Accent Bar on the Left -->
        <div id="toastAccentBar" class="absolute left-0 top-0 bottom-0 w-2 bg-primary"></div>
        
        <!-- Icon Badge -->
        <div id="toastIconContainer" class="w-11 h-11 rounded-xl bg-[#E5F5F0] text-primary flex items-center justify-center flex-shrink-0 shadow-sm">
            <span id="toastIcon" class="material-symbols-outlined text-2xl font-bold">check_circle</span>
        </div>

        <!-- Content -->
        <div class="flex-1 min-w-0 pr-2">
            <div class="flex items-center gap-2 mb-1">
                <span id="toastBadge" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#E5F5F0] text-primary">
                    Pemberitahuan
                </span>
                <span class="text-xs text-on-surface-variant font-medium">Baru saja</span>
            </div>
            
            <h4 class="text-base font-bold text-on-surface" id="toastTitle">
                Berhasil!
            </h4>
            
            <p class="text-sm text-on-surface-variant mt-1" id="toastMessage">
                Operasi berhasil dilakukan.
            </p>
        </div>

        <!-- Close Button -->
        <button type="button" onclick="hideToastNotification()" class="text-on-surface-variant hover:text-on-surface p-1 rounded-lg hover:bg-surface-container transition-colors flex-shrink-0" title="Tutup Notifikasi">
            <span class="material-symbols-outlined text-lg">close</span>
        </button>

        <!-- Auto-dismiss Progress Bar -->
        <div id="toastProgress" class="absolute bottom-0 left-0 h-1 bg-primary w-full transition-all linear"></div>
    </div>
</div>

{{-- ========================================================== --}}
{{-- MODAL KONFIRMASI HAPUS PASIEN --}}
{{-- ========================================================== --}}
<div id="deleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm hidden transition-opacity duration-200">
    <div class="bg-white rounded-2xl border border-outline-variant shadow-2xl max-w-md w-full p-6 relative transform transition-all scale-95 duration-200" id="deleteModalContent">
        <!-- Close button -->
        <button type="button" onclick="closeDeleteModal()" class="absolute top-4 right-4 text-on-surface-variant hover:text-on-surface p-1.5 rounded-lg hover:bg-surface-container transition-colors">
            <span class="material-symbols-outlined text-xl">close</span>
        </button>

        <!-- Warning Icon Badge -->
        <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center mb-4">
            <span class="material-symbols-outlined text-2xl font-bold">delete_forever</span>
        </div>

        <!-- Modal Text -->
        <h3 class="text-lg sm:text-xl font-bold text-on-surface mb-2">Hapus Data Pasien?</h3>
        <p class="text-sm text-on-surface-variant mb-6">
            Apakah Anda yakin ingin menghapus data pasien <strong id="deletePatientName" class="text-on-surface font-semibold"></strong>?
            <span class="block mt-1 text-xs text-red-500 font-medium">Tindakan ini tidak dapat dibatalkan dan akan menghapus riwayat terkait.</span>
        </p>

        <!-- Modal Action Buttons -->
        <div class="flex flex-col-reverse sm:flex-row justify-end gap-2.5 sm:gap-3">
            <button type="button" onclick="closeDeleteModal()" class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-outline-variant text-on-surface-variant font-semibold text-sm hover:bg-surface-container-low transition-colors">
                Batal
            </button>
            <button type="button" id="btnConfirmDelete" onclick="executeDeletePasien()" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm transition-colors shadow-sm flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-base">delete</span>
                <span>Ya, Hapus Pasien</span>
            </button>
        </div>
    </div>
</div>

<div class="p-4 sm:p-6 md:p-8 lg:p-10 w-full max-w-7xl mx-auto space-y-6 sm:space-y-8">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-on-surface tracking-tight">Data Pasien</h1>
            <p class="text-sm sm:text-base text-on-surface-variant mt-1">Kelola dan cari data pasien yang terdaftar di Mandalacare.</p>
        </div>
        <a href="{{ route('pendaftaran') }}" class="w-full sm:w-auto bg-primary text-white px-5 py-2.5 sm:px-6 sm:py-3 rounded-lg font-semibold hover:bg-[#005a3c] transition-colors shadow-sm flex items-center justify-center gap-2 text-sm shrink-0">
            <span class="material-symbols-outlined text-sm">add</span>
            Tambah Pasien
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        <!-- Card 1: Total Pasien -->
        <div class="bg-white border border-outline-variant rounded-xl p-5 sm:p-6 card-shadow flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-[#E5F5F0] flex items-center justify-center text-primary shrink-0">
                <span class="material-symbols-outlined text-2xl">group</span>
            </div>
            <div class="min-w-0">
                <p class="text-xs sm:text-sm text-on-surface-variant mb-0.5 truncate">Total Pasien</p>
                <h3 class="text-xl sm:text-2xl text-on-surface font-semibold" id="totalPasienCounter">{{ $totalPasien }}</h3>
            </div>
        </div>

        <!-- Card 2: Pasien Baru -->
        <div class="bg-white border border-outline-variant rounded-xl p-5 sm:p-6 card-shadow flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-[#E8F0FE] flex items-center justify-center text-[#1A73E8] shrink-0">
                <span class="material-symbols-outlined text-2xl">person_add</span>
            </div>
            <div class="min-w-0">
                <p class="text-xs sm:text-sm text-on-surface-variant mb-0.5 truncate">Pasien Baru</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-xl sm:text-2xl text-on-surface font-semibold">{{ $pasienBaru }}</h3>
                    <span class="text-xs text-on-surface-variant truncate">Bulan ini</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Kunjungan Hari Ini -->
        <div class="bg-white border border-outline-variant rounded-xl p-5 sm:p-6 card-shadow flex items-center gap-4 sm:col-span-2 lg:col-span-1">
            <div class="w-12 h-12 rounded-full bg-[#F3E8FF] flex items-center justify-center text-[#9333EA] shrink-0">
                <span class="material-symbols-outlined text-2xl">today</span>
            </div>
            <div class="min-w-0">
                <p class="text-xs sm:text-sm text-on-surface-variant mb-0.5 truncate">Kunjungan Hari Ini</p>
                <h3 class="text-xl sm:text-2xl text-on-surface font-semibold">{{ $kunjunganHariIni }}</h3>
            </div>
        </div>
    </div>

    <!-- Main Data Section (Table & Search) -->
    <div class="bg-white border border-outline-variant rounded-xl card-shadow overflow-hidden">
        <!-- Search & Filter Bar -->
        <div class="p-4 sm:p-6 border-b border-outline-variant flex flex-col md:flex-row gap-3 sm:gap-4 items-stretch md:items-center justify-between">
            <div class="hidden md:block">
                <h3 class="text-base text-on-surface font-semibold">Cari Pasien</h3>
            </div>
            <form id="searchPasienForm" method="GET" action="{{ route('pasien') }}" class="flex flex-col sm:flex-row flex-1 max-w-2xl gap-2.5 sm:gap-3 w-full" onsubmit="event.preventDefault(); performLiveSearch(document.getElementById('searchPasienInput').value.trim(), 1);">
                <div class="relative flex-1">
                    <span id="searchIcon" class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                    <svg id="searchSpinner" class="hidden animate-spin h-5 w-5 text-primary absolute left-3.5 top-1/2 -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <input 
                        id="searchPasienInput" 
                        name="search" 
                        value="{{ $search ?? '' }}" 
                        oninput="handlePasienSearchInput()" 
                        autocomplete="off"
                        class="w-full pl-10 pr-10 py-2.5 rounded-lg border border-outline-variant focus:border-primary focus:ring-3 focus:ring-primary/20 outline-none transition-all text-sm text-on-surface bg-white" 
                        placeholder="Cari nama, NIK, atau no. telp..." 
                        type="text"
                    >
                    <button 
                        type="button" 
                        id="btnClearInput" 
                        onclick="clearPasienSearch()" 
                        class="{{ !empty($search) ? '' : 'hidden' }} absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface p-0.5 rounded transition-colors"
                        title="Hapus pencarian"
                    >
                        <span class="material-symbols-outlined text-base">close</span>
                    </button>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 sm:flex-none justify-center flex items-center gap-2 px-5 py-2.5 rounded-lg bg-primary text-white hover:bg-[#005a3c] transition-colors text-sm font-semibold shadow-sm">
                        <span class="material-symbols-outlined text-sm">search</span>
                        Cari
                    </button>
                    <button 
                        type="button" 
                        id="btnResetSearch" 
                        onclick="clearPasienSearch()" 
                        class="{{ !empty($search) ? '' : 'hidden' }} flex-1 sm:flex-none justify-center flex items-center gap-1 px-3 py-2.5 rounded-lg border border-outline-variant text-on-surface-variant hover:bg-surface-container-low transition-colors text-sm font-medium"
                    >
                        <span class="material-symbols-outlined text-sm">restart_alt</span>
                        Reset
                    </button>
                </div>
            </form>
        </div>

        <!-- Table Container with Horizontal Scroll Hint -->
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse min-w-[700px]" id="pasienTable">
                <thead>
                    <tr class="bg-[#F8FAFC] border-b border-outline-variant">
                        <th class="py-3.5 px-4 sm:px-6 text-xs text-on-surface-variant font-medium whitespace-nowrap">No.</th>
                        <th class="py-3.5 px-4 sm:px-6 text-xs text-on-surface-variant font-medium whitespace-nowrap">Nama Pasien</th>
                        <th class="py-3.5 px-4 sm:px-6 text-xs text-on-surface-variant font-medium whitespace-nowrap">NIK</th>
                        <th class="py-3.5 px-4 sm:px-6 text-xs text-on-surface-variant font-medium whitespace-nowrap">Tanggal Lahir</th>
                        <th class="py-3.5 px-4 sm:px-6 text-xs text-on-surface-variant font-medium whitespace-nowrap">Jenis Kelamin</th>
                        <th class="py-3.5 px-4 sm:px-6 text-xs text-on-surface-variant font-medium whitespace-nowrap">No. Telepon</th>
                        <th class="py-3.5 px-4 sm:px-6 text-xs text-on-surface-variant font-medium whitespace-nowrap">Kunjungan Terakhir</th>
                        <th class="py-3.5 px-4 sm:px-6 text-xs text-on-surface-variant font-medium whitespace-nowrap text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant text-on-surface" id="pasienTableBody">
                    @include('pasien.table', ['pasiens' => $pasiens, 'search' => $search])
                </tbody>
            </table>
        </div>

        <!-- Pagination Container -->
        <div id="pasienPagination">
            @include('pasien.pagination', ['pasiens' => $pasiens])
        </div>
    </div>
</div>

<script>
let searchDebounceTimer = null;
let currentSearchAbortController = null;
let pendingDeleteId = null;
let toastTimeout = null;

// ==========================================
// TOAST NOTIFICATION HANDLER
// ==========================================
function showToastNotification(title, message, isError = false) {
    const toast = document.getElementById('toastNotification');
    const toastTitle = document.getElementById('toastTitle');
    const toastMsg = document.getElementById('toastMessage');
    const toastAccent = document.getElementById('toastAccentBar');
    const toastIcon = document.getElementById('toastIcon');
    const toastIconContainer = document.getElementById('toastIconContainer');
    const toastBadge = document.getElementById('toastBadge');
    const toastProgress = document.getElementById('toastProgress');

    if (!toast) return;

    toastTitle.textContent = title;
    toastMsg.textContent = message;

    if (isError) {
        toastAccent.className = "absolute left-0 top-0 bottom-0 w-2 bg-red-600";
        toastIconContainer.className = "w-11 h-11 rounded-xl bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0 shadow-sm";
        toastIcon.textContent = "error";
        toastBadge.className = "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700";
        toastBadge.textContent = "Gagal";
        toastProgress.className = "absolute bottom-0 left-0 h-1 bg-red-600 w-full transition-all linear";
    } else {
        toastAccent.className = "absolute left-0 top-0 bottom-0 w-2 bg-primary";
        toastIconContainer.className = "w-11 h-11 rounded-xl bg-[#E5F5F0] text-primary flex items-center justify-center flex-shrink-0 shadow-sm";
        toastIcon.textContent = "check_circle";
        toastBadge.className = "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#E5F5F0] text-primary";
        toastBadge.textContent = "Berhasil";
        toastProgress.className = "absolute bottom-0 left-0 h-1 bg-primary w-full transition-all linear";
    }

    // Reset animation
    toast.classList.remove('hidden');
    setTimeout(() => {
        toast.classList.remove('-translate-y-16', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');
    }, 10);

    // Progress Bar Animation (4s)
    if (toastProgress) {
        toastProgress.style.transition = 'none';
        toastProgress.style.width = '100%';
        setTimeout(() => {
            toastProgress.style.transition = 'width 4000ms linear';
            toastProgress.style.width = '0%';
        }, 50);
    }

    if (toastTimeout) clearTimeout(toastTimeout);
    toastTimeout = setTimeout(() => {
        hideToastNotification();
    }, 4000);
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

// ==========================================
// DELETE MODAL HANDLER
// ==========================================
function confirmDeletePasien(id, name) {
    pendingDeleteId = id;
    document.getElementById('deletePatientName').textContent = name;
    
    const modal = document.getElementById('deleteModal');
    const content = document.getElementById('deleteModalContent');
    if (!modal) return;

    modal.classList.remove('hidden');
    setTimeout(() => {
        content.classList.remove('scale-95');
        content.classList.add('scale-100');
    }, 10);
}

function closeDeleteModal() {
    pendingDeleteId = null;
    const modal = document.getElementById('deleteModal');
    const content = document.getElementById('deleteModalContent');
    if (!modal) return;

    content.classList.remove('scale-100');
    content.classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 150);
}

async function executeDeletePasien() {
    if (!pendingDeleteId) return;

    const id = pendingDeleteId;
    const btn = document.getElementById('btnConfirmDelete');
    const originalBtnHtml = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = `
        <svg class="animate-spin h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>Menghapus...</span>
    `;

    try {
        const response = await fetch(`/pasien/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        const result = await response.json();

        if (response.ok && result.success) {
            closeDeleteModal();
            showToastNotification('Data Terhapus', result.message || 'Data pasien berhasil dihapus.');

            // Update Counter Pasien
            if (result.total !== undefined) {
                const counter = document.getElementById('totalPasienCounter');
                if (counter) counter.textContent = result.total;
            }

            // Animasi fade-out row
            const row = document.getElementById(`pasien-row-${id}`);
            if (row) {
                row.style.transition = 'all 0.3s ease';
                row.style.opacity = '0';
                row.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    row.remove();
                    // Cek jika tabel sudah kosong, refresh halaman/search
                    const tbody = document.getElementById('pasienTableBody');
                    if (tbody && tbody.children.length === 0) {
                        const query = document.getElementById('searchPasienInput')?.value.trim() || '';
                        performLiveSearch(query, 1);
                    }
                }, 300);
            } else {
                const query = document.getElementById('searchPasienInput')?.value.trim() || '';
                performLiveSearch(query, 1);
            }
        } else {
            showToastNotification('Gagal Menghapus', result.message || 'Terjadi kesalahan saat menghapus data pasien.', true);
        }
    } catch (err) {
        console.error('Delete error:', err);
        showToastNotification('Gagal Menghapus', 'Koneksi ke server bermasalah. Silakan coba lagi.', true);
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalBtnHtml;
    }
}

// ==========================================
// SEARCH & PAGINATION LIVE HANDLER
// ==========================================
function handlePasienSearchInput() {
    const input = document.getElementById('searchPasienInput');
    const resetBtn = document.getElementById('btnResetSearch');
    const clearInputBtn = document.getElementById('btnClearInput');
    const query = input.value.trim();

    // Tampilkan tombol reset/hapus saat ada teks
    if (query.length > 0) {
        if (resetBtn) resetBtn.classList.remove('hidden');
        if (clearInputBtn) clearInputBtn.classList.remove('hidden');
    } else {
        if (resetBtn) resetBtn.classList.add('hidden');
        if (clearInputBtn) clearInputBtn.classList.add('hidden');
    }

    // Debounce live search ke database (250ms)
    clearTimeout(searchDebounceTimer);
    searchDebounceTimer = setTimeout(() => {
        performLiveSearch(query, 1);
    }, 250);
}

async function performLiveSearch(query, page = 1) {
    const tbody = document.getElementById('pasienTableBody');
    const paginationContainer = document.getElementById('pasienPagination');
    const spinner = document.getElementById('searchSpinner');
    const searchIcon = document.getElementById('searchIcon');

    // Tampilkan spinner loading di dalam input pencarian
    if (spinner && searchIcon) {
        spinner.classList.remove('hidden');
        searchIcon.classList.add('hidden');
    }

    // Batalkan request pencarian sebelumnya yang masih berjalan jika ada
    if (currentSearchAbortController) {
        currentSearchAbortController.abort();
    }
    currentSearchAbortController = new AbortController();

    try {
        const url = new URL('{{ route('pasien') }}', window.location.origin);
        if (query) {
            url.searchParams.set('search', query);
        }
        if (page && page > 1) {
            url.searchParams.set('page', page);
        }
        url.searchParams.set('ajax', '1');

        const response = await fetch(url.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            signal: currentSearchAbortController.signal
        });

        if (response.ok) {
            const data = await response.json();
            if (tbody && data.tbody !== undefined) {
                tbody.innerHTML = data.tbody;
            }
            if (paginationContainer && data.pagination !== undefined) {
                paginationContainer.innerHTML = data.pagination;
            }

            // Perbarui URL browser tanpa reload halaman penuh
            const cleanUrl = new URL('{{ route('pasien') }}', window.location.origin);
            if (query) cleanUrl.searchParams.set('search', query);
            if (page && page > 1) cleanUrl.searchParams.set('page', page);
            window.history.replaceState({}, '', cleanUrl.toString());

            // Bind ulang event pagination AJAX
            bindPaginationLinks();
        }
    } catch (err) {
        if (err.name !== 'AbortError') {
            console.error('Search error:', err);
        }
    } finally {
        if (spinner && searchIcon) {
            spinner.classList.add('hidden');
            searchIcon.classList.remove('hidden');
        }
    }
}

function clearPasienSearch() {
    const input = document.getElementById('searchPasienInput');
    const resetBtn = document.getElementById('btnResetSearch');
    const clearInputBtn = document.getElementById('btnClearInput');

    if (input) {
        input.value = '';
        input.focus();
    }
    if (resetBtn) resetBtn.classList.add('hidden');
    if (clearInputBtn) clearInputBtn.classList.add('hidden');

    performLiveSearch('', 1);
}

function bindPaginationLinks() {
    const links = document.querySelectorAll('#pasienPagination a.pagination-link');
    links.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const page = this.getAttribute('data-page') || 1;
            const query = document.getElementById('searchPasienInput')?.value.trim() || '';
            performLiveSearch(query, page);
        });
    });
}

document.addEventListener('DOMContentLoaded', function () {
    bindPaginationLinks();

    @if (session('success'))
        showToastNotification('Berhasil', '{{ session('success') }}');
    @endif
    @if (session('error'))
        showToastNotification('Peringatan', '{{ session('error') }}', true);
    @endif
});
</script>
@endsection