@extends('layouts.app')

@section('title', 'Mandalacare - Dashboard')

@section('content')
<div class="max-w-[1440px] mx-auto grid grid-cols-1 lg:grid-cols-12 gap-gutter">
<!-- Left Column (Main Content) -->
<div class="lg:col-span-8 flex flex-col gap-stack-lg">
<!-- Section 1: Ringkasan Hari Ini -->
<section>
<h3 class="font-headline-md text-headline-md text-on-surface mb-stack-md">Ringkasan Hari Ini</h3>
<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
<!-- Card 1 -->
<div class="bg-surface rounded-xl border border-outline-variant p-4 shadow-[0px_4px_20px_rgba(0,0,0,0.04)] flex flex-col gap-2">
<div class="flex items-center justify-between text-on-surface-variant">
<span class="font-label-sm text-label-sm">Pasien Hari Ini</span>
<span class="material-symbols-outlined text-primary">group</span>
</div>
<div class="flex items-baseline gap-2">
<span class="font-headline-lg text-headline-lg text-on-surface">12</span>
<span class="font-label-sm text-label-sm text-primary-container">+3 dari kemarin</span>
</div>
</div>
<!-- Card 2 -->
<div class="bg-surface rounded-xl border border-outline-variant p-4 shadow-[0px_4px_20px_rgba(0,0,0,0.04)] flex flex-col gap-2">
<div class="flex items-center justify-between text-on-surface-variant">
<span class="font-label-sm text-label-sm">Menunggu</span>
<span class="material-symbols-outlined text-secondary">pending_actions</span>
</div>
<div class="flex items-baseline gap-2">
<span class="font-headline-lg text-headline-lg text-on-surface">3</span>
</div>
</div>
<!-- Card 3 -->
<div class="bg-surface rounded-xl border border-outline-variant p-4 shadow-[0px_4px_20px_rgba(0,0,0,0.04)] flex flex-col gap-2">
<div class="flex items-center justify-between text-on-surface-variant">
<span class="font-label-sm text-label-sm">Sudah Diperiksa</span>
<span class="material-symbols-outlined text-primary-container">check_circle</span>
</div>
<div class="flex items-baseline gap-2">
<span class="font-headline-lg text-headline-lg text-on-surface">9</span>
</div>
</div>
<!-- Card 4 -->
<div class="bg-surface rounded-xl border border-outline-variant p-4 shadow-[0px_4px_20px_rgba(0,0,0,0.04)] flex flex-col gap-2">
<div class="flex items-center justify-between text-on-surface-variant">
<span class="font-label-sm text-label-sm">Pendapatan</span>
<span class="material-symbols-outlined text-primary">payments</span>
</div>
<div class="flex items-baseline gap-2">
<span class="font-headline-md text-headline-md text-on-surface">Rp 1.250k</span>
</div>
</div>
</div>
</section>
<!-- Section 2: Antrean Pasien -->
<section class="bg-surface rounded-xl border border-outline-variant shadow-[0px_4px_20px_rgba(0,0,0,0.04)] overflow-hidden">
<div class="p-6 border-b border-outline-variant flex justify-between items-center bg-surface">
<h3 class="font-headline-md text-headline-md text-on-surface">Antrean Pasien Hari Ini</h3>
<a href="{{ route('pendaftaran') }}" class="bg-primary text-on-primary font-label-md text-label-md px-4 py-2 rounded-lg hover:bg-primary/90 transition-colors flex items-center gap-2">
<span class="material-symbols-outlined text-sm">add</span>
                                Daftarkan Pasien
                            </a>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead class="bg-[#F1F5F9] font-label-sm text-label-sm text-on-surface-variant border-b border-outline-variant">
