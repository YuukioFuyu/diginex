@extends('backend.layouts.app')
@section('title')
	{{ __('Blog Create') }}
@endsection
@section('content')
	
	<div class="py-4">
		<div class="d-flex justify-content-between w-100 flex-wrap">
			<div class="mb-3 mb-lg-0">
				<h1 class="h4">{{ __('Blog Update') }}</h1>
			</div>
			<div class="btn-toolbar  mb-md-0 mb-2 ">
				<a href="{{ route('admin.blog.site.index') }}" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center">
					<x-svg i="back"/>
					{{ __('Back') }}
				</a>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12 col-xl-12">
			<div class="card card-body border-0 shadow mb-4">
				
				
				<nav>
					<div class="nav nav-tabs mb-4" id="nav-tab" role="tablist">
						@foreach($languages as $langCode => $langName)
							<a class="nav-item nav-link {{ is_default_lang($langCode, 'active') }}" id="nav-home-tab" data-bs-toggle="tab" href="#blog-nav-{{$langCode}}"
							   role="tab"
							   aria-controls="nav-home" aria-selected="true">{{ $langName }}</a>
						@endforeach
					</div>
				</nav>
				
				<div class="tab-content" id="nav-tabContent">
					@foreach($blog->trans_data as $code => $value)
						<div class="tab-pane fade {{ is_default_lang($code, 'active show') }}" id="blog-nav-{{$code}}" role="tabpanel"
						     aria-labelledby="nav-home-tab">
							<form method="POST" action="{{ route('admin.blog.site.update', ['site' => $blog->id]) }}" enctype="multipart/form-data">
								@csrf
								@method('PUT')
								<input type="hidden" name="lang" value="{{ $code }}">
								<div class="row mt-2">
									@if($code == config('app.static_default_language'))
										<div class="col-md-12 mb-3">
											<div class="form-group">
												<label for="title">{{ __('Cover Image') }}</label>
												<x-img-up name="cover" :old="$blog->cover ?? demoImage()"/>
											</div>
										</div>
									@endif
									
									<div class="col-md-{{ is_default_lang($code, '6', '12') }} mb-3">
										<div class="form-group">
											<label for="title">{{ __('Title') }}</label>
											<input class="form-control" name="title" id="title" type="text" value="{{ old('title',$value->title) }}" required>
										</div>
									</div>
									@if($code == config('app.static_default_language'))
										<div class="col-md-6 mb-3">
											<div class="form-group">
												<label for="category">{{ __('Category') }}</label>
												<select class="form-select" name="category_id" aria-label="Default">
													@foreach($categories as  $category)
														<option @selected($blog->category_id == $category->id) value="{{ $category->id }}">{{ Str::upper(_tr($category->name))  }}</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class="col-md-6 mb-3">
											<div class="form-group">
												<label for="slug">{{ __('Slug') }}</label>
												<input class="form-control slug" name="slug" id="slug" type="text" value="{{ old('slug',$blog->slug) }}">
											</div>
										</div>
										<div class="col-md-6 mb-3">
											<div class="form-group">
												<label for="tag">{{ __('Tag') }}</label>
												<input class="form-control tags-evs" name="tag" id="tag" value="{{ old('tags',$blog->tag) }}" type="text">
											</div>
										</div>
									@endif
									<div class="col-md-12 mb-3">
										<div class="form-group">
											<label for="summary">{{ __('Summary') }}</label>
											<textarea class="form-control" name="summary" id="summary" type="text">{{ old('summary',$value->summary) }}</textarea>
										</div>
									</div>
									<div class="col-md-12 mb-3">
										<div class="form-group">
											<label for="content">{{ __('Content') }}</label>
											<textarea class="form-control summernote" name="content" id="content" type="text">{{ old('content',$value->content) }}</textarea>
										</div>
									</div>
									@if($code == config('app.static_default_language'))
										<div class="col-md-6 mb-3 mt-2">
											<div class="form-check form-switch">
												<label class="form-check-label" for="blog-status">{{ __('Blog Status') }}</label>
												<input class="form-check-input" type="checkbox" @checked($blog->is_active)  value="1" name="is_active" id="blog-status">
											</div>
										</div>
									@endif
								</div>
								<div class="mt-3">
									<button class="btn btn-gray-800 mt-2 animate-up-2" type="submit">{{ __('Update Blog') }}</button>
								</div>
							</form>
						</div>
					@endforeach
				</div>
			
			</div>
		</div>
	</div>

@endsection
@section('script')
	<script>
        $(document).ready(function () {
            'use strict';
            $('#title').on('input', function () {
                let title = $(this).val();
                let slug = generateSlug(title);
                $('.slug').val(slug);
            });
        });
	
	</script>
@endsection