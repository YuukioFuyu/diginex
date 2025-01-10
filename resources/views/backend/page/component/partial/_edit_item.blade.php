<nav>
	<div class="nav nav-tabs mb-4" id="nav-tab" role="tablist">
		@foreach($languages as $code => $name)
			<a class="nav-item nav-link{{ $code == config('app.static_default_language') ? ' active' : '' }}" id="nav-home-tab" data-bs-toggle="tab" href="#list-content-nav-{{$code}}" role="tab" aria-controls="nav-home" aria-selected="true">{{ $name }}</a>
		@endforeach
	</div>
</nav>

<div class="tab-content" id="nav-tabContent">
	@foreach($modifiedData as $langCode => $listContent)
		<div class="tab-pane fade{{ $langCode == config('app.static_default_language') ? ' show active' : '' }}" id="list-content-nav-{{$langCode}}" role="tabpanel" aria-labelledby="nav-home-tab">
			@if(!empty($listContent))
				<form method="POST" action="{{ route('admin.page.component-item.update', $id) }}" enctype="multipart/form-data" id="edit-item-form">
					@method('PUT')
					@csrf
					<input type="hidden" name="lang" value="{{ $langCode }}">
					<div class="row">
						@foreach($listContent as $key => $value)
							<div class="{{ $value->class }} mb-3">
								<div class="form-group">
									<label for="{{ $key }}">{{ ucwords(str_replace('_', ' ', $key)) }}</label>
									@switch($value->type)
										@case('img')
											<x-img-up name="{{ $key }}" :old="$value->value ?? demoImage()" :ref="'coevs-remove-img'" />
											@break
										@case('textarea')
											<textarea id="{{ $key }}" class="form-control" name="{{ $key }}" rows="3">{{ $value->value }}</textarea>
											@break
										@case('rich_text')
											<textarea id="{{ $key }}" class="form-control summernote" name="{{ $key }}" rows="3">{{ $value->value }}</textarea>
											@break
										@case('date')
											<input id="{{ $key }}" class="form-control datepicker-input" name="{{ $key }}" value="{{ $value->value }}" type="date" required>
											@break
										@default
											<input id="{{ $key }}" class="form-control" name="{{ $key }}" type="text" value="{{ $value->value }}" required>
									@endswitch
								</div>
							</div>
						@endforeach
					</div>
					<div class="mt-3">
						<button class="btn btn-gray-800 mt-2 animate-up-2" type="submit">{{ __('Update Now') }}</button>
					</div>
				</form>
			@else
				<p class="text-muted text-center">{{ __('No Translate content is available') }}</p>
			@endif
		</div>
	@endforeach
</div>
