@extends('layouts.app')

@section('title', 'Pendaftaran Pasien - Mandalacare')

@section('content')

{{-- ========================================================== --}}
{{-- NOTIFIKASI TOAST (BAGIAN ATAS DENGAN AKSEN WARNA HIJAU) --}}
{{-- ========================================================== --}}
<div id="toastNotification" class="fixed top-6 left-1/2 -translate-x-1/2 z-50 w-[92%] max-w-lg pointer-events-none transition-all duration-300 transform -translate-y-16 opacity-0 hidden">
    <div id="toastCard" class="pointer-events-auto bg-white border-2 border-primary/40 rounded-2xl shadow-2xl p-4 md:p-5 flex items-start gap-4 backdrop-blur-md bg-white/95 relative overflow-hidden">
        <!-- Green Accent Bar on the Left -->
        <div id="toastAccentBar" class="absolute left-0 top-0 bottom-0 w-2 bg-primary"></div>
        
        <!-- Green Icon Badge -->
        <div id="toastIconContainer" class="w-11 h-11 rounded-xl bg-[#E5F5F0] text-primary flex items-center justify-center flex-shrink-0 shadow-sm">
            <span id="toastIcon" class="material-symbols-outlined text-2xl font-bold">check_circle</span>
        </div>

        <!-- Content -->
        <div class="flex-1 min-w-0 pr-2">
            <div class="flex items-center gap-2 mb-1">
                <span id="toastBadge" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#E5F5F0] text-primary">
                    Pendaftaran Berhasil
                </span>
                <span class="text-xs text-on-surface-variant font-medium">Baru saja</span>
            </div>
            
            <h4 class="text-base font-bold text-on-surface" id="toastTitle">
                Data Pasien Berhasil Disimpan!
            </h4>
            
            <p class="text-sm text-on-surface-variant mt-1" id="toastMessage">
                Data kunjungan pasien telah berhasil didaftarkan ke dalam sistem.
            </p>

            <!-- Metadata Badges (Antrean & ID Pasien) -->
            <div id="toastMeta" class="mt-3 flex flex-wrap items-center gap-2 pt-2 border-t border-outline-variant/60">
                <div class="flex items-center gap-1.5 bg-[#E5F5F0] px-3 py-1 rounded-lg border border-primary/20 text-xs">
                    <span class="text-on-surface-variant font-medium">Nomor Antrean:</span>
                    <span class="font-bold text-primary text-sm" id="toastNoAntrean">01</span>
                </div>
                <div class="flex items-center gap-1.5 bg-surface-container-low px-3 py-1 rounded-lg border border-outline-variant text-xs">
                    <span class="text-on-surface-variant font-medium">ID Pasien:</span>
                    <span class="font-semibold text-on-surface" id="toastIdPasien">#1</span>
                </div>
                <a href="{{ route('pasien') }}" class="ml-auto inline-flex items-center gap-1 text-xs font-semibold text-primary hover:underline">
                    Lihat Data Pasien
                    <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                </a>
            </div>
        </div>

        <!-- Close Button -->
        <button type="button" onclick="hideToastNotification()" class="text-on-surface-variant hover:text-on-surface p-1 rounded-lg hover:bg-surface-container transition-colors flex-shrink-0" title="Tutup Notifikasi">
            <span class="material-symbols-outlined text-lg">close</span>
        </button>

        <!-- Auto-dismiss Progress Bar -->
        <div id="toastProgress" class="absolute bottom-0 left-0 h-1 bg-primary w-full transition-all linear"></div>
    </div>
</div>

