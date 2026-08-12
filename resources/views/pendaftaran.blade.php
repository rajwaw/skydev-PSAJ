@extends('layouts.app')

@section('title', 'Pendaftaran Pasien - Mandalacare')

@section('content')
<div class="pt-2 px-margin-mobile md:px-margin-desktop pb-12 w-full max-w-container-max mx-auto flex-1">
<div class="mb-stack-lg">
<h1 class="font-headline-lg text-headline-lg font-bold text-on-surface">Pendaftaran Pasien</h1>
<p class="font-body-md text-body-md text-on-surface-variant mt-2">Daftarkan pasien baru atau cari pasien yang sudah terdaftar.</p>
</div>
<!-- Section 1: Selection Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-gutter mb-stack-lg">
<div class="bg-white rounded-xl border border-outline-variant p-6 card-shadow flex flex-col items-start hover:border-primary-container transition-colors cursor-pointer group">
<div class="w-12 h-12 rounded-lg bg-surface-container flex items-center justify-center text-primary mb-4 group-hover:bg-primary-container transition-colors">
<span class="material-symbols-outlined">person_add</span>
</div>
<h3 class="font-headline-md text-headline-md text-on-surface mb-2">Pasien Baru</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant mb-6 flex-1">Daftarkan pasien yang belum memiliki data.</p>
<button class="bg-primary text-white font-label-md text-label-md py-3 px-6 rounded-lg w-full hover:bg-[#005a3c] transition-colors">Pasien Baru</button>
</div>
<div class="bg-white rounded-xl border border-outline-variant p-6 card-shadow flex flex-col items-start hover:border-secondary transition-colors cursor-pointer group">
<div class="w-12 h-12 rounded-lg bg-surface-container-low flex items-center justify-center text-secondary mb-4 group-hover:bg-secondary-fixed-dim transition-colors">
<span class="material-symbols-outlined">search</span>
</div>
<h3 class="font-headline-md text-headline-md text-on-surface mb-2">Pasien Lama</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant mb-6 flex-1">Cari pasien yang sudah terdaftar.</p>
<button class="bg-transparent text-secondary border border-secondary font-label-md text-label-md py-3 px-6 rounded-lg w-full hover:bg-secondary hover:text-white transition-colors">Cari Pasien</button>
</div>
</div>
<!-- Section 2: Registration Form -->
<div class="bg-white rounded-xl border border-outline-variant card-shadow mb-stack-lg overflow-hidden">
<div class="p-6 border-b border-outline-variant">
<h3 class="font-headline-md text-headline-md text-on-surface">Data Pasien</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant mt-1">Masukkan identitas pasien untuk membuat kunjungan baru.</p>
</div>
<div class="p-6">
<form>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
<div class="space-y-2">
<label class="font-label-md text-label-md text-on-surface block">NIK</label>
<input class="w-full bg-white border border-outline-variant rounded-xl px-4 py-3 font-body-sm text-body-sm text-on-surface placeholder-on-surface-variant/50 input-ring" placeholder="Masukkan NIK" type="text">
</div>
<div class="space-y-2">
<label class="font-label-md text-label-md text-on-surface block">Nama Lengkap</label>
<input class="w-full bg-white border border-outline-variant rounded-xl px-4 py-3 font-body-sm text-body-sm text-on-surface placeholder-on-surface-variant/50 input-ring" placeholder="Masukkan nama lengkap" type="text">
</div>
<div class="space-y-2">
<label class="font-label-md text-label-md text-on-surface block">Tanggal Lahir</label>
<div class="relative">
<input class="w-full bg-white border border-outline-variant rounded-xl px-4 py-3 font-body-sm text-body-sm text-on-surface placeholder-on-surface-variant/50 input-ring appearance-none" type="date">
</div>
</div>
<div class="space-y-2">
<label class="font-label-md text-label-md text-on-surface block">Jenis Kelamin</label>
<select class="w-full bg-white border border-outline-variant rounded-xl px-4 py-3 font-body-sm text-body-sm text-on-surface input-ring appearance-none">
<option disabled="" selected="" value="">Pilih Jenis Kelamin</option>
<option value="l">Laki-laki</option>
<option value="p">Perempuan</option>
</select>
</div>
<div class="space-y-2">
<label class="font-label-md text-label-md text-on-surface block">Nomor Telepon</label>
<input class="w-full bg-white border border-outline-variant rounded-xl px-4 py-3 font-body-sm text-body-sm text-on-surface placeholder-on-surface-variant/50 input-ring" placeholder="08xxxxxxxxxx" type="tel">
</div>
<div class="space-y-2">
<label class="font-label-md text-label-md text-on-surface block">Golongan Darah</label>
<select class="w-full bg-white border border-outline-variant rounded-xl px-4 py-3 font-body-sm text-body-sm text-on-surface input-ring appearance-none">
<option disabled="" selected="" value="">Pilih Golongan Darah</option>
<option value="a">A</option>
<option value="b">B</option>
<option value="ab">AB</option>
<option value="o">O</option>
<option value="unknown">Tidak diketahui</option>
</select>
</div>
<div class="space-y-2 md:col-span-2">
<label class="font-label-md text-label-md text-on-surface block">Alamat</label>
<textarea class="w-full bg-white border border-outline-variant rounded-xl px-4 py-3 font-body-sm text-body-sm text-on-surface placeholder-on-surface-variant/50 input-ring resize-none" placeholder="Masukkan alamat lengkap" rows="3"></textarea>
</div>
</div>
<div class="mb-8">
<h4 class="font-label-md text-label-md text-on-surface mb-4 border-b border-outline-variant pb-2">Informasi Tambahan</h4>
<div class="space-y-2">
<label class="font-label-md text-label-md text-on-surface block">Alergi Obat</label>
<input class="w-full bg-white border border-outline-variant rounded-xl px-4 py-3 font-body-sm text-body-sm text-on-surface placeholder-on-surface-variant/50 input-ring" placeholder="Masukkan alergi obat jika ada" type="text">
</div>
</div>
<div class="flex justify-end gap-4 pt-6 border-t border-outline-variant">
<button class="bg-transparent text-secondary border border-secondary font-label-md text-label-md py-2.5 px-6 rounded-lg hover:bg-secondary-container hover:border-transparent transition-colors" type="button">Batal</button>
<button class="bg-primary text-white font-label-md text-label-md py-2.5 px-6 rounded-lg hover:bg-[#005a3c] transition-colors shadow-sm" type="submit">Simpan &amp; Mulai Kunjungan</button>
</div>
</form>
</div>
</div>
<!-- Section 3: Search Pasien Lama -->
<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
<div class="p-6 border-b border-outline-variant">
<h3 class="font-headline-md text-headline-md text-on-surface mb-4">Cari Pasien Lama</h3>
<div class="relative">
<span class="material-symbols-outlined absolute left-4 top-3.5 text-on-surface-variant">search</span>
<input class="w-full bg-surface border border-outline-variant rounded-xl pl-12 pr-4 py-3 font-body-sm text-body-sm text-on-surface placeholder-on-surface-variant/60 input-ring" placeholder="Cari berdasarkan nama, NIK, atau nomor telepon..." type="text">
</div>
</div>
<div class="divide-y divide-outline-variant">
<div class="p-4 flex items-center justify-between hover:bg-surface-container-low transition-colors">
<div class="flex items-center gap-4">
<div class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center text-on-surface font-bold text-sm">AP</div>
<div>
<h4 class="font-label-md text-label-md text-on-surface">Andi Pratama</h4>
<p class="font-body-sm text-body-sm text-on-surface-variant">NIK: 3271••••890 <span class="mx-2">•</span> Kunjungan terakhir: <span class="text-primary-container font-medium">Hari ini</span></p>
</div>
</div>
<button class="bg-transparent text-secondary border border-secondary font-label-sm text-label-sm py-1.5 px-4 rounded-md hover:bg-secondary hover:text-white transition-colors">Pilih</button>
</div>
<div class="p-4 flex items-center justify-between hover:bg-surface-container-low transition-colors">
<div class="flex items-center gap-4">
<div class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center text-on-surface font-bold text-sm">SA</div>
<div>
<h4 class="font-label-md text-label-md text-on-surface">Siti Aisyah</h4>
<p class="font-body-sm text-body-sm text-on-surface-variant">NIK: 3271••••123 <span class="mx-2">•</span> Kunjungan terakhir: Kemarin</p>
</div>
</div>
<button class="bg-transparent text-secondary border border-secondary font-label-sm text-label-sm py-1.5 px-4 rounded-md hover:bg-secondary hover:text-white transition-colors">Pilih</button>
</div>
</div>
</div>
</div>
@endsection
