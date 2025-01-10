<div class="modal fade" id="new-social-modal" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h2 class="h6 modal-title">{{ __('Social Link Create') }}</h2>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-12 col-xl-12">
						<form method="POST" action="{{ route('admin.social.store') }}" enctype="multipart/form-data">
							@csrf
							
							<div class="row">
								<div class="col-md-6 mb-3">
									<div>
										<label for="name">{{ __('Name') }}</label>
										<input class="form-control" name="name" id="name" value="{{ old('name') }}"  type="text" placeholder="Enter Social name" required>
									</div>
								</div>
								<div class="col-md-6 mb-3">
									<div>
										<label for="icon_class">{{ __('Icon Class') }}</label><span class="badge bg-info mx-2"><a href="https://fontawesome.com/" class="text-white text-muted" target="_blank">{{ __('Font Awesome') }}</a></span>
										<input class="form-control" name="icon_class" id="icon_class"  value="{{ old('icon_class') }}" type="text" placeholder="Enter Icon Class name" required>
									</div>
								</div>
							</div>
							<div class="row ">
								<div class="col-md-12 mb-3">
									<div>
										<label for="link_target">{{ __('Link Target') }}</label>
										<select class="form-select" name="target" id="link_target" aria-label="Default select example">
											@foreach(\App\Constants\Navigation::TARGET as $name => $target)
												<option value="{{ $target }}">{{ ucwords($name) }}</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 mb-3">
									<div>
										<label for="url">{{ __('Social Url') }}</label>
										<input class="form-control" name="url" id="url"  value="{{ old('url') }}" type="text" placeholder="Enter Social Url" required>
									</div>
								</div>
								<div class="col-md-6 mb-3 mt-2">
									<div>
										<div class="form-check form-switch">
											<label class="form-check-label" for="status">{{ __('Status') }}</label>
											<input class="form-check-input" type="checkbox" value="1" checked name="status"  id="status">
										</div>
									</div>
								</div>
							
							</div>
							
							<div class="mt-3">
								<button class="btn btn-gray-800 mt-2 animate-up-2" type="submit">{{ __('Create Now') }}</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		
		</div>
	</div>
</div>
