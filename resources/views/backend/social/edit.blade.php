<div class="row">
	<div class="col-md-6 mb-3">
		<div>
			<label for="name">{{ __('Name') }}</label>
			<input class="form-control" name="name" id="name" value="{{ old('name') ?? $social->name }}"  type="text" placeholder="Enter Social name" required>
		</div>
	</div>
	<div class="col-md-6 mb-3">
		<div>
			<label for="icon_class">{{ __('Icon Class') }}</label><span class="badge bg-info mx-2"><a href="https://fontawesome.com/" class="text-white text-muted" target="_blank">{{ __('Font Awesome') }}</a></span>
			<input class="form-control" name="icon_class" id="icon_class"  value="{{ old('icon_class') ?? $social->icon_class }}"  type="text" placeholder="Enter Icon Class name" required>
		</div>
	</div>
</div>

<div class="row ">
	<div class="col-md-12 mb-3">
		<div>
			<label for="link_target">{{ __('Link Target') }}</label>
			<select class="form-select" name="target" id="link_target" aria-label="Default select example">
				@foreach(\App\Constants\Navigation::TARGET as $name => $target)
					<option value="{{ $target }}" @selected($target == $social->target)>{{ ucwords($name) }}</option>
				@endforeach
			</select>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-12 mb-3">
		<div>
			<label for="url">{{ __('Social Url') }}</label>
			<input class="form-control" name="url" id="url"  value="{{ old('url') ?? $social->url }}" type="text" placeholder="Enter Social Url" required>
		</div>
	</div>
	<div class="col-md-6 mb-3 mt-2">
		<div>
			<div class="form-check form-switch">
				<label class="form-check-label" for="status">{{ __('Status') }}</label>
				<input class="form-check-input" type="checkbox" value="1" @checked($social->status) name="status"  id="status">
			</div>
		</div>
	</div>

</div>