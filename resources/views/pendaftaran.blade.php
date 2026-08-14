@extends('layouts.app')

@section('title', 'Pendaftaran Pasien - Mandalacare')

@section('content')

<div class="p-6 md:p-8 lg:p-10 w-full max-w-[1440px] mx-auto">

    {{-- ============================= --}}
    {{-- HEADER --}}
    {{-- ============================= --}}

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-on-surface">
            Pendaftaran Pasien
        </h1>

        <p class="text-base text-on-surface-variant mt-2">
            Daftarkan pasien baru atau cari pasien yang sudah terdaftar.
        </p>
    </div>


    {{-- ============================= --}}
    {{-- PILIHAN PASIEN --}}
    {{-- ============================= --}}

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

        {{-- PASIEN BARU --}}
        <div class="bg-white rounded-xl border border-outline-variant p-6 card-shadow flex flex-col items-start hover:border-primary transition-colors">

            <div class="w-12 h-12 rounded-lg bg-surface-container flex items-center justify-center text-primary mb-4">
                <span class="material-symbols-outlined">
                    person_add
                </span>
            </div>

            <h3 class="text-xl font-semibold text-on-surface mb-2">
                Pasien Baru
            </h3>

            <p class="text-sm text-on-surface-variant mb-6">
                Daftarkan pasien yang belum memiliki data.
            </p>

            <a
                href="#form-pendaftaran"
                class="bg-primary text-white font-semibold py-3 px-6 rounded-lg w-full text-center hover:bg-[#005a3c] transition-colors"
            >
                Pasien Baru
            </a>

        </div>


        {{-- PASIEN LAMA --}}
        <div class="bg-white rounded-xl border border-outline-variant p-6 card-shadow flex flex-col items-start hover:border-secondary transition-colors">

            <div class="w-12 h-12 rounded-lg bg-surface-container-low flex items-center justify-center text-secondary mb-4">
                <span class="material-symbols-outlined">
                    search
                </span>
            </div>

            <h3 class="text-xl font-semibold text-on-surface mb-2">
                Pasien Lama
            </h3>

            <p class="text-sm text-on-surface-variant mb-6">
                Cari pasien yang sudah terdaftar.
            </p>

            <a
                href="#cari-pasien"
                class="bg-transparent text-secondary border border-secondary font-semibold py-3 px-6 rounded-lg w-full text-center hover:bg-secondary hover:text-white transition-colors"
            >
                Cari Pasien
            </a>

        </div>

    </div>


    {{-- ============================= --}}
    {{-- FORM PASIEN BARU --}}
    {{-- ============================= --}}

    <div
        id="form-pendaftaran"
        class="bg-white rounded-xl border border-outline-variant card-shadow mb-8 overflow-hidden"
    >

        {{-- HEADER FORM --}}
        <div class="p-6 border-b border-outline-variant">

            <h3 class="text-xl font-semibold text-on-surface">
                Data Pasien
            </h3>

            <p class="text-sm text-on-surface-variant mt-1">
                Masukkan identitas pasien untuk membuat kunjungan baru.
            </p>

        </div>


        {{-- FORM --}}
        <div class="p-6">

            <form
                id="formPendaftaran"
                action="{{ route('pendaftaran.store') }}"
                method="POST"
            >

                @csrf


                {{-- ============================= --}}
                {{-- DATA IDENTITAS --}}
                {{-- ============================= --}}

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">


                    {{-- NIK --}}
                    <div class="space-y-2">

                        <label
                            for="nik"
                            class="text-sm font-semibold text-on-surface block"
                        >
                            NIK
                        </label>

                        <input
                            id="nik"
                            type="text"
                            name="nik"
                            value="{{ old('nik') }}"
                            placeholder="Masukkan NIK"
                            maxlength="20"
                            required
                            class="w-full bg-white border border-outline-variant rounded-xl px-4 py-3 text-sm text-on-surface placeholder-on-surface-variant/50 input-ring"
                        >

                    </div>


                    {{-- NAMA --}}
                    <div class="space-y-2">

                        <label
                            for="nama"
                            class="text-sm font-semibold text-on-surface block"
                        >
                            Nama Lengkap
                        </label>

                        <input
                            id="nama"
                            type="text"
                            name="nama"
                            value="{{ old('nama') }}"
                            placeholder="Masukkan nama lengkap"
                            required
                            class="w-full bg-white border border-outline-variant rounded-xl px-4 py-3 text-sm text-on-surface placeholder-on-surface-variant/50 input-ring"
                        >

                    </div>


                    {{-- TANGGAL LAHIR --}}
                    <div class="space-y-2">

                        <label
                            for="tanggal_lahir"
                            class="text-sm font-semibold text-on-surface block"
                        >
                            Tanggal Lahir
                        </label>

                        <input
                            id="tanggal_lahir"
                            type="date"
                            name="tanggal_lahir"
                            value="{{ old('tanggal_lahir') }}"
                            required
                            class="w-full bg-white border border-outline-variant rounded-xl px-4 py-3 text-sm text-on-surface input-ring"
                        >

                    </div>


                    {{-- JENIS KELAMIN --}}
                    <div class="space-y-2">

                        <label
                            for="jenis_kelamin"
                            class="text-sm font-semibold text-on-surface block"
                        >
                            Jenis Kelamin
                        </label>

                        <select
                            id="jenis_kelamin"
                            name="jenis_kelamin"
                            required
                            class="w-full bg-white border border-outline-variant rounded-xl px-4 py-3 text-sm text-on-surface input-ring"
                        >

                            <option value="">
                                Pilih Jenis Kelamin
                            </option>

                            <option
                                value="L"
                                {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}
                            >
                                Laki-laki
                            </option>

                            <option
                                value="P"
                                {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}
                            >
                                Perempuan
                            </option>

                        </select>

                    </div>


                    {{-- NOMOR TELEPON --}}
                    <div class="space-y-2">

                        <label
                            for="telepon"
                            class="text-sm font-semibold text-on-surface block"
                        >
                            Nomor Telepon
                        </label>

                        <input
                            id="telepon"
                            type="tel"
                            name="telepon"
                            value="{{ old('telepon') }}"
                            placeholder="08xxxxxxxxxx"
                            maxlength="20"
                            class="w-full bg-white border border-outline-variant rounded-xl px-4 py-3 text-sm text-on-surface placeholder-on-surface-variant/50 input-ring"
                        >

                    </div>


                    {{-- GOLONGAN DARAH --}}
                    <div class="space-y-2">

                        <label
                            for="golongan_darah"
                            class="text-sm font-semibold text-on-surface block"
                        >
                            Golongan Darah
                        </label>

                        <select
                            id="golongan_darah"
                            name="golongan_darah"
                            class="w-full bg-white border border-outline-variant rounded-xl px-4 py-3 text-sm text-on-surface input-ring"
                        >

                            <option value="">
                                Pilih Golongan Darah
                            </option>

                            <option
                                value="A"
                                {{ old('golongan_darah') == 'A' ? 'selected' : '' }}
                            >
                                A
                            </option>

                            <option
                                value="B"
                                {{ old('golongan_darah') == 'B' ? 'selected' : '' }}
                            >
                                B
                            </option>

                            <option
                                value="AB"
                                {{ old('golongan_darah') == 'AB' ? 'selected' : '' }}
                            >
                                AB
                            </option>

                            <option
                                value="O"
                                {{ old('golongan_darah') == 'O' ? 'selected' : '' }}
                            >
                                O
                            </option>

                        </select>

                    </div>


                    {{-- ALAMAT --}}
                    <div class="space-y-2 md:col-span-2">

                        <label
                            for="alamat"
                            class="text-sm font-semibold text-on-surface block"
                        >
                            Alamat
                        </label>

                        <textarea
                            id="alamat"
                            name="alamat"
                            rows="3"
                            placeholder="Masukkan alamat lengkap"
                            class="w-full bg-white border border-outline-variant rounded-xl px-4 py-3 text-sm text-on-surface placeholder-on-surface-variant/50 input-ring resize-none"
                        >{{ old('alamat') }}</textarea>

                    </div>

                </div>


                {{-- ============================= --}}
                {{-- INFORMASI TAMBAHAN --}}
                {{-- ============================= --}}

                <div class="mb-8">

                    <h4 class="text-sm font-semibold text-on-surface mb-4 border-b border-outline-variant pb-2">
                        Informasi Tambahan
                    </h4>


                    {{-- ALERGI OBAT --}}
                    <div class="space-y-2">

                        <label
                            for="alergi_obat"
                            class="text-sm font-semibold text-on-surface block"
                        >
                            Alergi Obat
                        </label>

                        <input
                            id="alergi_obat"
                            type="text"
                            name="alergi_obat"
                            value="{{ old('alergi_obat') }}"
                            placeholder="Masukkan alergi obat jika ada"
                            class="w-full bg-white border border-outline-variant rounded-xl px-4 py-3 text-sm text-on-surface placeholder-on-surface-variant/50 input-ring"
                        >

                    </div>

                </div>


                {{-- ============================= --}}
                {{-- BUTTON --}}
                {{-- ============================= --}}

                <div class="flex justify-end gap-4 pt-6 border-t border-outline-variant">

                    <a
                        href="{{ route('dashboard') }}"
                        class="bg-transparent text-secondary border border-secondary font-semibold py-2.5 px-6 rounded-lg hover:bg-secondary-container hover:border-transparent hover:text-white transition-colors text-center inline-block text-sm"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        id="btnSimpan"
                        class="bg-primary text-white font-semibold py-2.5 px-6 rounded-lg hover:bg-[#005a3c] transition-colors shadow-sm text-sm"
                    >
                        Simpan &amp; Mulai Kunjungan
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- ============================= --}}
    {{-- CARI PASIEN LAMA --}}
    {{-- ============================= --}}

    <div
        id="cari-pasien"
        class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden"
    >

        <div class="p-6 border-b border-outline-variant">

            <h3 class="text-xl font-semibold text-on-surface mb-4">
                Cari Pasien Lama
            </h3>

            <form
                action="{{ route('pasien.index') }}"
                method="GET"
            >

                <div class="relative">

                    <span class="material-symbols-outlined absolute left-4 top-3.5 text-on-surface-variant">
                        search
                    </span>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari berdasarkan nama, NIK, atau nomor telepon..."
                        class="w-full bg-surface border border-outline-variant rounded-xl pl-12 pr-24 py-3 text-sm text-on-surface placeholder-on-surface-variant/60 input-ring"
                    >

                    <button
                        type="submit"
                        class="absolute right-2 top-1.5 bg-primary text-white px-4 py-2 rounded-lg text-sm font-semibold"
                    >
                        Cari
                    </button>

                </div>

            </form>

        </div>


        {{-- HASIL PENCARIAN --}}
        <div class="divide-y divide-outline-variant">

            @if(isset($pasien) && $pasien->count() > 0)

                @foreach($pasien as $item)

                    <div class="p-4 flex items-center justify-between hover:bg-surface-container-low transition-colors">

                        <div class="flex items-center gap-4">

                            <div class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center text-on-surface font-bold text-sm">
                                {{ strtoupper(substr($item->nama_lengkap, 0, 2)) }}
                            </div>

                            <div>

                                <h4 class="text-sm font-semibold text-on-surface">
                                    {{ $item->nama_lengkap }}
                                </h4>

                                <p class="text-xs text-on-surface-variant">
                                    NIK:
                                    {{ $item->nik }}

                                    <span class="mx-2">
                                        •
                                    </span>

                                    Telepon:
                                    {{ $item->no_telp ?? '-' }}
                                </p>

                            </div>

                        </div>

                        <a
                            href="{{ route('rekam-medis', ['id_pasien' => $item->id_pasien]) }}"
                            class="bg-transparent text-secondary border border-secondary text-xs py-1.5 px-4 rounded-md hover:bg-secondary hover:text-white transition-colors font-semibold"
                        >
                            Pilih
                        </a>

                    </div>

                @endforeach

            @elseif(request('search'))

                <div class="p-8 text-center text-sm text-on-surface-variant">
                    Pasien tidak ditemukan.
                </div>

            @else

                <div class="p-8 text-center text-sm text-on-surface-variant">
                    Silakan cari pasien berdasarkan nama, NIK, atau nomor telepon.
                </div>

            @endif

        </div>

    </div>

