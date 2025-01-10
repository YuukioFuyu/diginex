{{-- resources/views/components/input.blade.php --}}
@props(['value'])

<div class="{{ $value->class }} mb-3">
	<div class="form-group">
		<label for="{{$key}}">{{ ucwords(str_replace('_', ' ', $key.'')) }}</label>
		@if($value->type == 'text')
			<input id="{{$key}}" class="form-control" name="{{$key}}" value="{{ $value->value }}" type="text" required>
		@elseif($value->type == 'textarea')
			<textarea id="{{$key}}" class="form-control" name="{{$key}}" type="text" required>{{ $value->value }}</textarea>
		@else
			<x-img-up name="{{ $key }}" :old="$value->value" :ref="'coevs-remove-img'"/>
		@endif
	</div>
</div>
