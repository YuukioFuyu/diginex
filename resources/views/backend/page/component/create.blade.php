@extends('backend.layouts.app')
@section('title')
	{{ __('Create Component') }}
@endsection
@section('content')
	<div class="py-4">
		<div class="d-flex justify-content-between w-100 flex-wrap">
			<div class="mb-3 mb-lg-0">
				<h1 class="h4">{{ __('Create Text Component') }}</h1>
			</div>
			<div class="btn-toolbar  mb-md-0 mb-2 ">
				<a href="{{ route('admin.page.component.index') }}" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center">
					<x-svg i="back"/>
					{{ __('Back') }}
				</a>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12 col-xl-12">
			<div class="card card-body border-0 shadow mb-4">
				<h2 class="h5 mb-4">{{ __('Dynamic Component') }}</h2>
				<form method="POST" action="{{ route('admin.page.component.store') }}" enctype="multipart/form-data">
					@csrf
					<div class="row">
						<div class="col-md-6 mb-3">
							<div>
								<label for="icon">{{ __('Component Icon') }}</label>
								<x-img-up name="icon"/>
							</div>
						</div>
					</div>
					<div class="row">
						
						<div class="col-md-6 mb-3">
							<div>
								<label for="name">{{ __('Component Name') }}</label>
								<input class="form-control" name="name" value="{{ old('name') }}" id="name" type="text" placeholder="Enter Component name" required>
							</div>
						</div>
						<div class="col-md-12 mb-3">
							<div>
								<label for="content">{{ __('Component Content') }}</label>
								<textarea class="form-control summernote" name="content" id="content" rows="3">{{ old('content') }}</textarea>
							</div>
						</div>
						<div class="col-md-6 mb-3 mt-2">
							<div>
								<div class="form-check form-switch">
									<label class="form-check-label" for="status">{{ __('Component Status') }}</label>
									<i data-bs-toggle="tooltip"
									   data-bs-placement="top"
									   title="{{ __('When status is active, component will be visible in Page Manager') }}"
									   class="mx-1 fa-solid fa-circle-info">
									</i>
									<input class="form-check-input" type="checkbox" value="1" checked name="status" id="status">
								</div>
							</div>
						</div>
					
					</div>
					<div class="mt-3">
						<button class="btn btn-gray-800 mt-2 animate-up-2" type="submit">{{ __( "Create Component") }}</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection