<nav class="pagination-container" role="navigation" aria-label="Pagination Navigation">
    <div class="pagination-left">
        <div class="pagination-info">
            Menampilkan <span>{{ $paginator->firstItem() ?? 0 }}</span> - <span>{{ $paginator->lastItem() ?? 0 }}</span> dari <span>{{ $paginator->total() }}</span> data
        </div>
        
        <div class="pagination-per-page">
            <select onchange="window.location.href = this.value" class="form-control form-control-sm" data-no-custom="true" style="min-width: 140px;">
                @foreach([5, 10, 25, 50] as $size)
                    <option value="{{ request()->fullUrlWithQuery(['per_page' => $size, 'page' => 1]) }}" {{ request('per_page', 5) == $size ? 'selected' : '' }}>
                        {{ $size }} data per hal
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="pagination-links">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="pagination-item disabled" aria-disabled="true">
                <i class="fas fa-chevron-left"></i> Prev
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="pagination-item" rel="prev">
                <i class="fas fa-chevron-left"></i> Prev
            </a>
        @endif

        {{-- Pagination Elements --}}
        <div class="pagination-numbers">
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="pagination-item disabled" aria-disabled="true">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="pagination-item active" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="pagination-item">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="pagination-item" rel="next">
                Next <i class="fas fa-chevron-right"></i>
            </a>
        @else
            <span class="pagination-item disabled" aria-disabled="true">
                Next <i class="fas fa-chevron-right"></i>
            </span>
        @endif
    </div>
</nav>