<div class="p-4 sm:p-6 md:p-8 lg:p-10 w-full max-w-7xl mx-auto space-y-6 sm:space-y-8">

    {{-- ============================= --}}
    {{-- HEADER --}}
    {{-- ============================= --}}

    <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-on-surface tracking-tight">
            Pendaftaran Pasien
        </h1>

        <p class="text-sm sm:text-base text-on-surface-variant mt-1">
            Daftarkan pasien baru atau cari pasien yang sudah terdaftar.
        </p>
    </div>


    {{-- ============================= --}}
    {{-- PILIHAN PASIEN --}}
    {{-- ============================= --}}

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">

        {{-- PASIEN BARU --}}
        <div class="bg-white rounded-xl border border-outline-variant p-5 sm:p-6 card-shadow flex flex-col items-start hover:border-primary transition-colors">

            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-surface-container flex items-center justify-center text-primary mb-3 sm:mb-4">
                <span class="material-symbols-outlined text-2xl">
                    person_add
                </span>
            </div>

            <h3 class="text-lg sm:text-xl font-semibold text-on-surface mb-1 sm:mb-2">
                Pasien Baru
            </h3>

            <p class="text-xs sm:text-sm text-on-surface-variant mb-5 sm:mb-6">
                Daftarkan pasien yang belum memiliki data.
            </p>

            <a
                href="#form-pendaftaran"
                class="bg-primary text-white font-semibold py-2.5 sm:py-3 px-6 rounded-lg w-full text-center hover:bg-[#005a3c] transition-colors text-sm mt-auto shadow-sm"
            >
                Pasien Baru
            </a>

        </div>


        {{-- PASIEN LAMA --}}
        <div class="bg-white rounded-xl border border-outline-variant p-5 sm:p-6 card-shadow flex flex-col items-start hover:border-secondary transition-colors">

            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-surface-container-low flex items-center justify-center text-secondary mb-3 sm:mb-4">
                <span class="material-symbols-outlined text-2xl">
                    search
                </span>
            </div>

            <h3 class="text-lg sm:text-xl font-semibold text-on-surface mb-1 sm:mb-2">
                Pasien Lama
            </h3>

            <p class="text-xs sm:text-sm text-on-surface-variant mb-5 sm:mb-6">
                Cari pasien yang sudah terdaftar.
            </p>

            <a
                href="#cari-pasien"
                class="bg-transparent text-secondary border border-secondary font-semibold py-2.5 sm:py-3 px-6 rounded-lg w-full text-center hover:bg-secondary hover:text-white transition-colors text-sm mt-auto"
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

                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 sm:gap-4 pt-6 border-t border-outline-variant">

                    <a
                        href="{{ route('dashboard') }}"
                        class="bg-transparent text-secondary border border-secondary font-semibold py-2.5 px-6 rounded-lg hover:bg-secondary/5 transition-colors text-center inline-block text-sm"
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

        <div class="p-4 sm:p-6 border-b border-outline-variant">

            <h3 class="text-lg sm:text-xl font-semibold text-on-surface mb-3 sm:mb-4">
                Cari Pasien Lama
            </h3>

            <form
                action="{{ route('pasien') }}"
                method="GET"
            >

                <div class="relative flex flex-col sm:flex-row gap-2">

                    <div class="relative flex-1">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant">
                            search
                        </span>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari berdasarkan nama, NIK, atau nomor telepon..."
                            class="w-full bg-surface border border-outline-variant rounded-xl pl-10 pr-4 py-2.5 text-sm text-on-surface placeholder-on-surface-variant/60 input-ring"
                        >
                    </div>

                    <button
                        type="submit"
                        class="bg-primary text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-[#005a3c] transition-colors shadow-sm flex items-center justify-center gap-1.5 shrink-0"
                    >
                        <span class="material-symbols-outlined text-sm">search</span>
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
{{-- JAVASCRIPT SUBMIT FORM & NOTIFIKASI --}}
{{-- ============================= --}}

<script>

let toastTimer = null;

