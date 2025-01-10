@extends('backend.layouts.app')

@section('title', __('Custom ').ucwords($data->type))

@push('style')
	<link href="{{ asset('backend/css/codemirror.css') }}" rel='stylesheet'>
	<link href="{{ asset('backend/css/ayu-dark.css') }}" rel='stylesheet'>
@endpush

@section('content')
	<div class="py-4">
		<div class="d-flex justify-content-between w-100 flex-wrap">
			<div class="mb-3 mb-lg-0">
				<h1 class="h4">{{ __('Custom ').ucwords($data->type) }}</h1>
			</div>
			<div class="btn-toolbar  mb-md-0 mb-2 ">
				<a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center">
					<x-svg i="back"/>
					{{ __('Back') }}
				</a>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12 col-xl-12">
			<div class="card card-body border-0 shadow mb-4">
				<p class="text-muted small"><x-svg i="info"/> {{ $data->notify }}</p>
				
				<form method="POST" action="{{ route('admin.custom.update.type') }}" enctype="multipart/form-data">
					@csrf
					<input type="hidden" name="type" value="{{ $data->type }}">
					<div class="row mt-2">
						<div class="col-md-12 mb-3">
							<label for="css">{{ __('Write ').ucwords($data->type) }}</label>
							<textarea name="value" id="css" class="form-control editorContainer" rows="10">{{ $data->value }}</textarea>
						</div>
						
						<div class="col-md-6 mb-3 mt-2">
							<div class="form-check form-switch">
								<label class="form-check-label" for="status">{{ __('Status') }}</label>
								<input class="form-check-input" type="checkbox" value="1"  @checked($data->is_active) name="is_active" id="status">
							</div>
						</div>
					</div>
					<div class="mt-3">
						<button class="btn btn-gray-800 mt-2 animate-up-2" type="submit">{{ __('Update Now') }}</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection

@push('script')
	<script src="{{ asset('backend/js/codemirror.js') }}"></script>
	<script src="{{ asset('backend/js/code-css.js') }}"></script>
	<script>
        (() => {
            'use strict';
            var editorContainer = document.querySelector('.editorContainer')

            var editor = CodeMirror.fromTextArea(editorContainer, {
                lineNumbers: true,
                mode: 'css',
                theme: 'ayu-dark',
            });
        })();
	</script>
@endpush
