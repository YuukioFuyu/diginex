@php use App\Constants\Component; @endphp
@extends('backend.layouts.app')
@section('title', __(title_case($data['section']).' Manage'))
@section('content')
	<div class="py-4">
		<div class="d-flex justify-content-between w-100 flex-wrap">
			<div class="mb-3 mb-lg-0">
				<h1 class="h4">{{ __(ucwords(str_replace('_',' ',$data['section'])).' '.'Manage') }}</h1>
			</div>
			<div class="btn-toolbar  mb-md-0 mb-2 ">
					@if(request()->get('page') === null)
						<a href="{{ route('admin.page.component.index',['component_display' => $currentDisplay, 'component_category' => $currentCategory]) }}"
						   class="btn btn-sm btn-gray-800 d-inline-flex align-items-center">
							<x-svg i="back"/>
							{{ __('Back') }}
						</a>
					@elseif(request()->get('page') !== null && request()->get('page'))
						<a href="{{ route('admin.page.site.edit', request()->get('page')) }}"
						   class="btn btn-sm btn-gray-800 d-inline-flex align-items-center">
							<x-svg i="back"/>
							{{ __('Back') }}
						</a>
					@endif

			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12 col-xl-12">
			<div class="card card-body border-0 shadow mb-4">
				{{-- Tab --}}
				<nav>
					<div class="nav nav-tabs mb-4" id="nav-tab" role="tablist">
						@foreach($languages as $code => $name)
							<a class="nav-item nav-link @if($code == config('app.static_default_language')) active @endif" id="nav-home-tab" data-bs-toggle="tab" href="#nav-{{$code}}" role="tab"
							   aria-controls="nav-home" aria-selected="true">{{ $name }}</a>
						@endforeach
					</div>
				</nav>
				<div class="tab-content" id="nav-tabContent">

						@forelse($data['content'] as $langCode => $pageContent)

						<div class="tab-pane fade  @if($langCode == config('app.static_default_language')) show active @endif" id="nav-{{$langCode}}" role="tabpanel" aria-labelledby="nav-home-tab">
							@if(!empty($pageContent))
								<form method="POST" action="{{ route('admin.page.component.update', $data['id']) }}" enctype="multipart/form-data">
									@method('PUT')
									@csrf
									<input type="hidden" name="lang" value="{{ $langCode }}">
									@if($langCode == config('app.static_default_language'))
										<div class="row">
											<div class="col-md-6 mb-3">
												<div>
													<label for="preview">{{ __('Preview') }}</label>
													<x-img-up name="preview" :old="$data['preview']"/>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6 mb-3">
												<div>
													<label for="name">{{ __('Component Name') }}</label>
													<input class="form-control" name="name" value="{{ $data['name'] }}" id="name" type="text" placeholder="Enter your first name" required>
												</div>
											</div>
										</div>
									@endif
									@if($data['type'] == Component::STATIC)
										<div class="row">
											@foreach($pageContent as $key => $value)
												@include('backend.page.component.partial._input', ['value' => $value])
											@endforeach
										</div>
									@else
										@if($langCode == config('app.static_default_language'))
											<div class="row">
												<div class="col-md-6 mb-3">
													<div>
														<label for="icon">{{ __('Component Icon') }}</label>
														<x-img-up name="icon" :old="$data['icon']"/>
													</div>
												</div>
											</div>
										@endif
										<div class="row">
											<div class="col-md-12 mb-3">
												<div>
													<label for="content">{{ __('Component Content') }}</label>
													<textarea class="form-control summernote" name="content" id="content" rows="3">{!! $pageContent !!}</textarea>
												</div>
											</div>
										</div>
									@endif
									@if($langCode == config('app.static_default_language'))
										<div class="row">
											<div class="col-md-6 mb-3 mt-2">
												<div>
													<div class="form-check form-switch">
														<label class="form-check-label" for="status">{{ __('Component Status') }}</label>
														<i data-bs-toggle="tooltip"
														   data-bs-placement="top"
														   title="{{ __('When status is active, component will be visible in Page Manager') }}"
														   class="mx-1 fa-solid fa-circle-info">
														</i>
														<input class="form-check-input" type="checkbox" value="1" @checked($data['status'])  name="status" id="status">
													</div>
												</div>
											</div>
										</div>
									@endif
									<div class="mt-3">
										<button class="btn btn-gray-800 mt-2 animate-up-2" type="submit">{{ __( 'Update Component') }}</button>
										@if($data['content_id'] != null)
											<a class="btn btn-gray-800 mt-2 animate-up-2"
											   href="{{ route('admin.page.component.edit', $data['content_id']) }}">{{ title_case($data['section']) .' '.__('Item Manage') }} </a>
										@endif
									</div>
								</form>
							@else
								<p class="text-muted text-center">{{ __('No Translate content is available') }}</p>
							@endif
						</div>

						@empty
							<p>No users</p>
						@endforelse
				</div>
				{{-- End of tab --}}
			</div>
		</div>
	</div>

	@if(null !== $data['content_fields'])

		{{-- Item Index Table 	--}}
		@include('backend.page.component.item.index')

		{{-- Add new Data Modal--}}
		@include('backend.page.component.partial._new_modal', ['fields' => $data['content_fields'],'componentId' => $data['id']])

		{{-- edit Data Modal--}}
		@include('backend.page.component.partial._edit_modal')

	@endif
@endsection
@section('script')
	<script>
        $(document).ready(function () {
            'use strict';

            $('.edit-modal-show').on('click', function (event) {
                let id = $(this).data('id');
				{{--let updateUrl = `{{ route('admin.page.component-item.update', ['component_item' => ':id']) }}`.replace(':id', id);--}}
                $.ajax({
                    url: "{{ route('admin.page.component-item.edit',['component_item' => ':id']) }}".replace(':id', id),
                    type: "GET",
                    data: {id: id},
                    success: function (data) {
                        // $('#edit-item-form').attr('action', updateUrl);
                        $('#edit-data').html(data);
                        handleImagePreview()
                    }
                });
                $('#item-edit').modal('show')
            })


            // delete item
            $('.delete').on('click', function (event) {
                let id = $(this).data('id');
                let url = '{{ route("admin.page.component-item.destroy", ["component_item" => ":id"]) }}'.replace(':id', id)
                deleteItem(url)
            })

        });
	</script>
@endsection
