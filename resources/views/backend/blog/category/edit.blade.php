@php($staticDefaultLang = config('app.static_default_language'))
<nav>
	<div class="nav nav-tabs mb-4" id="nav-tab" role="tablist">
		@foreach($languages as $code => $langName)
			<a class="nav-item nav-link {{ is_default_lang($code,'active') }}" id="nav-home-tab" data-bs-toggle="tab" href="#category-nav-{{$code}}" role="tab"
			   aria-controls="nav-home" aria-selected="true">{{ $langName }}</a>
		@endforeach
	</div>
</nav>

<div class="tab-content" id="nav-tabContent">
	@foreach($blogCategory->trans_data as $lang => $transCategory)
		
		<div class="tab-pane fade {{ is_default_lang($lang,' show active') }}" id="category-nav-{{$lang}}" role="tabpanel" aria-labelledby="nav-home-tab">
			<form method="POST" action="{{ route('admin.blog.category.update', $blogCategory->id) }}" enctype="multipart/form-data">
				@method('PUT')
				@csrf
				
				<input type="hidden" name="lang" value="{{ $lang }}">
				<div class="row">
					<div class="col-md-12 mb-3">
						<div>
							<label for="category_name">{{ __('Category Name') }}</label>
							<input class="form-control" name="category_name" id="category_name" value="{{ $transCategory->name }}" type="text" placeholder="Enter category name">
						</div>
					</div>
					@if($lang == $staticDefaultLang)
						<div class="col-md-6 mb-3 mt-2">
							<div>
								<div class="form-check form-switch">
									<label class="form-check-label" for="category-status">{{ __('Category Status') }}</label>
									<input class="form-check-input" type="checkbox" @checked($blogCategory->is_active) value="1" name="is_active" id="category-status">
								</div>
							</div>
						</div>
					@endif
				</div>
				
				<div class="mt-3">
					<button class="btn btn-gray-800 mt-2 animate-up-2" type="submit">{{ __('Update Now') }}</button>
				</div>
			</form>
		</div>
	@endforeach
</div>
