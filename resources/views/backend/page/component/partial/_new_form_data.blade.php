<form method="POST" action="{{ route('admin.page.component-item.store') }}" enctype="multipart/form-data">
	@csrf
	<input type="hidden" name="component_id" value="{{ $componentId }}">
	<input type="hidden" name="fields" value="{{ json_encode($fields) }}">
	<div class="row">
		@foreach($fields as $key => $value)
			<div class="{{ $value->class }} mb-3">
				<div class="form-group">
					<label for="{{ $key }}">{{ ucwords(str_replace('_', ' ', $key.'')) }}</label>
					@switch($value->type)
						@case('img')
							<x-img-up name="{{ $key }}" />
							@break
						@case('textarea')
							<textarea id="{{ $key }}" class="form-control" name="{{ $key }}" rows="3"></textarea>
							@break
						@case('rich_text')
							<textarea id="{{ $key }}" class="form-control summernote" name="{{ $key }}" rows="3"></textarea>
							@break
						@case('date')
							<input id="{{ $key }}" class="form-control datepicker-input" name="{{ $key }}" type="date" required>
							@break
						@default
							<input id="{{ $key }}" class="form-control" name="{{ $key }}" type="text" required>
					@endswitch
				</div>
			</div>
		@endforeach
	
	</div>
	
	<div class="mt-3">
		<button class="btn btn-gray-800 mt-2 animate-up-2" type="submit">{{ __('Save Now') }}</button>
	</div>
</form>