@extends('backend.layouts.app')
@section('title')
	{{  __(ucwords($type).' '.'Navigation') }}
@endsection
@section('content')
	<div class="py-4">
		<div class="d-flex justify-content-between w-100 flex-wrap">
			<div class="mb-3 mb-lg-0">
				<h1 class="h4">{{  __(ucwords($type).' '.'Navigation') }}</h1>
			</div>
			<div class="btn-toolbar  mb-md-0 mb-2 ">
				<a href="{{ route('admin.navigation.site.index') }}" type="button" class="btn btn-sm btn-info d-inline-flex align-items-center">
					<x-svg i="clipboard"/>
					{{ __('Navigation Manage') }}
				</a>
			</div>
		</div>
	</div>
	<div class="card border-0 shadow mb-4">
		<div class="table-responsive rounded">
			<form method="POST" action="{{ route('admin.navigation.position-update') }}">
				@csrf
				<input type="hidden" name="type" value="{{ $type }}">

				<table class="table table-bordered table-striped mb-0">
					<thead class="table-light">
					<tr>
						<th class="border-bottom">{{ __('Drag Navigation') }}</th>
						<th class="border-bottom">{{ __('Name') }}</th>
						<th class="border-bottom">{{ __('Slug') }}</th>
						<th class="border-bottom">{{ __('Status') }}</th>
						<th class="border-bottom">{{ __('Action') }}</th>
					</tr>
					</thead>
					<tbody id="navigation-drag">
					@foreach($navigations as $navigation)
						<tr class="navigation-item" draggable="true">
							<input type="hidden" name="navigation_ids[]" value="{{ $navigation->id }}">
							<td>
								<x-svg i="uil-drag" class="drag-handle"/>
							</td>
							<td class="fw-bold align-middle">{{ _tr($navigation->name) }}</td>
							<td class="fw-bold align-middle">{{ $navigation->slug }}</td>
							<td class="align-middle">
							<span class="badge bg-{{ $navigation->is_active ? 'success' : 'danger' }}">
								{{ $navigation->is_active ? 'Active' : 'Inactive' }}
							</span>
							</td>


							<td class="align-middle text-center">
								<a href="javascript:void(0)" data-url="{{ route('admin.navigation.position-remove',['id' => $navigation->id,'type' => $type]) }}" class="btn btn-sm btn-danger  delete">
									<x-icon name="delete-3" height="20" />{{ __('Delete') }}
								</a>
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>

				@if($navigations->count() == 0)
					<h4 class="text-center text-muted py-3">{{ __('No Data Available') }}</h4>
				@else
					<div class="m-3 text-end">
						<button class="btn btn-info mt-2" type="submit"> <x-icon name="check" height="20" /> {{ __('Update Now') }}</button>
					</div>
				@endif
			</form>
		</div>
	</div>

@endsection
@section('script')
	<script>
        $(document).ready(function () {
            'use strict';

            const navigationDrag = document.getElementById('navigation-drag');
            new Sortable(navigationDrag, {
                animation: 150,
                group: 'shared',
                ghostClass: 'blue-background-class'
            });
        });
	</script>
@endsection
