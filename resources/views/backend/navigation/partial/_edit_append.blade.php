@php($staticDefaultLang = config('app.static_default_language'))
<nav>
	<div class="nav nav-tabs mb-4" id="nav-tab" role="tablist">
		@foreach($languages as $code => $langName)
			<a class="nav-item nav-link {{ is_default_lang($code,'active') }}" id="nav-home-tab" data-bs-toggle="tab" href="#navigation-nav-{{$code}}" role="tab"
			   aria-controls="nav-home" aria-selected="true">{{ $langName }}</a>
		@endforeach
	</div>
</nav>
<div class="tab-content" id="nav-tabContent">
	@foreach($navigation->trans_data as $langName => $value)

		<div class="tab-pane fade {{ is_default_lang($langName,'show active') }}" id="navigation-nav-{{$langName}}" role="tabpanel" aria-labelledby="nav-home-tab">
			<form method="POST"  action="{{ route('admin.navigation.site.update', $navigation->id) }}" enctype="multipart/form-data">
				@method('PUT')
				@csrf
				<input type="hidden" name="lang" value="{{ $langName }}">
				<div class="row">
					<div class="col-md-12 mb-3">
						<div>
							<label for="navigation_name">{{ __('Navigation Name') }}</label>
							<input class="form-control" name="navigation_name" id="navigation_name" value="{{ $value->name }}" type="text" placeholder="Enter Navigation name">
						</div>
					</div>
				</div>
				@if($langName == $staticDefaultLang)
					<div class="row ">
						<div class="col-md-12 mb-3">
							<div>
								<label for="navigation-display">{{ __('Navigation Display') }}</label>
								<select class="form-select" name="navigation_display" id="navigation-display" aria-label="Default select example">
									@foreach(\App\Constants\Navigation::DISPLAY as $display)
										<option value="{{ $display }}" @selected($display == $navigation->display)>{{ ucwords($display) }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-md-12 mb-3">
							<div>
								<label for="target-link">{{ __('Target Link') }}</label>
								<select class="form-select" name="target_link" id="target-link" aria-label="Default select example">
									@foreach(\App\Constants\Navigation::TARGET as $name => $target)
										<option value="{{ $target }}" @selected($target == $navigation->target)>{{ $name }}</option>
									@endforeach
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 mb-3">
							<div class="page-list" @if($navigation->page_id == null) hidden @endif>
								<label for="page-list">{{ __('Page Select') }}</label>
								<select class="form-select" name="page_id" id="page-list" aria-label="Default select example">
									@foreach($pages as $page)
										<option @selected($page->id == $navigation->page_id) value="{{ $page->id }}">{{ $page->title }}</option>
									@endforeach
								</select>
							</div>
							<div class="custom-url-input" @if($navigation->page_id != null) hidden @endif>
								<label for="custom-url">{{ __('Custom Url') }}</label>
								<input class="form-control" name="custom_url" value="{{ $navigation->slug }}" id="custom-url" type="text" placeholder="Enter Custom Url">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 mb-3 mt-2">
							<div>
								<div class="form-check form-switch">
									<label class="form-check-label" for="is-custom-url">{{ __('Custom Url') }}</label>
									<input class="form-check-input is-custom-url" type="checkbox" @checked($navigation->page_id == null) value="active" name="is_custom_url" id="is-custom-url">
								</div>
							</div>
						</div>
						<div class="col-md-6 mb-3 mt-2">
							<div>
								<div class="form-check form-switch">
									<label class="form-check-label" for="nav-status">{{ __('Navigation Status') }}</label>
									<input class="form-check-input" type="checkbox" @checked($navigation->is_active) value="1" name="status" id="nav-status">
								</div>
							</div>
						</div>

					</div>
				@endif
				<div class="mt-3">
					<button class="btn btn-info mt-2 animate-up-2" type="submit"> <x-icon name="check" height="20" /> {{ __('Update Now') }}</button>
				</div>
			</form>

		</div>
	@endforeach
</div>
