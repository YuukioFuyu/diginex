@extends('backend.layouts.app')
@section('title')
	{{  __('Theme Page') }}
@endsection

@section('content')
	<div class="py-4">
		<div class="d-flex justify-content-between flex-wrap w-100">
			<div class="mb-2 mb-lg-0">
				<h1 class="h4">{{ __('Theme Management') }}</h1>
			</div>
		</div>
	</div>

	<div class="row">
	@foreach($themes as $key => $value)
			<div class="col-12 col-md-6 col-xl-4 mb-4 theme">
				<div class="card">
					<div class="card-img-top scroll-container">
						<img src="{{ asset($value['cover']) }}" alt="Theme Image">
					</div>
					<div class="card-body d-flex justify-content-between align-items-center">
						<h5 class="mb-0">{{ ucwords($key) }}</h5>
						@if (!array_diff($value['component_ids'], $homeComponentIds))
							<span class="btn btn-secondary d-flex align-items-center">
                    <x-svg i="shield-ban"/> {{ __('Activated') }}
                </span>
						@else
							<form method="POST" action="{{ route('admin.theme.update') }}">
								@csrf
								<input type="hidden" name="name" value="{{ $key }}">
								<button type="submit" class="btn btn-info d-flex align-items-center">
									<x-svg i="grid-check"/> {{ __('Active Now') }}
								</button>
							</form>
						@endif
					</div>
				</div>
			</div>
		@endforeach
	</div>

@endsection
