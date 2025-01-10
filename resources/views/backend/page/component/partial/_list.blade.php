<div class="table-responsive">
	<table class="table user-table table-hover align-items-center">
		<thead>
		<tr>
			<th class="border-bottom">{{ __('Icon') }}</th>
			<th class="border-bottom">{{ __('Name') }}</th>
			<th class="border-bottom">{{ __('Category') }}</th>
			<th class="border-bottom">{{ __('Type') }}</th>
			<th class="border-bottom">{{ __('Status') }}</th>
			<th class="border-bottom">{{ __('Action') }}</th>
		</tr>
		</thead>
		<tbody>
		@foreach($sections as $section)
			<tr>
				<td class=""><img src="{{ asset($section->icon) }}" class="avatar" alt="Avatar"></td>
				<td><span class="fw-bold">{{ $section->name }}</span></td>
				<td><span class="fw-bold "> <a href="{{ route('admin.page.component.index', ['component_display' => $currentDisplay, 'component_category' => $section->category]) }}"> {{ ucfirst($section->category) }} </a></span></td>
				<td><span class="fw-bold">{{ ucwords($section->type) }}</span></td>
				<td><span class="fw-bold text-{{ $section->status ? 'success' : 'danger' }}">{{ $section->status ? 'Active' : 'Inactive' }}</span></td>
				<td>
					<a href="{{ route('admin.page.component.edit', ['component' => $section, 'component_display' => $currentDisplay, 'component_category' => $currentCategory]) }}">
						<x-svg i="edit"/>
					</a>
					@if($section->type == \App\Constants\Component::DYNAMIC)
						<span data-id="{{ $section->id }}" class="delete">
											<x-svg i="delete"/>
									</span>
					@endif
				
				</td>
			</tr>
		@endforeach
		</tbody>
	</table>
</div>