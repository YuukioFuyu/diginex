@extends('backend.layouts.app')
@section('title', __('Languages Translation'))
@section('content')
	<div class="py-4">
		<div class="d-flex justify-content-between w-100 flex-wrap">
			<div class="mb-3 mb-lg-0">
				<h1 class="h4">{{  __('Languages Translation') }}</h1>
			</div>
			<div class="btn-toolbar  mb-md-0 mb-2 ">
				<a href="{{ route('admin.language.index') }}" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center me-2">
					<x-svg i="back"/>
					{{ __('Back') }}
				</a>
			</div>
		</div>
	</div>
	
	<div class="card border-0 shadow mb-4">
		
		<div class="card-header">
			<form action="{{ route('admin.language.translate',  $languageCode ) }}" method="get">
				<div class="row align-items-end">
					<div class="col-lg-3">
						<div class="input-group">
							<input type="text" class="form-control" name="filter" value="{{ request()->get('filter') }}" placeholder="Search" aria-label="Search">
							<button class="input-group-text" type="submit">
								<x-svg i="search"/>
							</button>
						</div>
					</div>
					<div class="col-lg-9 col-md-6 d-flex justify-content-end">
						<div class="col-md-2">
							@include('backend.languages.partial._select', ['name' => 'language', 'items' => $languages, 'selected' => $languageCode, 'submit' => true])
						</div>
						&nbsp;&nbsp;
						<div class="col-md-2">
							@include('backend.languages.partial._select', ['name' => 'group', 'items' => $groups, 'selected' => request()->get('group'), 'optional' => true, 'submit' => true])
						</div>
					</div>
				</div>
			</form>
		</div>
		
		<div class="card-body">
			<div class="row">
				<div class="col-xl-12">
					<div class="table-responsive">
						@isset($translations)
							
							<table class="table user-table table-hover align-items-center ">
								<thead class=" thead-light">
								<tr>
									<th>{{ __('GROUP / SINGLE') }}</th>
									<th>{{ __('KEY') }}</th>
									<th>{{ config('app.default_language') }}</th>
									<th>{{$languageCode}}</th>
									<th>{{ __('Action') }}</th>
								</tr>
								</thead>
								<tbody>
								@include('backend.languages.translate_list')
								</tbody>
							</table>
						
						@endisset
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="updateTranslate" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="h6 modal-title">{{ __('Edit Keyword') }}</h2>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form action="{{ route('admin.language.translate-update') }}" method="post">
						@csrf
						<div class="site-input-groups mb-2">
							<label class="box-input-label key-label"></label>
							<input type="hidden" class="form-control key-key" name="key">
							<input type="text" class="form-control key-value" name="value">
							<input type="hidden" class="form-control key-group" name="group">
							<input type="hidden" class="form-control key-language" name="language">
						</div>
						<div class="action-btns">
							<button type="submit" class="btn btn-gray-800 mt-2 animate-up-2">{{ __('Save Now') }}</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

@endsection
@section('script')
	
	<script>
        (function ($) {
            "use strict";

            $('.editKeyword').on('click', function (e) {
                var $this = $(this);
                var settings = {
                    key: $this.data('key'),
                    value: $this.data('value'),
                    group: $this.data('group'),
                    language: $this.data('language')
                };

                $('.key-label').text('Key: ' + settings.key);
                $('.key-key').val(settings.key);
                $('.key-value').val(settings.value);
                $('.key-group').val(settings.group);
                $('.key-language').val(settings.language);

                $('#updateTranslate').modal('show');
            });


        })(jQuery);
	</script>
@endsection