function showSuccessToast(title, message, noAntrean, idPasien) {
    const toast = document.getElementById('toastNotification');
    const toastCard = document.getElementById('toastCard');
    const toastAccentBar = document.getElementById('toastAccentBar');
    const toastIconContainer = document.getElementById('toastIconContainer');
    const toastIcon = document.getElementById('toastIcon');
    const toastBadge = document.getElementById('toastBadge');
    const titleEl = document.getElementById('toastTitle');
    const msgEl = document.getElementById('toastMessage');
    const metaEl = document.getElementById('toastMeta');
    const antreanEl = document.getElementById('toastNoAntrean');
    const idPasienEl = document.getElementById('toastIdPasien');
    const progressEl = document.getElementById('toastProgress');

    if (!toast) return;

    clearTimeout(toastTimer);

    // Styling Aksen Hijau
    toastCard.className = "pointer-events-auto bg-white border-2 border-primary/40 rounded-2xl shadow-2xl p-4 md:p-5 flex items-start gap-4 backdrop-blur-md bg-white/95 relative overflow-hidden";
    toastAccentBar.className = "absolute left-0 top-0 bottom-0 w-2 bg-primary";
    toastIconContainer.className = "w-11 h-11 rounded-xl bg-[#E5F5F0] text-primary flex items-center justify-center flex-shrink-0 shadow-sm";
    toastIcon.innerText = "check_circle";
    toastBadge.className = "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#E5F5F0] text-primary";
    toastBadge.innerText = "Pendaftaran Berhasil";
    progressEl.className = "absolute bottom-0 left-0 h-1 bg-primary w-full transition-all linear";

    // Text & Data
    titleEl.innerText = title || 'Data Pasien Berhasil Disimpan!';
    msgEl.innerText = message || 'Pasien berhasil didaftarkan dan nomor antrean telah diterbitkan.';

    if (noAntrean !== undefined && noAntrean !== null) {
        antreanEl.innerText = String(noAntrean).padStart(2, '0');
        idPasienEl.innerText = '#' + idPasien;
        metaEl.classList.remove('hidden');
    } else {
        metaEl.classList.add('hidden');
    }

    // Reset progress bar
    progressEl.style.width = '100%';
    progressEl.style.transition = 'none';

    // Animasi muncul dari atas
    toast.classList.remove('hidden');
    setTimeout(() => {
        toast.classList.remove('-translate-y-16', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');

        setTimeout(() => {
            progressEl.style.transition = 'width 5000ms linear';
            progressEl.style.width = '0%';
        }, 50);
    }, 10);

    // Sembunyikan otomatis setelah 5.2 detik
    toastTimer = setTimeout(() => {
        hideToastNotification();
    }, 5200);
}

function showErrorToast(title, message) {
    const toast = document.getElementById('toastNotification');
    const toastCard = document.getElementById('toastCard');
    const toastAccentBar = document.getElementById('toastAccentBar');
    const toastIconContainer = document.getElementById('toastIconContainer');
    const toastIcon = document.getElementById('toastIcon');
    const toastBadge = document.getElementById('toastBadge');
    const titleEl = document.getElementById('toastTitle');
    const msgEl = document.getElementById('toastMessage');
    const metaEl = document.getElementById('toastMeta');
    const progressEl = document.getElementById('toastProgress');

    if (!toast) return;

    clearTimeout(toastTimer);

    // Styling Error Merah
    toastCard.className = "pointer-events-auto bg-white border-2 border-red-500/40 rounded-2xl shadow-2xl p-4 md:p-5 flex items-start gap-4 backdrop-blur-md bg-white/95 relative overflow-hidden";
    toastAccentBar.className = "absolute left-0 top-0 bottom-0 w-2 bg-red-600";
    toastIconContainer.className = "w-11 h-11 rounded-xl bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0 shadow-sm";
    toastIcon.innerText = "error";
    toastBadge.className = "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700";
    toastBadge.innerText = "Gagal Menyimpan";
    progressEl.className = "absolute bottom-0 left-0 h-1 bg-red-600 w-full transition-all linear";

    titleEl.innerText = title || 'Gagal Menyimpan Data';
    msgEl.innerText = message || 'Terjadi kesalahan saat menyimpan data pasien.';
    metaEl.classList.add('hidden');

    progressEl.style.width = '100%';
    progressEl.style.transition = 'none';

    toast.classList.remove('hidden');
    setTimeout(() => {
        toast.classList.remove('-translate-y-16', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');

        setTimeout(() => {
            progressEl.style.transition = 'width 6000ms linear';
            progressEl.style.width = '0%';
        }, 50);
    }, 10);

    toastTimer = setTimeout(() => {
        hideToastNotification();
    }, 6200);
}

function hideToastNotification() {
    const toast = document.getElementById('toastNotification');
    if (!toast) return;

    clearTimeout(toastTimer);
    toast.classList.remove('translate-y-0', 'opacity-100');
    toast.classList.add('-translate-y-16', 'opacity-0');

    setTimeout(() => {
        toast.classList.add('hidden');
    }, 300);
}

document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('formPendaftaran');
    const button = document.getElementById('btnSimpan');

    if (!form) {
        return;
    }

    form.addEventListener('submit', async function (event) {

        event.preventDefault();

        button.disabled = true;
        button.innerText = 'Menyimpan...';

        try {

            const formData = new FormData(form);

            const response = await fetch(form.action, {
                method: 'POST',

                headers: {
                    'X-CSRF-TOKEN': document.querySelector(
                        'input[name="_token"]'
                    ).value,

                    'Accept': 'application/json'
                },

                body: formData
            });

            const data = await response.json();

            // =========================
            // BERHASIL DISIMPAN (TOAST AKSEN HIJAU)
            // =========================
            if (response.ok && data.success) {

                showSuccessToast(
                    'Data Pasien Berhasil Disimpan!',
                    'Data pasien telah tersimpan di database dan nomor antrean berhasil diterbitkan.',
                    data.no_antrean,
                    data.id_pasien
                );

                // Kosongkan form
                form.reset();

                // Scroll halus ke atas agar notifikasi terlihat jelas
                window.scrollTo({ top: 0, behavior: 'smooth' });

            }

            // =========================
            // GAGAL
            // =========================
            else {

                let pesan = data.message || 'Data gagal disimpan.';

                if (data.errors) {
                    const errorList = Object.values(data.errors).map(err => err[0]).join(', ');
                    pesan = errorList || pesan;
                }

                if (data.error) {
                    console.error('Database Error:', data.error);
                }

                showErrorToast('Gagal Menyimpan Data', pesan);

            }

        }

        catch (error) {

            console.error(error);

            showErrorToast(
                'Terjadi Kesalahan',
                'Tidak dapat terhubung ke server Laravel. Silakan coba beberapa saat lagi.'
            );

        }

        finally {

            button.disabled = false;

            button.innerHTML =
                'Simpan &amp; Mulai Kunjungan';

        }

    });

});

</script>

@endsection