</div>

{{-- ============================= --}}
{{-- POPUP NOTIFIKASI --}}
{{-- ============================= --}}

<div
    id="notificationModal"
    class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/40 backdrop-blur-sm px-4"
>
    <div
        class="w-full max-w-md bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden"
    >

        {{-- ICON --}}
        <div class="flex justify-center pt-7">

            <div
                id="notificationIcon"
                class="w-16 h-16 rounded-full flex items-center justify-center bg-green-100 text-green-600"
            >
                <span
                    id="notificationIconSymbol"
                    class="material-symbols-outlined text-4xl"
                >
                    check_circle
                </span>
            </div>

        </div>


        {{-- ISI POPUP --}}
        <div class="px-6 py-5 text-center">

            <h3
                id="notificationTitle"
                class="text-xl font-bold text-gray-900"
            >
                Data Berhasil Disimpan
            </h3>

            <p
                id="notificationMessage"
                class="text-sm text-gray-600 mt-3 whitespace-pre-line leading-6"
            ></p>

        </div>


        {{-- BUTTON --}}
        <div class="px-6 pb-6">

            <button
                type="button"
                id="notificationClose"
                class="w-full bg-primary text-white font-semibold py-3 rounded-lg hover:bg-[#005a3c] transition-colors"
            >
                OK
            </button>

        </div>

    </div>
