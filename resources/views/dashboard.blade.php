@extends('layouts.app')

@section('title', 'Dashboard - Mandalacare')

@section('content')
<div class="p-6 md:p-8 lg:p-10 w-full max-w-[1440px] mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-on-surface">Dashboard</h1>
        <p class="text-base text-on-surface-variant mt-1">Berikut ringkasan aktivitas dan antrean klinik hari ini.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Left Column (Main Content) -->
        <div class="lg:col-span-8 flex flex-col gap-6">
            <!-- Section 1: Ringkasan Hari Ini -->
            <section>
                <h3 class="text-xl font-semibold text-on-surface mb-4">Ringkasan Hari Ini</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <!-- Card 1 -->
                    <div class="bg-white rounded-xl border border-outline-variant p-4 card-shadow flex flex-col gap-2">
                        <div class="flex items-center justify-between text-on-surface-variant">
                            <span class="text-xs font-medium">Pasien Hari Ini</span>
                            <span class="material-symbols-outlined text-primary">group</span>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-2xl font-bold text-on-surface">12</span>
                            <span class="text-xs text-emerald-600 font-semibold">+3 hari ini</span>
                        </div>
                    </div>
                    <!-- Card 2 -->
                    <div class="bg-white rounded-xl border border-outline-variant p-4 card-shadow flex flex-col gap-2">
                        <div class="flex items-center justify-between text-on-surface-variant">
                            <span class="text-xs font-medium">Menunggu</span>
                            <span class="material-symbols-outlined text-secondary">pending_actions</span>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-2xl font-bold text-on-surface">3</span>
                        </div>
                    </div>
                    <!-- Card 3 -->
                    <div class="bg-white rounded-xl border border-outline-variant p-4 card-shadow flex flex-col gap-2">
                        <div class="flex items-center justify-between text-on-surface-variant">
                            <span class="text-xs font-medium">Sudah Diperiksa</span>
                            <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-2xl font-bold text-on-surface">9</span>
                        </div>
                    </div>
                    <!-- Card 4 -->
                    <div class="bg-white rounded-xl border border-outline-variant p-4 card-shadow flex flex-col gap-2">
                        <div class="flex items-center justify-between text-on-surface-variant">
                            <span class="text-xs font-medium">Pendapatan</span>
                            <span class="material-symbols-outlined text-primary">payments</span>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-xl font-bold text-on-surface">Rp 1.250k</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 2: Antrean Pasien -->
            <section class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
                <div class="p-6 border-b border-outline-variant flex justify-between items-center bg-white">
                    <h3 class="text-xl font-semibold text-on-surface">Antrean Pasien Hari Ini</h3>
                    <a href="{{ route('pendaftaran') }}" class="bg-primary text-white font-semibold text-sm px-4 py-2 rounded-lg hover:bg-[#005a3c] transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">add</span>
                        Daftarkan Pasien
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-[#F8FAFC] text-xs text-on-surface-variant border-b border-outline-variant">
                            <tr>
                                <th class="py-3 px-6 font-semibold">No. Antrean</th>
                                <th class="py-3 px-6 font-semibold">Nama Pasien</th>
                                <th class="py-3 px-6 font-semibold">Jam</th>
                                <th class="py-3 px-6 font-semibold">Status</th>
                                <th class="py-3 px-6 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-on-surface divide-y divide-outline-variant">
                            <tr class="hover:bg-surface-container-low transition-colors">
                                <td class="py-4 px-6 font-medium">01</td>
                                <td class="py-4 px-6 font-semibold">Andi Pratama</td>
                                <td class="py-4 px-6">09:00</td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Menunggu</span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <a href="{{ route('rekam-medis') }}" class="text-primary hover:underline font-semibold text-sm">Lanjutkan</a>
                                </td>
                            </tr>
                            <tr class="hover:bg-surface-container-low transition-colors">
                                <td class="py-4 px-6 font-medium">02</td>
                                <td class="py-4 px-6 font-semibold">Siti Aisyah</td>
                                <td class="py-4 px-6">09:30</td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Sedang Diperiksa</span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <a href="{{ route('rekam-medis') }}" class="text-primary hover:underline font-semibold text-sm">Lanjutkan</a>
                                </td>
                            </tr>
                            <tr class="hover:bg-surface-container-low transition-colors">
                                <td class="py-4 px-6 font-medium text-on-surface-variant">03</td>
                                <td class="py-4 px-6 text-on-surface-variant font-semibold">Budi Santoso</td>
                                <td class="py-4 px-6 text-on-surface-variant">10:00</td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">Selesai</span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <a href="{{ route('rekam-medis') }}" class="text-on-surface-variant hover:text-primary font-semibold text-sm">Lihat</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <!-- Right Column (Sidebar Elements) -->
        <div class="lg:col-span-4 flex flex-col gap-6">
            <!-- Akses Cepat -->
            <section class="bg-white rounded-xl border border-outline-variant card-shadow p-6">
                <h3 class="text-base font-bold text-on-surface mb-4">Akses Cepat</h3>
                <div class="flex flex-col gap-3">
                    <a href="{{ route('pendaftaran') }}" class="w-full bg-primary text-white text-sm font-semibold py-3 rounded-lg hover:bg-[#005a3c] transition-colors flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">person_add</span>
                        Daftarkan Pasien Baru
                    </a>
                    <a href="{{ route('pasien') }}" class="w-full bg-transparent border border-secondary text-secondary text-sm font-semibold py-3 rounded-lg hover:bg-secondary/5 transition-colors flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">search</span>
                        Cari Pasien
                    </a>
                    <a href="{{ route('rekam-medis') }}" class="w-full bg-transparent border border-outline-variant text-on-surface-variant text-sm font-semibold py-3 rounded-lg hover:bg-surface-container-low transition-colors flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">folder_open</span>
                        Buka Rekam Medis
                    </a>
                </div>
            </section>

            <!-- Pasien Terbaru -->
            <section class="bg-white rounded-xl border border-outline-variant card-shadow p-6">
                <h3 class="text-base font-bold text-on-surface mb-4 border-b border-outline-variant pb-2">Pasien Terbaru</h3>
                <div class="flex flex-col gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-bold text-sm">
                            AP
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-on-surface">Andi Pratama</p>
                            <p class="text-xs text-on-surface-variant">NIK: 3271...890</p>
                        </div>
                        <span class="text-xs text-on-surface-variant">Hari ini</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-secondary-container text-white flex items-center justify-center font-bold text-sm">
                            SA
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-on-surface">Siti Aisyah</p>
                            <p class="text-xs text-on-surface-variant">NIK: 3271...123</p>
                        </div>
                        <span class="text-xs text-on-surface-variant">Kemarin</span>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection