@extends('backend.layouts.app')
@section('title')
	{{  __('Blog Category Manage') }}
@endsection
@section('content')
	<div class="py-4">
		<div class="d-flex justify-content-between w-100 flex-wrap">
			<div class="mb-3 mb-lg-0">
				<h1 class="h4">{{  __('Blog Category Manage') }}</h1>
			</div>
			<div class="btn-toolbar  mb-md-0 mb-2 ">
				<button type="button" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#new-category-modal">
					<x-svg i="plus"/>
					{{ __('Add New') }}
				</button>
			</div>

		</div>
	</div>
	<div class="card border-0 shadow mb-4">
		<div class="table-responsive">
			<table class="table table-centered table-nowrap mb-0 rounded table-hover align-items-center">
				<thead>
				<tr>
					<th class="border-bottom">{{ __('Name') }}</th>
					<th class="border-bottom">{{ __('Status') }}</th>
					<th class="border-bottom">{{ __('Created At') }}</th>
					<th class="border-bottom">{{ __('Action') }}</th>
				</tr>
				</thead>
				<tbody>
				@foreach($blogCategories as $category)
					<tr>
						<td><span class="fw-bold">{{ _tr( $category->name)  }}</span></td>
						<td><span class="fw-bold text-{{ $category->is_active ? 'success' : 'danger' }}">{{ $category->is_active ? 'Active' : 'Inactive' }}</span></td>
						<td><span class="fw-bold"> {{ $category->created_at->diffForHumans() }}</span></td>
						<td>
								<span data-id="{{ $category->id }}" class="edit">
										<x-svg i="edit"/>
								</span>

							<span data-id="{{ $category->id }}" class="delete">
										<x-svg i="delete"/>
								</span>
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
			@if($blogCategories->count() == 0)
				<h4 class="text-center text-muted py-3">{{ __('No Data Available') }}</h4>
			@endif
		</div>
	</div>

	{{-- New Nav Modal --}}
	@include('backend.blog.partial._new_category_modal')

	{{-- Edit Modal --}}
	@include('backend.blog.partial._edit_category_modal')

@endsection
@section('script')
	<script>
        $(document).ready(function () {

            $('.edit').on('click', function () {
                'use strict';
                var id = $(this).data('id');
                $('#edit-category-modal').modal('show');

                $.ajax({
                    url: "{{ route('admin.blog.category.edit',['category' => ':id']) }}".replace(':id', id),
                    type: "GET",
                    data: {id: id},
                    success: function (data) {
                        $('#edit-category-append').html(data);
                    }
                })
            });

            $('.delete').on('click', function () {
                'use strict';
                let id = $(this).data('id');
                let url = "{{ route('admin.blog.category.destroy', ['category' => ':id']) }}".replace(':id', id);
                deleteItem(url)
            });
        });
	</script>
@endsection
