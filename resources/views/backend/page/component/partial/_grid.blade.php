<div class="row">
	@foreach($sections as $section)
		<div class="col-md-3 col-sm-6 col-12">
			<div class="card coevs-component mb-4">
				<div class="card-preview">
					<img src="{{ asset($section->preview) }}" class="card-img-top" alt="{{ $section->name }}">
				</div>

				<div class="card-body">
					<span class="badge bg-info text-white"><a href="{{ route('admin.page.component.index', ['component_display' => $currentDisplay, 'component_category' => $section->category]) }}"> {{ ucfirst($section->category) }} </a></span>
					<p class="card-text">{{ $section->name }}</p>
					<a class="btn btn-info" href="{{ route('admin.page.component.edit', ['component' => $section, 'component_display' => $currentDisplay, 'component_category' => $currentCategory]) }}">{{ __('Manage') }}</a>
					@if($section->type == \App\Constants\Component::DYNAMIC)
						<span data-id="{{ $section->id }}" class="delete btn btn-danger">
											{{ __('Delete') }}
									</span>
					@endif
				</div>
			</div>
		</div>
	@endforeach
</div>
