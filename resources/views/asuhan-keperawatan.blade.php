@extends('layouts.app')

@section('content')

<style>
    .card-shadow {
        box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.04);
    }

    .input-ring:focus {
        outline: none;
        border-color: #006c49;
        box-shadow: 0 0 0 3px rgba(0, 108, 73, 0.2);
    }
</style>

<div class="p-4 sm:p-6 md:p-8 lg:p-10 w-full max-w-7xl mx-auto flex-1 flex flex-col gap-6">

    <!-- Page Header -->
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-on-surface tracking-tight">
            Asuhan Keperawatan
        </h1>

        <p class="text-sm sm:text-base text-on-surface-variant mt-1">
            Kelola rencana asuhan keperawatan pasien secara terstruktur.
        </p>
    </div>


    <!-- Main Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <!-- LEFT COLUMN -->
        <div class="xl:col-span-2 flex flex-col gap-8">

            <!-- ===================================== -->
            <!-- 1. PILIH PASIEN -->
            <!-- ===================================== -->

            <section class="bg-white rounded-xl border border-[#bbcabf]/30 p-6 card-shadow">

                <h3 class="text-2xl font-semibold text-[#131b2e] mb-4">
                    1. Pilih Pasien
                </h3>

                <div class="relative mb-4">

                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#3c4a42]">
                        search
                    </span>

                    <input
                        type="text"
                        value="Andi Pratama"
                        placeholder="Cari nama pasien, NIK, atau nomor telepon..."
                        class="w-full bg-[#faf8ff] border border-[#6c7a71] rounded-xl py-3 pl-10 pr-4 text-base text-[#131b2e] input-ring"
                    >

                </div>


                <!-- Search Result -->
                <div class="flex items-center justify-between p-4 bg-[#f2f3ff] rounded-lg border border-[#006c49]/20">

                    <div class="flex items-center gap-4">

                        <div class="w-12 h-12 bg-[#10b981]/20 rounded-full flex items-center justify-center text-[#006c49] font-bold text-lg">
                            AP
                        </div>

                        <div>
                            <p class="font-semibold text-[#131b2e]">
                                Andi Pratama
                            </p>

                            <p class="text-sm text-[#3c4a42]">
                                NIK: 3271••••890 • RM: 002-145
                            </p>
                        </div>

                    </div>

                    <button
                        type="button"
                        class="bg-[#006c49] text-white px-4 py-2 rounded-lg font-semibold hover:bg-[#005236] transition-colors">
                        Terpilih
                    </button>

                </div>

            </section>


            <!-- ===================================== -->
            <!-- 2. DATA PASIEN -->
            <!-- ===================================== -->

            <section class="bg-white rounded-xl border border-[#bbcabf]/30 p-6 card-shadow">

                <div class="flex items-center justify-between mb-6 pb-4 border-b border-[#bbcabf]/20">

                    <h3 class="text-2xl font-semibold text-[#131b2e]">
                        2. Data Pasien
                    </h3>

                    <a
                        href="#"
                        class="text-[#0058be] border border-[#0058be] px-4 py-2 rounded-lg font-semibold hover:bg-[#0058be]/10 transition-colors flex items-center gap-2">

                        <span class="material-symbols-outlined text-sm">
                            open_in_new
                        </span>

                        Lihat Rekam Medis

                    </a>

                </div>


                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">

                    <div>
                        <p class="text-sm text-[#3c4a42] mb-1">
                            Tanggal Lahir
                        </p>

                        <p class="text-base text-[#131b2e]">
                            15 Mei 1985 (38 thn)
                        </p>
                    </div>


                    <div>
                        <p class="text-sm text-[#3c4a42] mb-1">
                            Jenis Kelamin
                        </p>

                        <p class="text-base text-[#131b2e]">
                            Laki-laki
                        </p>
                    </div>


                    <div>
                        <p class="text-sm text-[#3c4a42] mb-1">
                            Golongan Darah
                        </p>

                        <p class="text-base text-[#131b2e]">
                            O+
                        </p>
                    </div>


                    <div>
                        <p class="text-sm text-[#3c4a42] mb-1">
                            Tanggal Kunjungan
                        </p>

                        <p class="text-base text-[#131b2e]">
                            24 Okt 2023
                        </p>
                    </div>

                </div>

            </section>


            <!-- ===================================== -->
            <!-- 3. PENGKAJIAN PASIEN -->
            <!-- ===================================== -->

            <section class="bg-white rounded-xl border border-[#bbcabf]/30 p-6 card-shadow">

                <h3 class="text-2xl font-semibold text-[#131b2e] mb-6">
                    3. Pengkajian Pasien
                </h3>


                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                    <!-- Keluhan Utama -->
                    <div>

                        <label class="block font-semibold text-[#131b2e] mb-2">
                            Keluhan Utama
                        </label>

                        <textarea
                            placeholder="Deskripsikan keluhan utama pasien..."
                            class="w-full bg-[#faf8ff] border border-[#6c7a71] rounded-xl p-3 text-base text-[#131b2e] input-ring min-h-[100px]"
                        ></textarea>

                    </div>


                    <!-- Riwayat Keluhan -->
                    <div>

                        <label class="block font-semibold text-[#131b2e] mb-2">
                            Riwayat Keluhan
                        </label>

                        <textarea
                            placeholder="Riwayat penyakit atau keluhan sebelumnya..."
                            class="w-full bg-[#faf8ff] border border-[#6c7a71] rounded-xl p-3 text-base text-[#131b2e] input-ring min-h-[100px]"
                        ></textarea>

                    </div>

                </div>


                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

                    <!-- Kondisi Umum -->
                    <div>

                        <label class="block font-semibold text-[#131b2e] mb-2">
                            Kondisi Umum
                        </label>

                        <select
                            class="w-full bg-[#faf8ff] border border-[#6c7a71] rounded-xl p-3 text-base text-[#131b2e] input-ring">

                            <option>Baik</option>
                            <option>Cukup</option>
                            <option>Lemah</option>

                        </select>

                    </div>


                    <!-- Kesadaran -->
                    <div>

                        <label class="block font-semibold text-[#131b2e] mb-2">
                            Kesadaran
                        </label>

                        <select
                            class="w-full bg-[#faf8ff] border border-[#6c7a71] rounded-xl p-3 text-base text-[#131b2e] input-ring">

                            <option>Compos Mentis</option>
                            <option>Apatis</option>
                            <option>Delirium</option>
                            <option>Somnolen</option>
                            <option>Sopor</option>
                            <option>Koma</option>

                        </select>

                    </div>

                </div>


                <!-- TANDA VITAL -->
                <h4 class="font-semibold text-[#131b2e] mb-4">
                    Tanda-tanda Vital
                </h4>


                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">

                    <!-- TD -->
                    <div>

                        <label class="block text-sm text-[#3c4a42] mb-1">
                            Tekanan Darah (mmHg)
                        </label>

                        <input
                            type="text"
                            value="120/80"
                            class="w-full bg-[#faf8ff] border border-[#6c7a71] rounded-xl p-2 text-center input-ring">

                    </div>


                    <!-- Nadi -->
                    <div>

                        <label class="block text-sm text-[#3c4a42] mb-1">
                            Nadi (x/mnt)
                        </label>

                        <input
                            type="text"
                            value="82"
                            class="w-full bg-[#faf8ff] border border-[#6c7a71] rounded-xl p-2 text-center input-ring">

                    </div>


                    <!-- Suhu -->
                    <div>

                        <label class="block text-sm text-[#3c4a42] mb-1">
                            Suhu (°C)
                        </label>

                        <input
                            type="text"
                            value="36.5"
                            class="w-full bg-[#faf8ff] border border-[#6c7a71] rounded-xl p-2 text-center input-ring">

                    </div>


                    <!-- Pernapasan -->
                    <div>

                        <label class="block text-sm text-[#3c4a42] mb-1">
                            Pernapasan (x/mnt)
                        </label>

                        <input
                            type="text"
                            value="18"
                            class="w-full bg-[#faf8ff] border border-[#6c7a71] rounded-xl p-2 text-center input-ring">

                    </div>


                    <!-- SpO2 -->
                    <div>

                        <label class="block text-sm text-[#3c4a42] mb-1">
                            SpO2 (%)
                        </label>

                        <input
                            type="text"
                            value="98"
                            class="w-full bg-[#faf8ff] border border-[#6c7a71] rounded-xl p-2 text-center input-ring">

                    </div>

                </div>

            </section>


            <!-- ===================================== -->
            <!-- 4. DIAGNOSIS KEPERAWATAN -->
            <!-- ===================================== -->

            <section class="bg-white rounded-xl border border-[#bbcabf]/30 p-6 card-shadow">

                <h3 class="text-2xl font-semibold text-[#131b2e] mb-6">
                    4. Diagnosis Keperawatan
                </h3>


                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>

                        <label class="block font-semibold text-[#131b2e] mb-2">
                            Masalah / Keluhan Utama
                        </label>

                        <textarea
                            placeholder="Jelaskan masalah spesifik..."
                            class="w-full bg-[#faf8ff] border border-[#6c7a71] rounded-xl p-3 input-ring min-h-[100px]"
                        ></textarea>

                    </div>


                    <div>

                        <label class="block font-semibold text-[#131b2e] mb-2">
                            Faktor Terkait (Etiologi)
                        </label>

                        <textarea
                            placeholder="Penyebab atau faktor risiko..."
                            class="w-full bg-[#faf8ff] border border-[#6c7a71] rounded-xl p-3 input-ring min-h-[100px]"
                        ></textarea>

                    </div>


                    <div class="md:col-span-2">

                        <label class="block font-semibold text-[#131b2e] mb-2">
                            Prioritas Diagnosa
                        </label>

                        <textarea
                            placeholder="Masukkan prioritas diagnosis secara detail..."
                            class="w-full bg-[#faf8ff] border border-[#6c7a71] rounded-xl p-3 input-ring min-h-[100px]"
                        ></textarea>

                    </div>

                </div>

            </section>


            <!-- ===================================== -->
            <!-- 5. RENCANA ASUHAN -->
            <!-- ===================================== -->

            <section class="bg-white rounded-xl border border-[#bbcabf]/30 p-6 card-shadow">

                <div class="flex items-center justify-between mb-6">

                    <h3 class="text-2xl font-semibold text-[#131b2e]">
                        5. Rencana Asuhan (Intervensi)
                    </h3>

                    <button
                        type="button"
                        class="bg-[#f2f3ff] text-[#006c49] border border-[#006c49]/20 px-4 py-2 rounded-lg font-semibold hover:bg-[#e2e7ff] transition-colors flex items-center gap-2">

                        <span class="material-symbols-outlined text-sm">
                            add
                        </span>

                        Tambah Rencana

                    </button>

                </div>


                <div class="overflow-x-auto rounded-lg border border-[#bbcabf]/30">

                    <table class="w-full text-left border-collapse">

                        <thead>

                            <tr class="bg-[#f2f3ff] border-b border-[#bbcabf]/30">

                                <th class="py-3 px-4 font-semibold w-12">
                                    No.
                                </th>

                                <th class="py-3 px-4 font-semibold">
                                    Rencana Tindakan
                                </th>

                                <th class="py-3 px-4 font-semibold">
                                    Target
                                </th>

                                <th class="py-3 px-4 font-semibold">
                                    Keterangan
                                </th>

                                <th class="py-3 px-4 font-semibold w-20 text-center">
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <tr class="border-b border-[#bbcabf]/20 hover:bg-[#faf8ff]">

                                <td class="py-3 px-4 text-[#3c4a42]">
                                    1
                                </td>

                                <td class="py-3 px-4 text-[#131b2e]">
                                    Monitor tanda vital setiap 4 jam
                                </td>

                                <td class="py-3 px-4 text-[#3c4a42]">
                                    TD stabil
                                </td>

                                <td class="py-3 px-4 text-[#3c4a42]">
                                    Rutin
                                </td>

                                <td class="py-3 px-4 text-center">

                                    <button
                                        type="button"
                                        class="text-[#ba1a1a] hover:bg-[#ffdad6] p-1 rounded">

                                        <span class="material-symbols-outlined text-sm">
                                            delete
                                        </span>

                                    </button>

                                </td>

                            </tr>


                            <tr class="hover:bg-[#faf8ff]">

                                <td class="py-3 px-4 text-[#3c4a42]">
                                    2
                                </td>

                                <td class="py-3 px-4 text-[#131b2e]">
                                    Observasi keluhan nyeri secara komprehensif
                                </td>

                                <td class="py-3 px-4 text-[#3c4a42]">
                                    Skala nyeri &lt; 3
                                </td>

                                <td class="py-3 px-4 text-[#3c4a42]">
                                    PQRST
                                </td>

                                <td class="py-3 px-4 text-center">

                                    <button
                                        type="button"
                                        class="text-[#ba1a1a] hover:bg-[#ffdad6] p-1 rounded">

                                        <span class="material-symbols-outlined text-sm">
                                            delete
                                        </span>

                                    </button>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </section>

        </div>


        <!-- ===================================== -->
        <!-- RIGHT COLUMN -->
        <!-- ===================================== -->

        <div class="xl:col-span-1">

            <div class="sticky top-8 flex flex-col gap-6">

                <!-- RINGKASAN -->
                <div class="bg-white rounded-xl border border-[#bbcabf]/30 p-6 card-shadow">

                    <h3 class="font-semibold text-[#131b2e] uppercase tracking-wider mb-4 border-b border-[#bbcabf]/20 pb-2">
                        Ringkasan Dokumen
                    </h3>


                    <div class="space-y-4">

                        <div>

                            <p class="text-sm text-[#3c4a42]">
                                Pasien
                            </p>

                            <p class="text-base text-[#131b2e] font-medium">
                                Andi Pratama
                            </p>

                        </div>


                        <div>

                            <p class="text-sm text-[#3c4a42]">
                                Status Draft
                            </p>

                            <div class="inline-flex items-center gap-1 bg-[#e2e7ff] px-2 py-1 rounded text-[#006c49] text-xs font-medium mt-1">

                                <span class="material-symbols-outlined text-[14px]">
                                    edit_document
                                </span>

                                Belum Tersimpan

                            </div>

                        </div>


                        <div>

                            <p class="text-sm text-[#3c4a42]">
                                Jumlah Rencana
                            </p>

                            <p class="text-base text-[#131b2e]">
                                2 Tindakan
                            </p>

                        </div>


                        <div class="pt-4 border-t border-[#bbcabf]/20 text-xs text-[#3c4a42] italic">
                            Terakhir diperbarui: Hari ini, 10:45 AM
                        </div>

                    </div>

                </div>


                <!-- ACTION BUTTONS -->
                <div class="flex flex-col gap-3">

                    <button
                        type="button"
                        class="w-full bg-[#006c49] text-white py-3 rounded-xl font-semibold hover:bg-[#005236] transition-colors shadow-sm flex items-center justify-center gap-2">

                        <span class="material-symbols-outlined text-sm">
                            save
                        </span>

                        Simpan Asuhan Keperawatan

                    </button>


                    <button
                        type="button"
                        class="w-full bg-white text-[#0058be] border border-[#0058be] py-3 rounded-xl font-semibold hover:bg-[#0058be]/5 transition-colors">

                        Simpan Draft

                    </button>


                    <button
                        type="button"
                        class="w-full bg-transparent text-[#3c4a42] py-3 rounded-xl font-semibold hover:bg-[#e2e7ff] transition-colors">

                        Batal

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection