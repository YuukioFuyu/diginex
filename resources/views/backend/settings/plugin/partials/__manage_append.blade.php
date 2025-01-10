<div class="col-12 col-xl-12">
    <form method="POST" action="{{ route('admin.settings.plugin.update',$plugin->id) }}">
        @method('PUT')
        @csrf
        <div class="row">
            @foreach($plugin->credentials as $field_name => $credential)
                @if($field_name != 'fields')
                    <div class="col-md-12 mb-3">
                        <label class="form-label"
                               for="{{ $field_name }}">{{ ucwords(str_replace('_', ' ', $field_name)) }}</label>
                        <input type="text" name="credentials[{{ $field_name }}]" id="{{ $field_name }}"
                               value="{{ $credential }}"
                               placeholder="Enter {{ $field_name }}" class="form-control">
                    </div>
                @endif
            @endforeach

            @includeIf('backend.settings.plugin.other_fields.'.$plugin->fields_blade,['plugin' => $plugin])

                @if(!empty($plugin->copy_url))
                    @foreach($plugin->copy_url as $key => $value)
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="url_{{ $key }}">{{ Str::title($key) }}</label>
                            <div class="input-group">
                                <!-- URL Input Field -->
                                <input class="form-control" id="url_{{ $key.'_'.$loop->iteration }}" value="{{ url($value) }}" type="text" readonly required>

                                <!-- Copy Button -->
                                <button class="btn btn-outline-info" type="button" aria-label="Copy URL"
                                        onclick="copyToClipboard('url_{{ $key.'_'.$loop->iteration }}', '{{ __(':name copied!', ['name' => title($key)]) }}')">
                                    <i class="fa-solid fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                @endif


                <div class="col-md-6 mb-3 mt-1">
                <div class="card">
                    <div class="form-check form-switch card-body p-2 rounded d-flex align-items-center">
                        <label class="form-check-label mb-0 flex-grow-1" for="status">{{ __('Status') }}</label>
                        <input class="form-check-input coevs-switch flex-shrink-0 ms-2" type="checkbox" role="switch"
                               name="status" @checked($plugin->status) value="1" id="status">
                    </div>
                </div>

            </div>
        </div>

        <div class="mt-3 text-end">
            <button class="btn btn-info" type="submit">
                <x-icon name="check" height="20"/> {{ __('Update Now') }}</button>
        </div>
    </form>
</div>
