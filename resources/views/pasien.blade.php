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
        <!-- Card 1 -->
        <div class="bg-white border border-outline-variant rounded-xl p-6 card-shadow flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-[#E5F5F0] flex items-center justify-center text-primary">
                <span class="material-symbols-outlined text-2xl">group</span>
            </div>
            <div>
                <p class="text-sm text-on-surface-variant mb-1">Total Pasien</p>
                <h3 class="text-2xl text-on-surface font-semibold">248</h3>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white border border-outline-variant rounded-xl p-6 card-shadow flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-[#E8F0FE] flex items-center justify-center text-[#1A73E8]">
                <span class="material-symbols-outlined text-2xl">person_add</span>
            </div>
            <div>
                <p class="text-sm text-on-surface-variant mb-1">Pasien Baru</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-2xl text-on-surface font-semibold">12</h3>
                    <span class="text-xs text-on-surface-variant">Bulan ini</span>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white border border-outline-variant rounded-xl p-6 card-shadow flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-[#F3E8FF] flex items-center justify-center text-[#9333EA]">
                <span class="material-symbols-outlined text-2xl">today</span>
            </div>
            <div>
                <p class="text-sm text-on-surface-variant mb-1">Kunjungan Hari Ini</p>
                <h3 class="text-2xl text-on-surface font-semibold">12</h3>
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
            <div class="flex flex-1 max-w-2xl gap-3 w-full">
                <div class="relative flex-1">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                    <input id="searchPasienInput" onkeyup="filterPasienTable()" class="w-full pl-12 pr-4 py-2.5 rounded-lg border border-outline-variant focus:border-primary focus:ring-3 focus:ring-primary/20 outline-none transition-all text-sm text-on-surface bg-white" placeholder="Cari berdasarkan nama, NIK, atau nomor telepon..." type="text">
                </div>
                <button type="button" class="flex items-center gap-2 px-4 py-2.5 rounded-lg border border-outline-variant text-on-surface hover:bg-surface-container-low transition-colors text-sm font-semibold bg-white">
                    <span class="material-symbols-outlined text-sm">tune</span>
                    Filter
                </button>
            </div>
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
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="py-4 px-6 text-sm text-on-surface-variant">01</td>
                        <td class="py-4 px-6 text-sm font-semibold">Andi Pratama</td>
                        <td class="py-4 px-6 text-sm">3271••••890</td>
                        <td class="py-4 px-6 text-sm">12 Januari 2005</td>
                        <td class="py-4 px-6 text-sm">Laki-laki</td>
                        <td class="py-4 px-6 text-sm">0812••••3456</td>
                        <td class="py-4 px-6 text-sm">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-[#E5F5F0] text-primary font-medium text-xs">Hari ini</span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <a href="{{ route('rekam-medis') }}" class="inline-flex items-center justify-center text-[#1A73E8] hover:text-[#1557B0] transition-colors p-1.5 rounded-lg hover:bg-[#E8F0FE]" title="Lihat Rekam Medis">
                                <span class="material-symbols-outlined">chevron_right</span>
                            </a>
                        </td>
                    </tr>
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="py-4 px-6 text-sm text-on-surface-variant">02</td>
                        <td class="py-4 px-6 text-sm font-semibold">Siti Aisyah</td>
                        <td class="py-4 px-6 text-sm">3271••••123</td>
                        <td class="py-4 px-6 text-sm">4 Mei 2004</td>
                        <td class="py-4 px-6 text-sm">Perempuan</td>
                        <td class="py-4 px-6 text-sm">0857••••7890</td>
                        <td class="py-4 px-6 text-sm">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-[#E5F5F0] text-primary font-medium text-xs">Hari ini</span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <a href="{{ route('rekam-medis') }}" class="inline-flex items-center justify-center text-[#1A73E8] hover:text-[#1557B0] transition-colors p-1.5 rounded-lg hover:bg-[#E8F0FE]" title="Lihat Rekam Medis">
                                <span class="material-symbols-outlined">chevron_right</span>
                            </a>
                        </td>
                    </tr>
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="py-4 px-6 text-sm text-on-surface-variant">03</td>
                        <td class="py-4 px-6 text-sm font-semibold">Budi Santoso</td>
                        <td class="py-4 px-6 text-sm">3271••••456</td>
                        <td class="py-4 px-6 text-sm">19 September 2003</td>
                        <td class="py-4 px-6 text-sm">Laki-laki</td>
                        <td class="py-4 px-6 text-sm">0813••••1122</td>
                        <td class="py-4 px-6 text-sm text-on-surface-variant">Kemarin</td>
                        <td class="py-4 px-6 text-right">
                            <a href="{{ route('rekam-medis') }}" class="inline-flex items-center justify-center text-[#1A73E8] hover:text-[#1557B0] transition-colors p-1.5 rounded-lg hover:bg-[#E8F0FE]" title="Lihat Rekam Medis">
                                <span class="material-symbols-outlined">chevron_right</span>
                            </a>
                        </td>
                    </tr>
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="py-4 px-6 text-sm text-on-surface-variant">04</td>
                        <td class="py-4 px-6 text-sm font-semibold">Rina Maharani</td>
                        <td class="py-4 px-6 text-sm">3271••••567</td>
                        <td class="py-4 px-6 text-sm">22 Februari 2002</td>
                        <td class="py-4 px-6 text-sm">Perempuan</td>
                        <td class="py-4 px-6 text-sm">0821••••3344</td>
                        <td class="py-4 px-6 text-sm text-on-surface-variant">5 Agustus 2026</td>
                        <td class="py-4 px-6 text-right">
                            <a href="{{ route('rekam-medis') }}" class="inline-flex items-center justify-center text-[#1A73E8] hover:text-[#1557B0] transition-colors p-1.5 rounded-lg hover:bg-[#E8F0FE]" title="Lihat Rekam Medis">
                                <span class="material-symbols-outlined">chevron_right</span>
                            </a>
                        </td>
                    </tr>
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="py-4 px-6 text-sm text-on-surface-variant">05</td>
                        <td class="py-4 px-6 text-sm font-semibold">Dimas Saputra</td>
                        <td class="py-4 px-6 text-sm">3271••••789</td>
                        <td class="py-4 px-6 text-sm">10 November 2001</td>
                        <td class="py-4 px-6 text-sm">Laki-laki</td>
                        <td class="py-4 px-6 text-sm">0838••••5566</td>
                        <td class="py-4 px-6 text-sm text-on-surface-variant">4 Agustus 2026</td>
                        <td class="py-4 px-6 text-right">
                            <a href="{{ route('rekam-medis') }}" class="inline-flex items-center justify-center text-[#1A73E8] hover:text-[#1557B0] transition-colors p-1.5 rounded-lg hover:bg-[#E8F0FE]" title="Lihat Rekam Medis">
                                <span class="material-symbols-outlined">chevron_right</span>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-outline-variant flex flex-col sm:flex-row items-center justify-between gap-4">
            <span class="text-sm text-on-surface-variant">Menampilkan 1–5 dari 248 pasien</span>
            <div class="flex items-center gap-1">
                <button type="button" class="w-8 h-8 flex items-center justify-center rounded text-on-surface-variant hover:bg-surface-container-low transition-colors disabled:opacity-50" disabled>
                    <span class="material-symbols-outlined text-sm">chevron_left</span>
                </button>
                <button type="button" class="w-8 h-8 flex items-center justify-center rounded bg-primary text-white font-medium text-xs">1</button>
                <button type="button" class="w-8 h-8 flex items-center justify-center rounded text-on-surface hover:bg-surface-container-low transition-colors text-xs font-medium">2</button>
                <button type="button" class="w-8 h-8 flex items-center justify-center rounded text-on-surface hover:bg-surface-container-low transition-colors text-xs font-medium">3</button>
                <button type="button" class="w-8 h-8 flex items-center justify-center rounded text-on-surface hover:bg-surface-container-low transition-colors text-xs font-medium">4</button>
                <button type="button" class="w-8 h-8 flex items-center justify-center rounded text-on-surface hover:bg-surface-container-low transition-colors text-xs font-medium">5</button>
                <button type="button" class="w-8 h-8 flex items-center justify-center rounded text-on-surface hover:bg-surface-container-low transition-colors">
                    <span class="material-symbols-outlined text-sm">chevron_right</span>
                </button>
            </div>
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