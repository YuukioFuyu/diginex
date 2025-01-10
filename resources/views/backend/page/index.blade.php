@extends('backend.layouts.app')
@section('title')
	{{  __('Page Manager') }}
@endsection
@section('content')
	<div class="py-4">
		<div class="d-flex justify-content-between flex-wrap w-100">
			<div class="mb-3 mb-lg-0">
				<h1 class="h4">{{ __('Page Manager') }}</h1>
			</div>
			<div class="btn-toolbar mb-2 me-md-2 d-flex flex-wrap justify-content-end">
				<a href="{{ route('admin.page.site.create') }}" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center mb-2 mb-md-0 me-2">
					<x-svg i="plus"/>
					{{ __('Create Page') }}
				</a>
				<a href="{{ route('admin.page.setting-type', 'error_404') }}" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center mb-2 mb-md-0">
					<x-svg i="error"/>
					{{ __('404 Error Page Manage') }}
				</a>
			</div>
		</div>
	</div>


	<div class="card border-0 shadow mb-4">
		<div class="table-responsive rounded">
			<table class="table table-bordered  table-striped mb-0">
				<thead class="table-light">
				<tr>
					<th class="border-bottom">{{ __('Title') }}</th>
					<th class="border-bottom">{{ __('Slug') }}</th>
					<th class="border-bottom">{{ __('Component') }}</th>
					<th class="border-bottom">{{ __('Status') }}</th>
					<th class="border-bottom d-flex justify-content-center">{{ __('Action') }}</th>
				</tr>
				</thead>
				<tbody>
				@foreach($pages as $page)
					<tr>
						<td class="fw-bold">
							{{ $page->title }}
						</td>
						<td>
							@if($page->slug != '/')
								<a href="{{ route('admin.page.site.edit', $page->id) }}?page={{ $page->slug }}"
								   class="fw-bold text-primary">@if($page->slug != '/')/@endif{{ $page->slug }}</a>
							@else
								<span class="fw-bold text-primary">@if($page->slug != '/')/@endif{{ $page->slug }}</span>
							@endif
						</td>
						<td>
							<span class="badge bg-tertiary rounded-pill">{{ count(json_decode($page->component_id,true)) ?? 0 }}</span>
						</td>
						<td>
							<span class="badge bg-{{ __($page->is_active ? 'success' : 'danger') }}">{{ __($page->is_active ? 'ACTIVE' : 'INACTIVE') }}</span>
						</td>
						<td class="d-flex justify-content-center">
							<a href="{{ route('admin.page.site.edit', $page->id) }}" class="btn btn-sm btn-info me-2"><x-icon name="edit" height="20" />{{ __('Edit') }}</a>
							@if($page->slug != '/')
								<a href="javascript:void(0)" class="btn btn-sm btn-danger delete" data-url="{{ route('admin.page.site.destroy', $page->id) }}"><x-icon name="delete-3" height="20" />{{ __('Delete') }}</a>
							@else
								<a href="javascript:void(0)" class="btn btn-sm btn-danger disabled"><x-icon name="delete-3" height="20" />{{ __('Not Permitted Delete') }}</a>
							@endif
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endsection
