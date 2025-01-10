<div class="modal fade" id="new-lang-modal" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h2 class="h6 modal-title">{{ __('Create Language') }}</h2>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-12 col-xl-12">
						<form method="POST" action="{{ route('admin.language.store') }}">
							@csrf
							
							<div class="row">
								<div class="col-md-12 mb-3">
									<div>
										<label for="language_name">{{ __('Language Name') }}</label>
										<input class="form-control" name="language_name" id="language_name"  type="text" placeholder="EX: English" required>
									</div>
								</div>
								<div class="col-md-12 mb-3">
									<div>
										<label for="language_code">{{ __('Language Code') }}</label>
										<input class="form-control" name="language_code" id="language_code"  type="text" placeholder="EX: en" required>
									</div>
								</div>
							</div>
							
							<div class="row">
							
								<div class="col-md-4 mb-3 mt-2">
									<div>
										<div class="form-check form-switch">
											<label class="form-check-label" for="default">{{ __('Default') }}</label>
											<input class="form-check-input" type="checkbox" value="1" name="is_default"  id="default">
										</div>
									</div>
								</div>
								<div class="col-md-4 mb-3 mt-2">
									<div>
										<div class="form-check form-switch">
											<label class="form-check-label" for="rtl">{{ __('Rtl') }}</label>
											<input class="form-check-input" type="checkbox" value="1" name="is_rtl"  id="rtl">
										</div>
									</div>
								</div>
								<div class="col-md-4 mb-3 mt-2">
									<div>
										<div class="form-check form-switch">
											<label class="form-check-label" for="status">{{ __('Status') }}</label>
											<input class="form-check-input" type="checkbox" checked value="1" name="status"  id="status">
										</div>
									</div>
								</div>
							
							</div>
							
							<div class="mt-3">
								<button class="btn btn-gray-800 mt-2 animate-up-2" type="submit">{{ __('Save Now') }}</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		
		</div>
	</div>
</div>