</div>


{{-- ============================= --}}
{{-- JAVASCRIPT SUBMIT FORM --}}
{{-- ============================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('formPendaftaran');
    const button = document.getElementById('btnSimpan');

    const modal = document.getElementById('notificationModal');
    const title = document.getElementById('notificationTitle');
    const message = document.getElementById('notificationMessage');

    const icon = document.getElementById('notificationIcon');
    const iconSymbol = document.getElementById('notificationIconSymbol');

    const closeButton = document.getElementById('notificationClose');


    // ==========================================
    // TAMPILKAN POPUP
    // ==========================================

    function showNotification(type, titleText, messageText) {

        title.innerText = titleText;
        message.innerText = messageText;


        // ==========================
        // POPUP BERHASIL
        // ==========================

        if (type === 'success') {

            icon.className =
                'w-16 h-16 rounded-full flex items-center justify-center bg-green-100 text-green-600';

            iconSymbol.innerText = 'check_circle';

            closeButton.className =
                'w-full bg-primary text-white font-semibold py-3 rounded-lg hover:bg-[#005a3c] transition-colors';

        }


        // ==========================
        // POPUP ERROR
        // ==========================

        else {

            icon.className =
                'w-16 h-16 rounded-full flex items-center justify-center bg-red-100 text-red-600';

            iconSymbol.innerText = 'error';

            closeButton.className =
                'w-full bg-red-600 text-white font-semibold py-3 rounded-lg hover:bg-red-700 transition-colors';

        }


        // Tampilkan popup
        modal.classList.remove('hidden');
        modal.classList.add('flex');

    }


    // ==========================================
    // TUTUP POPUP
    // ==========================================

    closeButton.addEventListener('click', function () {

        modal.classList.add('hidden');
        modal.classList.remove('flex');

    });


    // ==========================================
    // SUBMIT FORM
    // ==========================================

    form.addEventListener('submit', async function (event) {

        event.preventDefault();


        // Disable tombol
        button.disabled = true;

        button.innerText = 'Menyimpan...';


        try {

            const formData = new FormData(form);


            const response = await fetch(form.action, {

                method: 'POST',

                headers: {

                    'X-CSRF-TOKEN':
                        document.querySelector(
                            'input[name="_token"]'
                        ).value,

                    'Accept': 'application/json'

                },

                body: formData

            });


            const data = await response.json();


            // ==========================================
            // BERHASIL
            // ==========================================

            if (response.ok && data.success) {

                showNotification(

                    'success',

                    'Data Berhasil Disimpan',

                    'Data pasien berhasil disimpan.\n\n' +

                    'Nomor Antrean : ' +
                    data.no_antrean +

                    '\nID Pasien : ' +
                    data.id_pasien

                );


                // Kosongkan form
                form.reset();

            }


            // ==========================================
            // DATA SUDAH ADA
            // ==========================================

            else if (response.status === 409) {

                showNotification(

                    'error',

                    'Data Pasien Sudah Ada',

                    data.message ||
                    'Pasien dengan NIK tersebut sudah terdaftar di sistem.'

                );

            }


            // ==========================================
            // VALIDASI
            // ==========================================

            else {

                let pesan =
                    data.message ||
                    'Data gagal disimpan.';


                if (data.errors) {

                    pesan += '\n\n';

                    Object.values(data.errors).forEach(function (error) {

                        pesan +=
                            '• ' +
                            error[0] +
                            '\n';

                    });

                }


                showNotification(

                    'error',

                    'Data Gagal Disimpan',

                    pesan

                );

            }

        }


        // ==========================================
        // ERROR SERVER
        // ==========================================

        catch (error) {

            console.error(error);

            showNotification(

                'error',

                'Terjadi Kesalahan',

                'Tidak dapat terhubung ke server Laravel.'

            );

        }


        // ==========================================
        // KEMBALIKAN TOMBOL
        // ==========================================

        finally {

            button.disabled = false;

            button.innerText =
                'Simpan & Mulai Kunjungan';

        }

    });

});

</script>

@endsection