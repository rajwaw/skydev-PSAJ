@extends('layouts.app')

@section('title', 'Pasien - Mandalacare')

@section('content')
<div class="p-6 md:p-8 lg:p-10 w-full max-w-container-max mx-auto">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-on-surface mb-2">Data Pasien</h1>
            <p class="text-base text-on-surface-variant">Kelola dan cari data pasien yang terdaftar di Mandalacare.</p>
        </div>
        <a href="{{ route('pendaftaran') }}" class="bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#005a3c] transition-colors shadow-sm flex items-center gap-2 text-sm">
            <span class="material-symbols-outlined text-sm">add</span>
            Tambah Pasien
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Card 1: Total Pasien -->
        <div class="bg-white border border-outline-variant rounded-xl p-6 card-shadow flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-[#E5F5F0] flex items-center justify-center text-primary">
                <span class="material-symbols-outlined text-2xl">group</span>
            </div>
            <div>
                <p class="text-sm text-on-surface-variant mb-1">Total Pasien</p>
                <h3 class="text-2xl text-on-surface font-semibold">{{ $totalPasien }}</h3>
            </div>
        </div>

        <!-- Card 2: Pasien Baru -->
        <div class="bg-white border border-outline-variant rounded-xl p-6 card-shadow flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-[#E8F0FE] flex items-center justify-center text-[#1A73E8]">
                <span class="material-symbols-outlined text-2xl">person_add</span>
            </div>
            <div>
                <p class="text-sm text-on-surface-variant mb-1">Pasien Baru</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-2xl text-on-surface font-semibold">{{ $pasienBaru }}</h3>
                    <span class="text-xs text-on-surface-variant">Bulan ini</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Kunjungan Hari Ini -->
        <div class="bg-white border border-outline-variant rounded-xl p-6 card-shadow flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-[#F3E8FF] flex items-center justify-center text-[#9333EA]">
                <span class="material-symbols-outlined text-2xl">today</span>
            </div>
            <div>
                <p class="text-sm text-on-surface-variant mb-1">Kunjungan Hari Ini</p>
                <h3 class="text-2xl text-on-surface font-semibold">{{ $kunjunganHariIni }}</h3>
            </div>
        </div>
    </div>

    <!-- Main Data Section (Table & Search) -->
    <div class="bg-white border border-outline-variant rounded-xl card-shadow overflow-hidden">
        <!-- Search & Filter Bar -->
        <div class="p-6 border-b border-outline-variant flex flex-col md:flex-row gap-4 items-center justify-between">
            <div>
                <h3 class="text-base text-on-surface font-semibold">Cari Pasien</h3>
            </div>
            <form method="GET" action="{{ route('pasien') }}" class="flex flex-1 max-w-2xl gap-3 w-full">
                <div class="relative flex-1">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                    <input 
                        id="searchPasienInput" 
                        name="search" 
                        value="{{ $search ?? '' }}" 
                        onkeyup="filterPasienTable()" 
                        class="w-full pl-12 pr-4 py-2.5 rounded-lg border border-outline-variant focus:border-primary focus:ring-3 focus:ring-primary/20 outline-none transition-all text-sm text-on-surface bg-white" 
                        placeholder="Cari berdasarkan nama, NIK, atau nomor telepon..." 
                        type="text"
                    >
                </div>
                <button type="submit" class="flex items-center gap-2 px-5 py-2.5 rounded-lg bg-primary text-white hover:bg-[#005a3c] transition-colors text-sm font-semibold shadow-sm">
                    <span class="material-symbols-outlined text-sm">search</span>
                    Cari
                </button>
                @if (!empty($search))
                    <a href="{{ route('pasien') }}" class="flex items-center gap-1 px-3 py-2.5 rounded-lg border border-outline-variant text-on-surface-variant hover:bg-surface-container-low transition-colors text-sm font-medium">
                        <span class="material-symbols-outlined text-sm">close</span>
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="pasienTable">
                <thead>
                    <tr class="bg-[#F8FAFC] border-b border-outline-variant">
                        <th class="py-4 px-6 text-xs text-on-surface-variant font-medium whitespace-nowrap">No.</th>
                        <th class="py-4 px-6 text-xs text-on-surface-variant font-medium whitespace-nowrap">Nama Pasien</th>
                        <th class="py-4 px-6 text-xs text-on-surface-variant font-medium whitespace-nowrap">NIK</th>
                        <th class="py-4 px-6 text-xs text-on-surface-variant font-medium whitespace-nowrap">Tanggal Lahir</th>
                        <th class="py-4 px-6 text-xs text-on-surface-variant font-medium whitespace-nowrap">Jenis Kelamin</th>
                        <th class="py-4 px-6 text-xs text-on-surface-variant font-medium whitespace-nowrap">No. Telepon</th>
                        <th class="py-4 px-6 text-xs text-on-surface-variant font-medium whitespace-nowrap">Kunjungan Terakhir</th>
                        <th class="py-4 px-6 text-xs text-on-surface-variant font-medium whitespace-nowrap text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant text-on-surface" id="pasienTableBody">
                    @forelse ($pasiens as $index => $pasien)
                        <tr class="hover:bg-surface-container-low transition-colors">
                            <td class="py-4 px-6 text-sm text-on-surface-variant">
                                {{ sprintf('%02d', ($pasiens->firstItem() ?? 1) + $index) }}
                            </td>
                            <td class="py-4 px-6 text-sm font-semibold">
                                {{ $pasien->nama_lengkap }}
                            </td>
                            <td class="py-4 px-6 text-sm">
                                {{ $pasien->nik }}
                            </td>
                            <td class="py-4 px-6 text-sm">
                                {{ $pasien->formatted_tgl_lahir }}
                            </td>
                            <td class="py-4 px-6 text-sm">
                                {{ $pasien->formatted_jk }}
                            </td>
                            <td class="py-4 px-6 text-sm">
                                {{ $pasien->no_telp ?: '-' }}
                            </td>
                            <td class="py-4 px-6 text-sm">
                                @if ($pasien->kunjungan_terakhir)
                                    @php
                                        $kunjungan = \Carbon\Carbon::parse($pasien->kunjungan_terakhir);
                                    @endphp
                                    @if ($kunjungan->isToday())
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-[#E5F5F0] text-primary font-medium text-xs">Hari ini</span>
                                    @elseif ($kunjungan->isYesterday())
                                        <span class="text-sm text-on-surface-variant">Kemarin</span>
                                    @else
                                        <span class="text-sm text-on-surface-variant">
                                            @php
                                                $monthsShort = [
                                                    1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
                                                    5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agt',
                                                    9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
                                                ];
                                                $day = $kunjungan->format('j');
                                                $mNum = (int) $kunjungan->format('n');
                                                $mStr = $monthsShort[$mNum] ?? $kunjungan->format('M');
                                                $yr = $kunjungan->format('Y');
                                            @endphp
                                            {{ $day }} {{ $mStr }} {{ $yr }}
                                        </span>
                                    @endif
                                @else
                                    <span class="text-sm text-on-surface-variant italic">-</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('rekam-medis') }}?id={{ $pasien->id_pasien }}" class="inline-flex items-center justify-center text-[#1A73E8] hover:text-[#1557B0] transition-colors p-1.5 rounded-lg hover:bg-[#E8F0FE]" title="Lihat Rekam Medis">
                                    <span class="material-symbols-outlined">chevron_right</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 px-6 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 rounded-full bg-[#E5F5F0] flex items-center justify-center text-primary mb-3">
                                        <span class="material-symbols-outlined text-3xl">person_off</span>
                                    </div>
                                    <h4 class="text-base font-semibold text-on-surface mb-1">
                                        @if (!empty($search))
                                            Tidak ada pasien yang cocok dengan pencarian "{{ $search }}"
                                        @else
                                            Belum Ada Data Pasien
                                        @endif
                                    </h4>
                                    <p class="text-sm text-on-surface-variant max-w-md mb-4">
                                        @if (!empty($search))
                                            Coba periksa kembali kata kunci nama, NIK, atau nomor telepon.
                                        @else
                                            Silakan lakukan pendaftaran pasien baru melalui menu pendaftaran.
                                        @endif
                                    </p>
                                    @if (empty($search))
                                        <a href="{{ route('pendaftaran') }}" class="bg-primary text-white px-5 py-2.5 rounded-lg font-semibold hover:bg-[#005a3c] transition-colors text-sm inline-flex items-center gap-2 shadow-sm">
                                            <span class="material-symbols-outlined text-sm">add</span>
                                            Daftarkan Pasien Pertama
                                        </a>
                                    @else
                                        <a href="{{ route('pasien') }}" class="text-primary hover:underline text-sm font-semibold">
                                            Reset Pencarian
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-outline-variant flex flex-col sm:flex-row items-center justify-between gap-4">
            <span class="text-sm text-on-surface-variant">
                @if ($pasiens->total() > 0)
                    Menampilkan {{ $pasiens->firstItem() }}–{{ $pasiens->lastItem() }} dari {{ $pasiens->total() }} pasien
                @else
                    Menampilkan 0 pasien
                @endif
            </span>

            @if ($pasiens->hasPages())
                <div class="flex items-center gap-1">
                    {{-- Tombol Sebelumnya --}}
                    @if ($pasiens->onFirstPage())
                        <button type="button" class="w-8 h-8 flex items-center justify-center rounded text-on-surface-variant/40 cursor-not-allowed" disabled>
                            <span class="material-symbols-outlined text-sm">chevron_left</span>
                        </button>
                    @else
                        <a href="{{ $pasiens->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded text-on-surface hover:bg-surface-container-low transition-colors">
                            <span class="material-symbols-outlined text-sm">chevron_left</span>
                        </a>
                    @endif

                    {{-- Angka Halaman --}}
                    @foreach ($pasiens->getUrlRange(1, $pasiens->lastPage()) as $page => $url)
                        @if ($page == $pasiens->currentPage())
                            <span class="w-8 h-8 flex items-center justify-center rounded bg-primary text-white font-medium text-xs flex items-center justify-center">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center rounded text-on-surface hover:bg-surface-container-low transition-colors text-xs font-medium">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Tombol Selanjutnya --}}
                    @if ($pasiens->hasMorePages())
                        <a href="{{ $pasiens->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded text-on-surface hover:bg-surface-container-low transition-colors">
                            <span class="material-symbols-outlined text-sm">chevron_right</span>
                        </a>
                    @else
                        <button type="button" class="w-8 h-8 flex items-center justify-center rounded text-on-surface-variant/40 cursor-not-allowed" disabled>
                            <span class="material-symbols-outlined text-sm">chevron_right</span>
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function filterPasienTable() {
    const input = document.getElementById('searchPasienInput');
    const filter = input.value.toLowerCase();
    const tbody = document.getElementById('pasienTableBody');
    const rows = tbody.getElementsByTagName('tr');

    for (let i = 0; i < rows.length; i++) {
        // Jangan sembunyikan jika hanya baris kosong (empty state)
        if (rows[i].cells.length <= 1) continue;

        const text = rows[i].textContent || rows[i].innerText;
        if (text.toLowerCase().indexOf(filter) > -1) {
            rows[i].style.display = "";
        } else {
            rows[i].style.display = "none";
        }
    }
}
</script>
@endsection