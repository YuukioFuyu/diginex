@extends('backend.layouts.app')
@section('title')
	{{  __('Navigation Manage') }}
@endsection
@section('content')

	<div class="py-4">
		<div class="d-flex justify-content-between w-100 flex-wrap">
			<div class="mb-3 mb-lg-0">
				<h1 class="h4">{{  __('Navigation Manager') }}</h1>
			</div>
			<div class="btn-toolbar  mb-md-0 mb-2 ">
				<button type="button" class="btn btn-sm btn-info d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#new-nav-modal">
					<x-svg i="plus"/>
					{{ __('Add New') }}
				</button>
			</div>

		</div>
	</div>
	<div class="card border-0 shadow mb-4">
		<div class="table-responsive rounded">
			<table class="table table-bordered table-striped mb-0">
				<thead class="table-light">
				<tr>
					<th class="border-bottom">{{ __('Name') }}</th>
					<th class="border-bottom">{{ __('Slug') }}</th>
					<th class="border-bottom">{{ __('Display') }}</th>
					<th class="border-bottom">{{ __('Status') }}</th>
					<th class="border-bottom d-flex justify-content-center">{{ __('Action') }}</th>
				</tr>
				</thead>
				<tbody>
				@foreach($navigations as $navigation)
					<tr>
						<td class="fw-bold">{{ _tr($navigation->name) }}</td>
						<td class="fw-bold">{{ $navigation->slug }}</td>
						<td class="fw-bold">{{ $navigation->display }}</td>
						<td>
						<span class="badge bg-{{ $navigation->is_active ? 'success' : 'danger' }}">
							{{ $navigation->is_active ? 'Active' : 'Inactive' }}
						</span>
						</td>
						<td class="d-flex justify-content-center">
							<a href="javascript:void(0)" data-id="{{ $navigation->id }}" class="btn btn-sm btn-info me-2 edit">
								<x-icon name="edit" height="20" />{{ __('Edit') }}
							</a>
							<a href="javascript:void(0)" data-url="{{ route('admin.navigation.site.destroy', $navigation->id) }}" class="btn btn-sm btn-danger delete">
								<x-icon name="delete-3" height="20" />{{ __('Delete') }}
							</a>
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>

			@if($navigations->count() == 0)
				<h4 class="text-center text-muted py-3">{{ __('No Data Available') }}</h4>
			@endif
		</div>
	</div>

	{{-- New Nav Modal --}}
	@include('backend.navigation.partial._new_nav_modal')

	{{-- New Nav Modal --}}
	@include('backend.navigation.partial._edit_nav_modal')

@endsection
@section('script')
	<script>
        $(document).ready(function () {

            $(document).on('change', '.is-custom-url', function () {
                'use strict';
                var isChecked = this.checked;
                $('.page-list').prop('hidden', isChecked);
                $('.custom-url-input').prop('hidden', !isChecked);
                $("[name='custom_url']").prop('required', isChecked);
            });

            $('.edit').on('click', function () {
                'use strict';
                var id = $(this).data('id');
                $('#edit-nav-modal').modal('show');

                $.ajax({
                    url: "{{ route('admin.navigation.site.edit',['site' => ':id']) }}".replace(':id', id),
                    type: "GET",
                    data: {id: id},
                    success: function (data) {
                        $('#edit-append').html(data);
                    }
                })
            });
        });
	</script>
@endsection
