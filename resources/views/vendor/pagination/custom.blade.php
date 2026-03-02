{{--
    Komponen Paginasi Kustom (vendor/pagination/custom.blade.php)

    Override tampilan paginasi bawaan Laravel dengan desain shadcn UI style.
    Digunakan di semua halaman yang memiliki paginasi dengan memanggil:
    {{ $data->links('vendor.pagination.custom') }}
    
    Fitur:
    - Info teks: "Menampilkan X-Y dari Z data"
    - Tombol navigasi: Previous, halaman, Next
    - Halaman aktif ditandai dengan background hitam
    - Tombol disabled saat di halaman pertama/terakhir
--}}
@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination" class="flex items-center justify-between mt-6">
    {{-- Info text --}}
    <div class="text-sm text-gray-500">
        Menampilkan
        <span class="font-medium text-gray-700">{{ $paginator->firstItem() }}</span>
        -
        <span class="font-medium text-gray-700">{{ $paginator->lastItem() }}</span>
        dari
        <span class="font-medium text-gray-700">{{ $paginator->total() }}</span>
        data
    </div>

    {{-- Pagination buttons --}}
    <div class="flex items-center gap-1">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center justify-center w-9 h-9 rounded-md border border-gray-200 bg-white text-gray-300 cursor-not-allowed">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center justify-center w-9 h-9 rounded-md border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </a>
        @endif

        {{-- Pages --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-md text-sm text-gray-400">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-md border border-gray-900 bg-gray-900 text-white text-sm font-medium shadow-sm">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="inline-flex items-center justify-center w-9 h-9 rounded-md border border-gray-200 bg-white text-gray-700 text-sm font-medium hover:bg-gray-50 hover:border-gray-300 transition-colors">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center justify-center w-9 h-9 rounded-md border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        @else
            <span class="inline-flex items-center justify-center w-9 h-9 rounded-md border border-gray-200 bg-white text-gray-300 cursor-not-allowed">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            </span>
        @endif
    </div>
</nav>
@endif
