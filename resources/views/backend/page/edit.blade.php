@extends('backend.layouts.app')
@section('title')
	{{ $page ->title . ' ' .__('Manage') }}
@endsection
@section('content')

	<div class="py-4">
		<div class="d-flex justify-content-between w-100 flex-wrap">
			<div class="mb-3 mb-lg-0">
				<h1 class="h4">{{ $page ->title . ' ' .__('Manage') }}</h1>
			</div>
			<div class="btn-toolbar  mb-md-0 mb-2 mb-md-0">
				<a href="{{ route('admin.page.site.index') }}" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center">
					<x-svg i="back"/>
					{{ __('Back') }}
				</a>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12 col-xl-6">
			<div class="card card-body border-0 shadow mb-4">

				<div class="d-flex justify-content-between flex-wrap w-100 mb-3">
					<div class="mb-1 mb-lg-0">
						<h1 class="h4">{{ __('Component') }}</h1>
					</div>
					<div class="btn-toolbar mb-0 d-flex flex-wrap justify-content-end">
						<select class="form-select custom-select-200 me-2 mb-2 mb-md-0" name="component_category" id="filterComponentCategory" aria-label="Default select example">
							<option value="all">{{ __('All Components') }}</option>
							@foreach($componentCategory as $category)
								<option value="{{ $category }}">{{ ucfirst($category) }}</option>
							@endforeach
						</select>
						<a href="{{ route('admin.page.component.index') }}" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center mb-2 mb-md-0">
							<x-svg i="component2"/>
							{{ __('Manage') }}
						</a>
					</div>
				</div>

				<div class="sortable-list">
					<button class="btn btn-info w-100 loading" type="button" disabled hidden="">
						<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
						{{ __('Loading') }}...
					</button>
					<span id="componentList">
						@include('backend.page.component.partial._filter_component')
					</span>

				</div>

			</div>
		</div>
		<div class="col-12 col-xl-6">
			<div class="card card-body border-0 shadow mb-4">
				<form method="POST" action="{{ route('admin.page.site.update', $page->id) }}">
					@method('PUT')
					@csrf
					<div class="d-flex justify-content-between flex-wrap w-100 mb-3">
						<div class="mb-2 mb-lg-0">
							<h2 class="h5 mb-2">{{ __('Update Page') }}</h2>
						</div>
						<div class="d-flex flex-column flex-sm-row flex-wrap align-items-start">
							<div class="form-check form-switch mb-2 me-sm-3">
								<label class="form-check-label me-2" for="is_breadcrumb">{{ __('Page Breadcrumb') }}</label>
								<input class="form-check-input" type="checkbox" value="1" name="is_breadcrumb" @checked($page->is_breadcrumb) id="is_breadcrumb">
							</div>
							<div class="form-check form-switch mb-2 ms-sm-3">
								<label class="form-check-label" for="flexSwitchCheckDefault">{{ __('Page Status') }}</label>
								<input class="form-check-input" type="checkbox" value="1" name="is_active" @checked($page->is_active) id="flexSwitchCheckDefault">
							</div>
						</div>
					</div>

					<div class="sortable-list" id="pageComponent">
						@foreach($pageComponents as $component)
							<div class="item" draggable="true" data-index="{{ $component->id }}">
								<input type="hidden" name="component[]" value="{{ $component->id }}">
								<div class="details">
									<img src="{{ asset($component->icon) }}">
									<span>{{ $component->name }}</span>
								</div>

								<span class="component-manage" title="{{ __('Manage Component') }}" data-bs-toggle="tooltip" data-bs-placement="top">
								<a href="{{ route('admin.page.component.edit', ['component' => $component->id,'page' => $page->id]) }}" > <x-svg
										i="setting-2"/> </a>
								</span>

								<span class="manage-drag" data-id="{{$component->id}}" title="{{ __('Move to Component List') }}" data-bs-toggle="tooltip" data-bs-placement="top">
									<span class="drag-svg"><x-svg i="delete"/> </span>
								</span>
							</div>
						@endforeach
					</div>
					<div class="row">
						<div class="col-md-6 mb-3">
							<div class="form-group">
								<label for="page_title">{{ __('Page Title') }}</label>
								<input class="form-control" name="page_title" value="{{ old('page_title') ?? $page->title }}" id="page_title" type="text" required>
							</div>
						</div>
						<div class="col-md-6 mb-3">
							<div class="form-group">
								<label for="page_slug">{{ __('Page Slug') }}</label>
								<input class="form-control  @if($page->slug != '/') page_slug @endif" @if($page->slug == '/') readonly @endif name="page_slug"
								       value="{{ old('page_slug') ?? $page->slug }}" id="page_slug" type="text" required>
							</div>
						</div>

						{{--Page Seo Part Without Landing Page--}}
						@if($page->slug != '/')
							<div class="row">
								<div class="col-md-6 mb-3">
									<div class="form-group">
										<label for="meta_title">{{ __('Meta Title') }}</label>
										<input class="form-control" name="meta_title" value="{{ old('meta_title') ?? $page->meta_title }}" id="meta_title" type="text">
									</div>
								</div>
								<div class="col-md-6 mb-3">
									<div class="form-group">
										<label for="meta_keywords">{{ __('Meta Keywords') }} <small class="sm-font text-muted">({{ __('comma or enter separated') }})</small></label>
										<input class="form-control tags-evs" name="tags" value="{{ old('meta_keywords') ?? $page->meta_keywords }}" id="meta_keywords" type="text">

									</div>
								</div>
								<div class="col-md-12 mb-3">
									<div class="form-group">
										<label for="meta_description">{{ __('Meta Description') }}</label>
										<input class="form-control" name="meta_description" value="{{ old('meta_description') ?? $page->meta_description }}" id="page_description" type="text">
									</div>
								</div>
							</div>
						@endif

					</div>
					<div class="mt-3">
						<button class="btn btn-gray-800 mt-2 animate-up-2" type="submit">{{ __('Update Page') }}</button>
					</div>
				</form>
			</div>
		</div>
	</div>

@endsection
@push('script')
	@include('backend.page.partial._page_script');
@endpush
