@extends('layouts.app')

@section('title', 'Dashboard - Mandalacare')

@section('content')

<div class="min-h-screen p-6 md:p-8 lg:p-10">

    <!-- HEADER -->
    <div class="mb-8">

        <p class="text-sm text-primary font-semibold mb-2">
            Clinical Management
        </p>

        <h1 class="text-3xl md:text-4xl font-semibold tracking-tight">
            Dashboard
        </h1>

        <p class="text-on-surface-variant mt-2">
            Ringkasan aktivitas dan informasi klinis Mandalacare.
        </p>

    </div>


    <!-- STAT CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

        <!-- TOTAL PASIEN -->
        <div class="bg-white rounded-xl border border-outline-variant p-6 card-shadow">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-on-surface-variant font-medium">
                        Total Pasien
                    </p>

                    <p class="text-3xl font-semibold mt-3 text-on-surface">
                        —
                    </p>

                    <p class="text-xs text-on-surface-variant mt-2">
                        Belum ada data
                    </p>
                </div>

                <div class="w-12 h-12 rounded-xl bg-primary-container/20 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined">
                        group
                    </span>
                </div>

            </div>

        </div>


        <!-- PENDAFTARAN -->
        <div class="bg-white rounded-xl border border-outline-variant p-6 card-shadow">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-on-surface-variant font-medium">
                        Pendaftaran
                    </p>

                    <p class="text-3xl font-semibold mt-3">
                        —
                    </p>

                    <p class="text-xs text-on-surface-variant mt-2">
                        Belum ada data
                    </p>
                </div>

                <div class="w-12 h-12 rounded-xl bg-secondary-container/20 flex items-center justify-center text-secondary">
                    <span class="material-symbols-outlined">
                        person_add
                    </span>
                </div>

            </div>

        </div>


        <!-- REKAM MEDIS -->
        <div class="bg-white rounded-xl border border-outline-variant p-6 card-shadow">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-on-surface-variant font-medium">
                        Rekam Medis
                    </p>

                    <p class="text-3xl font-semibold mt-3">
                        —
                    </p>

                    <p class="text-xs text-on-surface-variant mt-2">
                        Belum ada data
                    </p>
                </div>

                <div class="w-12 h-12 rounded-xl bg-primary-container/20 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined">
                        medical_information
                    </span>
                </div>

            </div>

        </div>


        <!-- EVALUASI -->
        <div class="bg-white rounded-xl border border-outline-variant p-6 card-shadow">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-on-surface-variant font-medium">
                        Evaluasi
                    </p>

                    <p class="text-3xl font-semibold mt-3">
                        —
                    </p>

                    <p class="text-xs text-on-surface-variant mt-2">
                        Belum ada data
                    </p>
                </div>

                <div class="w-12 h-12 rounded-xl bg-secondary-container/20 flex items-center justify-center text-secondary">
                    <span class="material-symbols-outlined">
                        assignment_turned_in
                    </span>
                </div>

            </div>

        </div>

    </div>


    <!-- CONTENT GRID -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <!-- AKTIVITAS -->
        <section class="xl:col-span-2 bg-white rounded-xl border border-outline-variant card-shadow">

            <div class="p-6 border-b border-outline-variant">

                <div class="flex items-center justify-between">

                    <div>
                        <h2 class="text-lg font-semibold">
                            Aktivitas Terbaru
                        </h2>

                        <p class="text-sm text-on-surface-variant mt-1">
                            Aktivitas klinis terbaru akan muncul di sini.
                        </p>
                    </div>

                    <span class="material-symbols-outlined text-on-surface-variant">
                        history
                    </span>

                </div>

            </div>


            <!-- EMPTY STATE -->
            <div class="p-12 flex flex-col items-center justify-center text-center">

                <div class="w-16 h-16 rounded-full bg-surface-container-low flex items-center justify-center text-on-surface-variant mb-4">

                    <span class="material-symbols-outlined text-3xl">
                        inbox
                    </span>

                </div>

                <h3 class="font-semibold text-on-surface">
                    Belum ada aktivitas
                </h3>

                <p class="text-sm text-on-surface-variant mt-2 max-w-md">
                    Data aktivitas klinis akan ditampilkan setelah terdapat aktivitas pada sistem.
                </p>

            </div>

        </section>


        <!-- PASIEN TERBARU -->
        <section class="bg-white rounded-xl border border-outline-variant card-shadow">

            <div class="p-6 border-b border-outline-variant">

                <h2 class="text-lg font-semibold">
                    Pasien Terbaru
                </h2>

                <p class="text-sm text-on-surface-variant mt-1">
                    Pasien yang baru terdaftar.
                </p>

            </div>


            <!-- EMPTY STATE -->
            <div class="p-8 flex flex-col items-center justify-center text-center min-h-[280px]">

                <div class="w-14 h-14 rounded-full bg-surface-container-low flex items-center justify-center text-on-surface-variant mb-4">

                    <span class="material-symbols-outlined text-2xl">
                        person_search
                    </span>

                </div>

                <h3 class="font-semibold">
                    Belum ada pasien
                </h3>

                <p class="text-sm text-on-surface-variant mt-2">
                    Data pasien akan muncul setelah pendaftaran dilakukan.
                </p>

            </div>

        </section>

    </div>


    <!-- QUICK ACTION -->
    <section class="mt-6 bg-white rounded-xl border border-outline-variant card-shadow p-6">

        <div class="flex items-center gap-4">

            <div class="w-12 h-12 rounded-xl bg-primary-container/20 text-primary flex items-center justify-center">

                <span class="material-symbols-outlined">
                    info
                </span>

            </div>

            <div>

                <h3 class="font-semibold">
                    Sistem siap digunakan
                </h3>

                <p class="text-sm text-on-surface-variant mt-1">
                    Silakan mulai dengan melakukan pendaftaran pasien.
                </p>

            </div>

        </div>

    </section>

</div>

@endsection