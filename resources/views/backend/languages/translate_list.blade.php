@foreach($translations as $type => $items)
	@foreach($items as $group => $translations)
		@foreach($translations as $key => $value)
			@if(!is_array($value[config('app.default_language')]))
				<tr>
					<td>{{ $group }}</td>
					<td>{{  Str::limit($key,30) }}</td>
					<td>{{  Str::limit($value[config('app.default_language')],30) }}</td>
					<td>
						{{ Str::limit($value[$languageCode],30) }}
					</td>
					<td>
						<a class="editKeyword"
						   data-language="{{ $languageCode }}"
						   data-group="{{ $group }}" data-key="{{ $key }}"
						   data-value="{{ $value[$languageCode] }}"
						   data-bs-toggle="tooltip" title=""
						   data-bs-original-title="Edit Language">
							<x-svg i="edit" class="icon icon-sm me-1 text-info" /></a>
					</td>
				</tr>
			@endif
		@endforeach
	@endforeach
@endforeach
