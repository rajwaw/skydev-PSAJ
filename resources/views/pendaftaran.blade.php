@extends('layouts.app')

@section('title', 'Pendaftaran Pasien - Mandalacare')

@section('content')
<div class="p-6 md:p-8 lg:p-10 w-full max-w-[1440px] mx-auto">

    <!-- Header Page -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-on-surface">Pendaftaran Pasien</h1>
        <p class="text-base text-on-surface-variant mt-2">Daftarkan pasien baru atau cari pasien yang sudah terdaftar.</p>
    </div>

    <!-- Section 1: Selection Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-xl border border-outline-variant p-6 card-shadow flex flex-col items-start hover:border-primary transition-colors cursor-pointer group">
            <div class="w-12 h-12 rounded-lg bg-surface-container flex items-center justify-center text-primary mb-4 group-hover:bg-primary-container transition-colors">
                <span class="material-symbols-outlined">person_add</span>
            </div>
            <h3 class="text-xl font-semibold text-on-surface mb-2">Pasien Baru</h3>
            <p class="text-sm text-on-surface-variant mb-6 flex-1">Daftarkan pasien yang belum memiliki data.</p>
            <a href="#form-pendaftaran" class="bg-primary text-white font-semibold py-3 px-6 rounded-lg w-full text-center hover:bg-[#005a3c] transition-colors">Pasien Baru</a>
        </div>

        <div class="bg-white rounded-xl border border-outline-variant p-6 card-shadow flex flex-col items-start hover:border-secondary transition-colors cursor-pointer group">
            <div class="w-12 h-12 rounded-lg bg-surface-container-low flex items-center justify-center text-secondary mb-4 group-hover:bg-secondary-fixed-dim transition-colors">
                <span class="material-symbols-outlined">search</span>
            </div>
            <h3 class="text-xl font-semibold text-on-surface mb-2">Pasien Lama</h3>
            <p class="text-sm text-on-surface-variant mb-6 flex-1">Cari pasien yang sudah terdaftar.</p>
            <a href="#cari-pasien" class="bg-transparent text-secondary border border-secondary font-semibold py-3 px-6 rounded-lg w-full text-center hover:bg-secondary hover:text-white transition-colors">Cari Pasien</a>
        </div>
    </div>

    <!-- Section 2: Registration Form -->
    <div id="form-pendaftaran" class="bg-white rounded-xl border border-outline-variant card-shadow mb-8 overflow-hidden">
        <div class="p-6 border-b border-outline-variant">
            <h3 class="text-xl font-semibold text-on-surface">Data Pasien</h3>
            <p class="text-sm text-on-surface-variant mt-1">Masukkan identitas pasien untuk membuat kunjungan baru.</p>
        </div>
        <div class="p-6">
            <form action="#" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-on-surface block">NIK</label>
                        <input class="w-full bg-white border border-outline-variant rounded-xl px-4 py-3 text-sm text-on-surface placeholder-on-surface-variant/50 input-ring" placeholder="Masukkan NIK" type="text" name="nik">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-on-surface block">Nama Lengkap</label>
                        <input class="w-full bg-white border border-outline-variant rounded-xl px-4 py-3 text-sm text-on-surface placeholder-on-surface-variant/50 input-ring" placeholder="Masukkan nama lengkap" type="text" name="nama">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-on-surface block">Tanggal Lahir</label>
                        <input class="w-full bg-white border border-outline-variant rounded-xl px-4 py-3 text-sm text-on-surface input-ring" type="date" name="tanggal_lahir">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-on-surface block">Jenis Kelamin</label>
                        <select class="w-full bg-white border border-outline-variant rounded-xl px-4 py-3 text-sm text-on-surface input-ring" name="jenis_kelamin">
                            <option disabled selected value="">Pilih Jenis Kelamin</option>
                            <option value="l">Laki-laki</option>
                            <option value="p">Perempuan</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-on-surface block">Nomor Telepon</label>
                        <input class="w-full bg-white border border-outline-variant rounded-xl px-4 py-3 text-sm text-on-surface placeholder-on-surface-variant/50 input-ring" placeholder="08xxxxxxxxxx" type="tel" name="telepon">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-on-surface block">Golongan Darah</label>
                        <select class="w-full bg-white border border-outline-variant rounded-xl px-4 py-3 text-sm text-on-surface input-ring" name="golongan_darah">
                            <option disabled selected value="">Pilih Golongan Darah</option>
                            <option value="a">A</option>
                            <option value="b">B</option>
                            <option value="ab">AB</option>
                            <option value="o">O</option>
                            <option value="unknown">Tidak diketahui</option>
                        </select>
                    </div>

                    <div class="space-y-2 md:col-span-2">
                        <label class="text-sm font-semibold text-on-surface block">Alamat</label>
                        <textarea class="w-full bg-white border border-outline-variant rounded-xl px-4 py-3 text-sm text-on-surface placeholder-on-surface-variant/50 input-ring resize-none" placeholder="Masukkan alamat lengkap" rows="3" name="alamat"></textarea>
                    </div>
                </div>

                <div class="mb-8">
                    <h4 class="text-sm font-semibold text-on-surface mb-4 border-b border-outline-variant pb-2">Informasi Tambahan</h4>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-on-surface block">Alergi Obat</label>
                        <input class="w-full bg-white border border-outline-variant rounded-xl px-4 py-3 text-sm text-on-surface placeholder-on-surface-variant/50 input-ring" placeholder="Masukkan alergi obat jika ada" type="text" name="alergi_obat">
                    </div>
                </div>

                <div class="flex justify-end gap-4 pt-6 border-t border-outline-variant">
                    <a href="{{ route('dashboard') }}" class="bg-transparent text-secondary border border-secondary font-semibold py-2.5 px-6 rounded-lg hover:bg-secondary-container hover:border-transparent hover:text-white transition-colors text-center inline-block text-sm">Batal</a>
                    <button class="bg-primary text-white font-semibold py-2.5 px-6 rounded-lg hover:bg-[#005a3c] transition-colors shadow-sm text-sm" type="submit">Simpan &amp; Mulai Kunjungan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Section 3: Search Pasien Lama -->
    <div id="cari-pasien" class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
        <div class="p-6 border-b border-outline-variant">
            <h3 class="text-xl font-semibold text-on-surface mb-4">Cari Pasien Lama</h3>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-4 top-3.5 text-on-surface-variant">search</span>
                <input class="w-full bg-surface border border-outline-variant rounded-xl pl-12 pr-4 py-3 text-sm text-on-surface placeholder-on-surface-variant/60 input-ring" placeholder="Cari berdasarkan nama, NIK, atau nomor telepon..." type="text">
            </div>
        </div>
        <div class="divide-y divide-outline-variant">
            <div class="p-4 flex items-center justify-between hover:bg-surface-container-low transition-colors">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center text-on-surface font-bold text-sm">AP</div>
                    <div>
                        <h4 class="text-sm font-semibold text-on-surface">Andi Pratama</h4>
                        <p class="text-xs text-on-surface-variant">NIK: 3271••••890 <span class="mx-2">•</span> Kunjungan terakhir: <span class="text-primary-container font-medium">Hari ini</span></p>
                    </div>
                </div>
                <a href="{{ route('rekam-medis') }}" class="bg-transparent text-secondary border border-secondary text-xs py-1.5 px-4 rounded-md hover:bg-secondary hover:text-white transition-colors font-semibold">Pilih</a>
            </div>
            <div class="p-4 flex items-center justify-between hover:bg-surface-container-low transition-colors">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center text-on-surface font-bold text-sm">SA</div>
                    <div>
                        <h4 class="text-sm font-semibold text-on-surface">Siti Aisyah</h4>
                        <p class="text-xs text-on-surface-variant">NIK: 3271••••123 <span class="mx-2">•</span> Kunjungan terakhir: Kemarin</p>
                    </div>
                </div>
                <a href="{{ route('rekam-medis') }}" class="bg-transparent text-secondary border border-secondary text-xs py-1.5 px-4 rounded-md hover:bg-secondary hover:text-white transition-colors font-semibold">Pilih</a>
            </div>
        </div>
    </div>

</div>
@endsection
