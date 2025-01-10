<div class="modal fade" id="new-nav-modal" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h2 class="h6 modal-title">{{ __('Navigation Create') }}</h2>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-12 col-xl-12">
						<form method="POST" action="{{ route('admin.navigation.site.store') }}" enctype="multipart/form-data">
							@csrf

							<div class="row">
								<div class="col-md-12 mb-3">
									<div>
										<label for="first_name">{{ __('Navigation Name') }}</label>
										<input class="form-control" name="navigation_name" id="first_name"  type="text" placeholder="Enter Navigation name" required>
									</div>
								</div>
							</div>

							<div class="row ">
								<div class="col-md-12 mb-3">
									<div>
										<label for="navigation-display">{{ __('Navigation Display') }}</label>
										<select class="form-select" name="navigation_display" id="navigation-display" aria-label="Default select example">
											@foreach(\App\Constants\Navigation::DISPLAY as $display)
												<option value="{{ $display }}">{{ ucwords($display) }}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="col-md-12 mb-3">
									<div>
										<label for="target-link">{{ __('Target Link') }}</label>
										<select class="form-select" name="target_link" id="target-link" aria-label="Default select example">
											@foreach(\App\Constants\Navigation::TARGET as $name => $target)
												<option value="{{ $target }}">{{ $name }}</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12 mb-3">
									<div class="page-list">
										<label for="page-list">{{ __('Page Select') }}</label>
										<select class="form-select" name="page_id" id="page-list" aria-label="Default select example">
											@foreach($pages as $page)
												<option value="{{ $page->id }}">{{ $page->title }}</option>
											@endforeach
										</select>
									</div>
									<div class="custom-url-input" hidden>
										<label for="custom-url">{{ __('Custom Url') }}</label>
										<input class="form-control" name="custom_url" id="custom-url"  type="text" placeholder="Enter Custom Url">
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6 mb-3 mt-2">
									<div>
										<div class="form-check form-switch">
											<label class="form-check-label" for="is-custom-url">{{ __('Custom Url') }}</label>
											<input class="form-check-input is-custom-url" type="checkbox" value="active" name="is_custom_url"  id="is-custom-url">
										</div>
									</div>
								</div>
								<div class="col-md-6 mb-3 mt-2">
									<div>
										<div class="form-check form-switch">
											<label class="form-check-label" for="nav-status">{{ __('Navigation Status') }}</label>
											<input class="form-check-input" type="checkbox" checked value="1" name="status"  id="nav-status">
										</div>
									</div>
								</div>

							</div>

							<div class="mt-3">
								<button class="btn btn-info mt-2" type="submit"> <x-icon name="check" height="20" /> {{ __('Save Now') }}</button>
							</div>
						</form>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
