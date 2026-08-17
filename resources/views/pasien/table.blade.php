@forelse ($pasiens as $index => $pasien)
    <tr class="hover:bg-surface-container-low transition-colors">
        <td class="py-3.5 px-4 sm:px-6 text-sm text-on-surface-variant whitespace-nowrap">
            {{ sprintf('%02d', ($pasiens->firstItem() ?? 1) + $index) }}
        </td>
        <td class="py-3.5 px-4 sm:px-6 text-sm font-semibold whitespace-nowrap">
            {{ $pasien->nama_lengkap }}
        </td>
        <td class="py-3.5 px-4 sm:px-6 text-sm whitespace-nowrap">
            {{ $pasien->nik }}
        </td>
        <td class="py-3.5 px-4 sm:px-6 text-sm whitespace-nowrap">
            {{ $pasien->formatted_tgl_lahir }}
        </td>
        <td class="py-3.5 px-4 sm:px-6 text-sm whitespace-nowrap">
            {{ $pasien->formatted_jk }}
        </td>
        <td class="py-3.5 px-4 sm:px-6 text-sm whitespace-nowrap">
            {{ $pasien->no_telp ?: '-' }}
        </td>
        <td class="py-3.5 px-4 sm:px-6 text-sm whitespace-nowrap">
            @if ($pasien->kunjungan_terakhir)
                @php
                    $kunjungan = \Carbon\Carbon::parse($pasien->kunjungan_terakhir);
                @endphp
                @if ($kunjungan->isToday())
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-[#E5F5F0] text-primary font-medium text-xs">Hari ini</span>
                @elseif ($kunjungan->isYesterday())
                    <span class="text-sm text-on-surface-variant">Kemarin</span>
                @else
                    <span class="text-sm text-on-surface-variant">
                        @php
                            $monthsShort = [
                                1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
                                5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agt',
                                9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
                            ];
                            $day = $kunjungan->format('j');
                            $mNum = (int) $kunjungan->format('n');
                            $mStr = $monthsShort[$mNum] ?? $kunjungan->format('M');
                            $yr = $kunjungan->format('Y');
                        @endphp
                        {{ $day }} {{ $mStr }} {{ $yr }}
                    </span>
                @endif
            @else
                <span class="text-sm text-on-surface-variant italic">-</span>
            @endif
        </td>
        <td class="py-3.5 px-4 sm:px-6 text-right whitespace-nowrap">
            <a href="{{ route('rekam-medis') }}?id={{ $pasien->id_pasien }}" class="inline-flex items-center justify-center text-[#1A73E8] hover:text-[#1557B0] transition-colors p-1.5 rounded-lg hover:bg-[#E8F0FE]" title="Lihat Rekam Medis">
                <span class="material-symbols-outlined">chevron_right</span>
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="py-8 sm:py-12 px-4 sm:px-6 text-center">
            <div class="flex flex-col items-center justify-center">
                <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-[#E5F5F0] flex items-center justify-center text-primary mb-3">
                    <span class="material-symbols-outlined text-2xl sm:text-3xl">person_off</span>
                </div>
                <h4 class="text-sm sm:text-base font-semibold text-on-surface mb-1">
                    @if (!empty($search))
                        Tidak ada pasien yang cocok dengan pencarian "{{ $search }}"
                    @else
                        Belum Ada Data Pasien
                    @endif
                </h4>
                <p class="text-xs sm:text-sm text-on-surface-variant max-w-md mb-4">
                    @if (!empty($search))
                        Coba periksa kembali kata kunci nama, NIK, atau nomor telepon.
                    @else
                        Silakan lakukan pendaftaran pasien baru melalui menu pendaftaran.
                    @endif
                </p>
                @if (empty($search))
                    <a href="{{ route('pendaftaran') }}" class="bg-primary text-white px-4 py-2 sm:px-5 sm:py-2.5 rounded-lg font-semibold hover:bg-[#005a3c] transition-colors text-xs sm:text-sm inline-flex items-center gap-2 shadow-sm">
                        <span class="material-symbols-outlined text-sm">add</span>
                        Daftarkan Pasien Pertama
                    </a>
                @else
                    <button type="button" onclick="clearPasienSearch()" class="text-primary hover:underline text-xs sm:text-sm font-semibold">
                        Reset Pencarian
                    </button>
                @endif
            </div>
        </td>
    </tr>
@endforelse
