@extends('backend.layouts.app')
@section('title')
	{{ __('404 Error Page') }}
@endsection
@section('content')
	<div class="py-4">
		<div class="d-flex justify-content-between w-100 flex-wrap">
			<div class="mb-3 mb-lg-0">
				<h1 class="h4">{{ __('404 Error Page Manage') }}</h1>
			</div>
			<div class="btn-toolbar  mb-md-0 mb-2  mx-1">
				<a href="{{ route('admin.page.site.index') }}" type="button" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center">
					<x-svg i="page"/>
					{{ __('Page Manage') }}
				</a>
			</div>
		</div>
	</div>
	<div class="row">
		@php
			$footer = config('setting_fields.error_404');
		@endphp
		<div class="col-12 col-xl-12">
			<div class="card card-body border-0 shadow mb-4">
				<h2 class="h5 mb-4">{{ __($footer['title']) }}</h2>
				<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
					@csrf
					<input type="hidden" name="section" value="error_404">
					<input type="hidden" name="info_message" value="{{ __('404 Page Updated Successfully') }}">
					<div class="row">
						@foreach($footer['elements'] as $key => $field)
							@includeIf('backend.settings.fields.'.$field['type'], ['field' => $field,'class' => $field['class']])
						@endforeach
					</div>
					<div class="mt-3">
						<button class="btn btn-gray-800 mt-2 animate-up-2" type="submit">{{ __('Update 404 Error Page ') }}</button>
					</div>
				</form>
			</div>
		</div>
	</div>

@endsection