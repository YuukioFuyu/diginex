@if ($paginator->hasPages())
	<div class="pegi justify-content-center mt-60">
		{{-- Previous Page Link --}}
		@if (!$paginator->onFirstPage())
		<a href="{{ $paginator->previousPageUrl() . '&' . http_build_query(request()->except('page')) }}" class="{{ $paginator->onFirstPage() ? 'active disabled' : '' }}">&lsaquo;</a>
		@endif
		
		{{-- Pagination Elements --}}
		@foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
			{{-- Add the existing query parameters to the pagination links --}}
			<a href="{{ $url . (str_contains($url, '?') !== false ? '&' : '?') . http_build_query(request()->except('page')) }}" class="{{ $page == $paginator->currentPage() ? 'active' : '' }}">{{ $page }}</a>
		@endforeach
		
		{{-- Next Page Link --}}
		@if ($paginator->hasMorePages())
		<a href="{{ $paginator->nextPageUrl() . '&' . http_build_query(request()->except('page')) }}" class="{{ !$paginator->hasMorePages() ? 'disabled' : '' }}">&rsaquo;</a>
		@endif
	</div>
@endif

