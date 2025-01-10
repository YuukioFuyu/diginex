@extends('backend.layouts.app')
@section('title')
	{{  __('Blog Manage') }}
@endsection
@section('content')
	<div class="py-4">
		<div class="d-flex justify-content-between w-100 flex-wrap">
			<div class="mb-3 mb-lg-0">
				<h1 class="h4">{{  __('Blog Manage') }}</h1>
			</div>
			<div class="btn-toolbar  mb-md-0 mb-2 ">
				<a href="{{ route('admin.blog.site.create') }}" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center">
					<x-svg i="plus" />
					{{ __('Add New') }}
				</a>
			</div>
		
		</div>
	</div>
	<div class="card border-0 shadow mb-4">
		<div class="table-responsive">
			<table class="table table-centered table-nowrap mb-0 rounded  align-items-center">
				<thead>
				<tr>
					<th class="border-bottom">{{ __('Cover') }}</th>
					<th class="border-bottom">{{ __('Title') }}</th>
					<th class="border-bottom">{{ __('Category') }}</th>
					<th class="border-bottom">{{ __('Status') }}</th>
					<th class="border-bottom">{{ __('Created At') }}</th>
					<th class="border-bottom">{{ __('Action') }}</th>
				</tr>
				</thead>
				<tbody>
				@foreach($blogs as $blog)
					<tr>
						<td><img class="table-img" src="{{ asset($blog->cover) }}" alt="{{ _tr($blog->title)  }}"></td>
						<td><span class="fw-bold">{{ _tr($blog->title) }}</span></td>
						<td><span class="fw-bold">{{ _tr($blog->category->name) }}</span></td>
						<td><span class="fw-bold text-{{ $blog->is_active ? 'success' : 'danger' }}">{{ $blog->is_active ? 'Active' : 'Inactive' }}</span></td>
						<td><span class="fw-bold"> {{ $blog->created_at->format('d M, Y') }}</span></td>
						<td>
							<a href="{{ route('admin.blog.site.edit', ['site' => $blog->id]) }}" class="edit">
								<x-svg i="edit"/>
							</a>
							<a href="{{ route('blog.details', ['slug' => $blog->slug]) }}" target="_blank" class="edit">
								<x-svg i="eye"/>
							</a>
							<span  data-id="{{ $blog->id }}" class="delete">
										<x-svg i="delete"/>
								</span>
						</td>
					</tr>
				@endforeach
				
				<tr>
					<td colspan="8">
						{{ $blogs->links() }}
					</td>
				</tr>
				
				
				</tbody>
			</table>
			@if($blogs->count() == 0)
				<h4 class="text-center text-muted py-3">{{ __('No Data Available') }}</h4>
			@endif
		</div>
	</div>
	
	
@endsection
@section('script')
	<script>
        $(document).ready(function() {
	        
            $('.delete').on('click', function() {
                'use strict';
                let id = $(this).data('id');
                let url = "{{ route('admin.blog.site.destroy', ['site' => ':id']) }}".replace(':id', id);
                deleteItem(url)
            });
        });
	</script>
@endsection
