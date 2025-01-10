@php use App\Constants\Component; @endphp
@extends('backend.layouts.app')
@section('title')
	{{  __('Component Page') }}
@endsection
@section('content')
	<div class="py-4">
		<div class="d-flex justify-content-between flex-wrap w-100">
			<div class="mb-2 mb-lg-0">
				<h1 class="h4">{{ __('Component Page') }}</h1>
			</div>
			<div class="btn-toolbar mb-1 mb-md-0 d-flex flex-wrap justify-content-end">
				<a href="{{ route('admin.page.component.create') }}" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center">
					<x-svg i="plus"/>
					{{ __('Create') }}
				</a>
			</div>
		</div>
	</div>
	
	<div class="card border-0 shadow mb-4">
		<div class="card-body">
			<form action="{{ route('admin.page.component.index') }}" method="get" class="mb-4">
				<div class="d-flex flex-column flex-md-row align-items-md-end justify-content-md-end">
					<div class="d-flex">
						<select class="form-select custom-select-200 mb-2 me-1  mb-md-0 me-md-2" name="component_display" id="component-display1" aria-label="Default select example">
							@foreach($display as $key => $value)
								<option @if($currentDisplay == $key) selected @endif value="{{ $key }}">{{ $value }}</option>
							@endforeach
						</select>
						<select class="form-select custom-select-200  mb-2 me-1 mb-md-0   me-md-2" name="component_category" id="component-category" aria-label="Default select example">
							<option value="all">{{ __('All Categories') }}</option>
							@foreach($categories as $category)
								<option @if($currentCategory == $category) selected @endif value="{{ $category }}">{{ ucfirst($category) }}</option>
							@endforeach
						</select>
					</div>
					<div class="btn-toolbar  mb-md-0">
						<button type="submit" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center">
							<x-svg i="filter"/>
							{{ __('Filter Now') }}
						</button>
					</div>
				</div>
			</form>
			
			
			@include('backend.page.component.partial._'.$currentDisplay)
		</div>
	</div>
@endsection
@section('script')
	<script>
        $(document).ready(function () {
            'use strict';

            const deleteElements = $('.delete');  // Cache the selector
            deleteElements.on('click', function (event) {
                event.preventDefault();
                const id = $(this).data('id');
                let url = '{{ route("admin.page.component.destroy", ["component" => ":id"]) }}'.replace(':id', id)
                deleteItem(url);
            });
        });
	</script>
@endsection