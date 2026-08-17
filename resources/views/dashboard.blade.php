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
                    <!-- Card 1: Pasien Hari Ini -->
                    <div class="bg-white rounded-xl border border-outline-variant p-4 card-shadow flex flex-col gap-2">
                        <div class="flex items-center justify-between text-on-surface-variant">
                            <span class="text-xs font-medium">Pasien Hari Ini</span>
                            <span class="material-symbols-outlined text-primary">group</span>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-2xl font-bold text-on-surface">{{ $pasienHariIni }}</span>
                            <span class="text-xs text-emerald-600 font-semibold">kunjungan</span>
                        </div>
                    </div>

                    <!-- Card 2: Menunggu -->
                    <div class="bg-white rounded-xl border border-outline-variant p-4 card-shadow flex flex-col gap-2">
                        <div class="flex items-center justify-between text-on-surface-variant">
                            <span class="text-xs font-medium">Menunggu</span>
                            <span class="material-symbols-outlined text-secondary">pending_actions</span>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-2xl font-bold text-on-surface">{{ $antreanMenunggu }}</span>
                            <span class="text-xs text-on-surface-variant">antrean</span>
                        </div>
                    </div>

                    <!-- Card 3: Sudah Diperiksa -->
                    <div class="bg-white rounded-xl border border-outline-variant p-4 card-shadow flex flex-col gap-2">
                        <div class="flex items-center justify-between text-on-surface-variant">
                            <span class="text-xs font-medium">Sudah Diperiksa</span>
                            <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-2xl font-bold text-on-surface">{{ $sudahDiperiksa }}</span>
                            <span class="text-xs text-on-surface-variant">selesai</span>
                        </div>
                    </div>

                    <!-- Card 4: Total Pasien Terdaftar -->
                    <div class="bg-white rounded-xl border border-outline-variant p-4 card-shadow flex flex-col gap-2">
                        <div class="flex items-center justify-between text-on-surface-variant">
                            <span class="text-xs font-medium">Total Pasien</span>
                            <span class="material-symbols-outlined text-primary">groups</span>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-2xl font-bold text-on-surface">{{ $totalPasien }}</span>
                            <span class="text-xs text-on-surface-variant">terdaftar</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 2: Antrean Pasien Hari Ini -->
            <section class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
                <div class="p-6 border-b border-outline-variant flex justify-between items-center bg-white">
                    <div>
                        <h3 class="text-xl font-semibold text-on-surface">Antrean Pasien Hari Ini</h3>
                        <p class="text-xs text-on-surface-variant mt-0.5">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                    </div>
                    <a href="{{ route('pendaftaran') }}" class="bg-primary text-white font-semibold text-sm px-4 py-2 rounded-lg hover:bg-[#005a3c] transition-colors flex items-center gap-2 shadow-sm">
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
                                <th class="py-3 px-6 font-semibold">Jam Daftar</th>
                                <th class="py-3 px-6 font-semibold">Status</th>
                                <th class="py-3 px-6 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-on-surface divide-y divide-outline-variant">
                            @forelse ($antreanHariIni as $antrean)
                                <tr class="hover:bg-surface-container-low transition-colors">
                                    <td class="py-4 px-6 font-bold text-primary">
                                        {{ sprintf('%02d', $antrean->no_antrean) }}
                                    </td>
                                    <td class="py-4 px-6 font-semibold">
                                        {{ $antrean->nama_lengkap }}
                                        <span class="block text-xs text-on-surface-variant font-normal">NIK: {{ $antrean->nik }}</span>
                                    </td>
                                    <td class="py-4 px-6 text-on-surface-variant">
                                        {{ \Carbon\Carbon::parse($antrean->tgl_daftar)->format('H:i') }} WIB
                                    </td>
                                    <td class="py-4 px-6">
                                        @if ($antrean->status_kunjungan === 'Menunggu')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                Menunggu
                                            </span>
                                        @elseif ($antrean->status_kunjungan === 'Sedang Diperiksa')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                                Sedang Diperiksa
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                                {{ $antrean->status_kunjungan ?: 'Selesai' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <a href="{{ route('rekam-medis') }}?id={{ $antrean->id_pasien }}" class="text-primary hover:underline font-semibold text-sm inline-flex items-center gap-1">
                                            Lanjutkan
                                            <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 px-6 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-14 h-14 rounded-full bg-[#E5F5F0] flex items-center justify-center text-primary mb-3">
                                                <span class="material-symbols-outlined text-3xl">event_available</span>
                                            </div>
                                            <h4 class="text-base font-semibold text-on-surface mb-1">Belum Ada Antrean Pasien Hari Ini</h4>
                                            <p class="text-sm text-on-surface-variant max-w-sm mb-4">
                                                Daftarkan pasien baru atau lama untuk memulai antrean pemeriksaan klinik.
                                            </p>
                                            <a href="{{ route('pendaftaran') }}" class="bg-primary text-white font-semibold text-sm px-5 py-2.5 rounded-lg hover:bg-[#005a3c] transition-colors inline-flex items-center gap-2 shadow-sm">
                                                <span class="material-symbols-outlined text-sm">add</span>
                                                Daftarkan Pasien Sekarang
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
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
                    <a href="{{ route('pendaftaran') }}" class="w-full bg-primary text-white text-sm font-semibold py-3 rounded-lg hover:bg-[#005a3c] transition-colors flex items-center justify-center gap-2 shadow-sm">
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
                <div class="flex items-center justify-between border-b border-outline-variant pb-3 mb-4">
                    <h3 class="text-base font-bold text-on-surface">Pasien Terbaru</h3>
                    <a href="{{ route('pasien') }}" class="text-xs font-semibold text-primary hover:underline">
                        Lihat Semua
                    </a>
                </div>
                <div class="flex flex-col gap-4">
                    @forelse ($pasienTerbaru as $index => $pasien)
                        @php
                            $words = explode(' ', trim($pasien->nama_lengkap));
                            $initials = '';
                            if (count($words) >= 2) {
                                $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                            } else {
                                $initials = strtoupper(substr($pasien->nama_lengkap, 0, 2));
                            }
                            $bgColors = [
                                'bg-primary-container text-on-primary-container',
                                'bg-secondary-container text-white',
                                'bg-[#F3E8FF] text-[#9333EA]',
                                'bg-[#E5F5F0] text-primary',
                                'bg-amber-100 text-amber-800'
                            ];
                            $avatarColor = $bgColors[$index % count($bgColors)];
                        @endphp
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full {{ $avatarColor }} flex items-center justify-center font-bold text-sm flex-shrink-0">
                                {{ $initials }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('rekam-medis') }}?id={{ $pasien->id_pasien }}" class="text-sm font-semibold text-on-surface hover:text-primary transition-colors block truncate">
                                    {{ $pasien->nama_lengkap }}
                                </a>
                                <p class="text-xs text-on-surface-variant truncate">NIK: {{ $pasien->nik }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                @if ($pasien->created_at)
                                    <span class="text-xs text-on-surface-variant">
                                        @if (\Carbon\Carbon::parse($pasien->created_at)->isToday())
                                            Hari ini
                                        @elseif (\Carbon\Carbon::parse($pasien->created_at)->isYesterday())
                                            Kemarin
                                        @else
                                            {{ \Carbon\Carbon::parse($pasien->created_at)->format('d M') }}
                                        @endif
                                    </span>
                                @else
                                    <span class="text-xs text-on-surface-variant">Terdaftar</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-6 text-center">
                            <p class="text-xs text-on-surface-variant">Belum ada data pasien terdaftar.</p>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</div>
@endsection