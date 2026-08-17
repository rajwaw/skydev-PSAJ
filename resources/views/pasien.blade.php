@extends('layouts.app')

@section('title', 'Pasien - Mandalacare')

@section('content')
<div class="p-4 sm:p-6 md:p-8 lg:p-10 w-full max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 md:mb-8 gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-on-surface mb-1 sm:mb-2">Data Pasien</h1>
            <p class="text-sm sm:text-base text-on-surface-variant">Kelola dan cari data pasien yang terdaftar di Mandalacare.</p>
        </div>
        <a href="{{ route('pendaftaran') }}" class="w-full sm:w-auto bg-primary text-white px-5 py-2.5 sm:px-6 sm:py-3 rounded-lg font-semibold hover:bg-[#005a3c] transition-colors shadow-sm flex items-center justify-center gap-2 text-sm shrink-0">
            <span class="material-symbols-outlined text-sm">add</span>
            Tambah Pasien
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 md:mb-8">
        <!-- Card 1: Total Pasien -->
        <div class="bg-white border border-outline-variant rounded-xl p-5 sm:p-6 card-shadow flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-[#E5F5F0] flex items-center justify-center text-primary shrink-0">
                <span class="material-symbols-outlined text-2xl">group</span>
            </div>
            <div class="min-w-0">
                <p class="text-xs sm:text-sm text-on-surface-variant mb-0.5 truncate">Total Pasien</p>
                <h3 class="text-xl sm:text-2xl text-on-surface font-semibold">{{ $totalPasien }}</h3>
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
});
</script>
@endsection