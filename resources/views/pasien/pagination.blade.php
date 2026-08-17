<div class="p-4 border-t border-outline-variant flex flex-col sm:flex-row items-center justify-between gap-4">
    <span class="text-xs sm:text-sm text-on-surface-variant text-center sm:text-left">
        @if ($pasiens->total() > 0)
            Menampilkan {{ $pasiens->firstItem() }}–{{ $pasiens->lastItem() }} dari {{ $pasiens->total() }} pasien
        @else
            Menampilkan 0 pasien
        @endif
    </span>

    @if ($pasiens->hasPages())
        <div class="flex items-center gap-1 overflow-x-auto max-w-full pb-1 sm:pb-0">
            {{-- Tombol Sebelumnya --}}
            @if ($pasiens->onFirstPage())
                <button type="button" class="w-8 h-8 flex items-center justify-center rounded text-on-surface-variant/40 cursor-not-allowed shrink-0" disabled>
                    <span class="material-symbols-outlined text-sm">chevron_left</span>
                </button>
            @else
                <a href="{{ $pasiens->previousPageUrl() }}" class="pagination-link w-8 h-8 flex items-center justify-center rounded text-on-surface hover:bg-surface-container-low transition-colors shrink-0" data-page="{{ $pasiens->currentPage() - 1 }}">
                    <span class="material-symbols-outlined text-sm">chevron_left</span>
                </a>
            @endif

            {{-- Angka Halaman --}}
            @foreach ($pasiens->getUrlRange(1, $pasiens->lastPage()) as $page => $url)
                @if ($page == $pasiens->currentPage())
                    <span class="w-8 h-8 flex items-center justify-center rounded bg-primary text-white font-medium text-xs shrink-0 flex items-center justify-center">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="pagination-link w-8 h-8 flex items-center justify-center rounded text-on-surface hover:bg-surface-container-low transition-colors text-xs font-medium shrink-0 flex items-center justify-center" data-page="{{ $page }}">{{ $page }}</a>
                @endif
            @endforeach

            {{-- Tombol Selanjutnya --}}
            @if ($pasiens->hasMorePages())
                <a href="{{ $pasiens->nextPageUrl() }}" class="pagination-link w-8 h-8 flex items-center justify-center rounded text-on-surface hover:bg-surface-container-low transition-colors shrink-0" data-page="{{ $pasiens->currentPage() + 1 }}">
                    <span class="material-symbols-outlined text-sm">chevron_right</span>
                </a>
            @else
                <button type="button" class="w-8 h-8 flex items-center justify-center rounded text-on-surface-variant/40 cursor-not-allowed shrink-0" disabled>
                    <span class="material-symbols-outlined text-sm">chevron_right</span>
                </button>
            @endif
        </div>
    @endif
</div>
