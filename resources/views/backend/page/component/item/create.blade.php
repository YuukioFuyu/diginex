@extends('backend.layouts.app')
@section('title')
	{{ __('Create New Item') }}
@endsection
@section('content')
	<div class="py-4">
		<div class="d-flex justify-content-between w-100 flex-wrap">
			<div class="mb-3 mb-lg-0">
				<h1 class="h4">{{ __('Create New Item') }}</h1>
			</div>
			<div class="btn-toolbar  mb-md-0 mb-2 ">
				<a href="{{ route('admin.page.component.edit', $componentId) }}" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center">
					<x-svg i="back"/>
					{{ __('Back') }}
				</a>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12 col-xl-12">
			<div class="card card-body border-0 shadow mb-4">
				<h2 class="h5 mb-4">{{ __('Create New Item') }}</h2>
				
				@include('backend.page.component.partial._new_form_data')
			</div>
		</div>
	</div>
@endsection