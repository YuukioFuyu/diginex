@extends('backend.layouts.app')
@section('title', __('Languages'))
@section('content')
	<div class="py-4">
		<div class="d-flex justify-content-between w-100 flex-wrap">
			<div class="mb-3 mb-lg-0">
				<h1 class="h4">{{  __('Languages Manage') }}</h1>
			</div>
			<div class="btn-toolbar  mb-md-0 mb-2 ">
				<a  href="{{ route('admin.language.sync-missing-keys') }}" class="btn btn-sm btn-info d-inline-flex align-items-center me-2">
					<x-svg i="sync"/>
					{{ __('Sync Missing Translation') }}
				</a>
				<button type="button" class="btn btn-sm btn-info d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#new-lang-modal">
					<x-svg i="plus"/>
					{{ __('Add New') }}
				</button>
			</div>
		</div>
	</div>
	<div class="card border-0 shadow mb-4">
		<div class="table-responsive rounded">
			<table class="table table-striped align-middle text-center">
				<thead class="table-light">
				<tr>
					<th>{{ __('Name') }}</th>
					<th>{{ __('Code') }}</th>
					<th>{{ __('Default') }}</th>
					<th>{{ __('Rtl') }}</th>
					<th>{{ __('Status') }}</th>
					<th>{{ __('Action') }}</th>
				</tr>
				</thead>
				<tbody>
				@forelse($languages as $language)
					<tr>
						<td>{{ $language->name }}</td>
						<td>{{ $language->code }}</td>
						<td>
                    <span class="fw-bold  badge bg-{{ $language->is_default ? 'success' : 'danger' }}">
                        {{ $language->is_default ? __('YES') : __('NO') }}
                    </span>
						</td>
						<td>
                    <span class="fw-bold  badge bg-{{ $language->is_rtl ? 'success' : 'danger' }}">
                        {{ $language->is_rtl ? __('YES') : __('NO') }}
                    </span>
						</td>
						<td>
                    <span class="fw-bold badge bg-{{ $language->status ? 'success' : 'danger' }}">
                        {{ $language->status ? __('ACTIVE') : __('INACTIVE') }}
                    </span>
						</td>
						<td class="d-flex justify-content-center">
							<a href="{{ route('admin.language.translate', $language->code) }}" class="btn btn-sm btn-tertiary me-2">
								<x-svg i="language" class="icon icon-sm "/>{{ __('Translate') }}
							</a>
							<button data-id="{{ $language->id }}" class="btn btn-sm btn-success text-white edit me-2">
								<x-svg i="edit" class="icon icon-sm " />{{ __('Edit') }}
							</button>
							@if($language->code != 'en')
								<button data-id="{{ $language->id }}" class="btn btn-sm btn-danger delete">
									<x-svg i="delete-3" class="icon icon-sm " />{{ __('Delete') }}
								</button>
							@endif
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="6" class="text-center">{{ __('No Data Available') }}</td>
					</tr>
				@endforelse
				</tbody>
			</table>
		</div>
	</div>

	{{-- New Lang Modal --}}
	@include('backend.languages.partial._new_lang_modal')

	{{-- Edit Lang Modal --}}
	@include('backend.languages.partial._edit_lang_modal')
@endsection
@section('script')
	<script>
        $(document).ready(function () {



            $('.edit').on('click', function () {
                'use strict';
                var id = $(this).data('id');
                $('#edit-lang-modal').modal('show');

                $.ajax({
                    url: "{{ route('admin.language.edit',['language' => ':id']) }}".replace(':id', id),
                    type: "GET",
                    data: {id: id},
                    success: function (data) {
                        $('#edit-lang-form').attr('action', '{{ route('admin.language.update', ['language' => ':id']) }}'.replace(':id', id));
                        $('#edit-lang-append').html(data);
                    }
                })
            });

            $('.delete').on('click', function () {
                'use strict';
                let id = $(this).data('id');
                let url = "{{ route('admin.language.destroy', ['language' => ':id']) }}".replace(':id', id);
                deleteItem(url)
            });
        });
	</script>
@endsection
