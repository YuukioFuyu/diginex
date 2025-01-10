<div class="modal fade" id="send-mail-modal" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h2 class="h6 modal-title">{{ __('Send Mail') }}</h2>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-12 col-xl-12">
						<form method="POST" action="{{ route('admin.mail-send') }}">
							@csrf
							<input type="hidden" name="id" id="subscriber_id">
							<div class="row">
								<div class="col-md-12 mb-3">
									<div>
										<label for="title">{{ __('Title') }}</label>
										<input class="form-control" name="title" id="title" value="{{ old('title') }}"  type="text" placeholder="Enter Mail Title" required>
									</div>
								</div>
								<div class="col-md-12 mb-3">
									<div>
										<label for="description">{{ __('Message') }}</label>
										<textarea class="form-control" name="message" id="description" type="text" placeholder="Enter Mail Message" required>{{ old('message') }}</textarea>
									</div>
								</div>
							</div>


							<div class="mt-3">
								<button class="btn btn-gray-800 mt-2 animate-up-2" type="submit">{{ __('Send Mail Now') }}</button>
							</div>
						</form>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
