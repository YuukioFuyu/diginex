@extends('backend.layouts.app')
@section('title')
	{{  __('Social Links') }}
@endsection
@section('content')
	<div class="py-4">
		<div class="d-flex justify-content-between w-100 flex-wrap">
			<div class="mb-3 mb-lg-0">
				<h1 class="h4">{{  __('Social Links') }}</h1>
			</div>
			<div class="btn-toolbar  mb-md-0 mb-2 ">
				<button type="button" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#new-social-modal">
					<x-svg i="plus"/>
					{{ __('Add New') }}
				</button>
			</div>

		</div>
	</div>
	<div class="card border-0 shadow mb-4">
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-centered table-nowrap mb-0 rounded table-hover align-items-center">
					<thead class="thead-light">
					<tr>
						<th class="border-bottom">{{ __('Name') }}</th>
						<th class="border-bottom">{{ __('Icon Class') }}</th>
						<th class="border-bottom">{{ __('Url') }}</th>
						<th class="border-bottom">{{ __('Status') }}</th>
						<th class="border-bottom">{{ __('Action') }}</th>
					</tr>
					</thead>
					<tbody>
					@foreach($socials as $social)
						<tr>
							<td><span class="fw-bold">{{ $social->name }}</span></td>
							<td><span class="fw-bold">{{ $social->icon_class }}</span></td>
							<td><span class="fw-bold">{{ $social->url }}</span></td>
							<td><span class="fw-bold text-{{ $social->status ? 'success' : 'danger' }}">{{ $social->status ? 'Active' : 'Inactive' }}</span></td>
							<td>
								<span data-id="{{ $social->id }}" class="edit">
										<x-svg i="edit"/>
								</span>

								<span data-id="{{ $social->id }}" class="delete">
										<x-svg i="delete"/>
								</span>
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>
				@if($socials->count() == 0)
					<h4 class="text-center text-muted py-3">{{ __('No Data Available') }}</h4>
				@endif

			</div>
		</div>
	</div>

	{{-- New Nav Modal --}}
	@include('backend.social.partial._new_social_modal')

	{{--	{{-- New Nav Modal --}}--}}
	@include('backend.social.partial._edit_social_modal')

@endsection
@section('script')
	<script>
        $(document).ready(function () {

            $('.edit').on('click', function () {
                'use strict';
                var id = $(this).data('id');
                $('#edit-social-modal').modal('show');

                $.ajax({
                    url: "{{ route('admin.social.edit',['social' => ':id']) }}".replace(':id', id),
                    type: "GET",
                    data: {id: id},
                    success: function (data) {
                        $('#edit-social-form').attr('action', '{{ route('admin.social.update', ['social' => ':id']) }}'.replace(':id', id));
                        $('#edit-append').html(data);
                    }
                })
            });

            $('.delete').on('click', function () {
                'use strict';
                let id = $(this).data('id');
                let url = "{{ route('admin.social.destroy', ['social' => ':id']) }}".replace(':id', id);
                deleteItem(url)
            });
        });
	</script>
@endsection