<tr>
<th class="py-3 px-6 font-semibold">No. Antrean</th>
<th class="py-3 px-6 font-semibold">Nama Pasien</th>
<th class="py-3 px-6 font-semibold">Jam</th>
<th class="py-3 px-6 font-semibold">Status</th>
<th class="py-3 px-6 font-semibold text-right">Aksi</th>
</tr>
</thead>
<tbody class="font-body-md text-body-md text-on-surface divide-y divide-outline-variant">
<tr class="hover:bg-surface-container-low transition-colors">
<td class="py-4 px-6 font-medium">01</td>
<td class="py-4 px-6">Andi Pratama</td>
<td class="py-4 px-6">09:00</td>
<td class="py-4 px-6">
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-secondary-fixed text-on-secondary-fixed">Menunggu</span>
</td>
<td class="py-4 px-6 text-right">
<button class="text-primary hover:text-primary/80 font-label-sm text-label-sm font-semibold">Lanjutkan</button>
</td>
</tr>
<tr class="hover:bg-surface-container-low transition-colors">
<td class="py-4 px-6 font-medium">02</td>
<td class="py-4 px-6">Siti Aisyah</td>
<td class="py-4 px-6">09:30</td>
<td class="py-4 px-6">
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-fixed text-on-primary-fixed">Sedang Diperiksa</span>
</td>
<td class="py-4 px-6 text-right">
<button class="text-primary hover:text-primary/80 font-label-sm text-label-sm font-semibold">Lanjutkan</button>
</td>
</tr>
<tr class="hover:bg-surface-container-low transition-colors">
<td class="py-4 px-6 font-medium text-on-surface-variant">03</td>
<td class="py-4 px-6 text-on-surface-variant">Budi Santoso</td>
<td class="py-4 px-6 text-on-surface-variant">10:00</td>
<td class="py-4 px-6">
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-surface-container-high text-on-surface-variant">Selesai</span>
</td>
<td class="py-4 px-6 text-right">
<button class="text-on-surface-variant hover:text-primary font-label-sm text-label-sm font-semibold">Lihat</button>
</td>
</tr>
</tbody>
</table>
</div>
</section>
</div>
<!-- Right Column (Sidebar Elements) -->
<div class="lg:col-span-4 flex flex-col gap-stack-lg">
<!-- Akses Cepat -->
<section class="bg-surface rounded-xl border border-outline-variant shadow-[0px_4px_20px_rgba(0,0,0,0.04)] p-6">
<h3 class="font-label-md text-label-md font-bold text-on-surface mb-4">Akses Cepat</h3>
<div class="flex flex-col gap-3">
<a href="{{ route('pendaftaran') }}" class="w-full bg-primary text-on-primary font-label-md text-label-md py-3 rounded-lg hover:bg-primary/90 transition-colors flex items-center justify-center gap-2">
<span class="material-symbols-outlined">person_add</span>
                                Daftarkan Pasien Baru
                            </a>
<button class="w-full bg-transparent border border-secondary text-secondary font-label-md text-label-md py-3 rounded-lg hover:bg-secondary-fixed/50 transition-colors flex items-center justify-center gap-2">
<span class="material-symbols-outlined">search</span>
                                Cari Pasien
                            </button>
<button class="w-full bg-transparent border border-outline-variant text-on-surface-variant font-label-md text-label-md py-3 rounded-lg hover:bg-surface-container-low transition-colors flex items-center justify-center gap-2">
<span class="material-symbols-outlined">folder_open</span>
                                Buka Rekam Medis
                            </button>
</div>
</section>
<!-- Pasien Terbaru -->
<section class="bg-surface rounded-xl border border-outline-variant shadow-[0px_4px_20px_rgba(0,0,0,0.04)] p-6">
<h3 class="font-label-md text-label-md font-bold text-on-surface mb-4 border-b border-outline-variant pb-2">Pasien Terbaru</h3>
<div class="flex flex-col gap-4">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-bold text-sm">
                                    AP
                                </div>
<div class="flex-1">
<p class="font-label-md text-label-md text-on-surface">Andi Pratama</p>
<p class="font-label-sm text-label-sm text-on-surface-variant">NIK: 3271...890</p>
</div>
<span class="font-label-sm text-label-sm text-on-surface-variant">Hari ini</span>
</div>
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-full bg-secondary-container text-on-secondary-container flex items-center justify-center font-bold text-sm">
                                    SA
                                </div>
<div class="flex-1">
<p class="font-label-md text-label-md text-on-surface">Siti Aisyah</p>
<p class="font-label-sm text-label-sm text-on-surface-variant">NIK: 3271...123</p>
</div>
<span class="font-label-sm text-label-sm text-on-surface-variant">Kemarin</span>
</div>
</div>
</section>
</div>
</div>
@endsection