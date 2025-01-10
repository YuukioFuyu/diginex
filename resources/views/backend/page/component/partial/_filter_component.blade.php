@foreach($componentsAvailable as $component)
	<div class="item" draggable="true" data-index="{{ $component->id }}" >
		<input type="hidden" name="component[]" value="{{ $component->id }}">
		<div class="details">
			<img src="{{ asset($component->icon) }}">
			<span>{{ $component->name }}</span>
		</div>
		
		<span class="component-manage" title="{{ __('Manage Component') }}" data-bs-toggle="tooltip" data-bs-placement="top">
			<a href="{{ route('admin.page.component.edit', ['component' => $component->id,'page' => $page->id ?? $pageId]) }}" target="_blank" > <x-svg
					i="setting-2"/> </a>
		</span>
		
		<span class="manage-drag" title="{{ __('Add to Page') }}" data-bs-toggle="tooltip" data-bs-placement="top">
			<span class="drag-svg"> <x-svg i="com-plus"/> </span>
		</span>
	
	</div>
@endforeach

@if($componentsAvailable->isEmpty())
	<h4 class="text-center text-muted h5">{{ __('No Component Available') }}</h4>
@endif