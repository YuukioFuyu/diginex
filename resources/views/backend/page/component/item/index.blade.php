<div class="row">
	<div class="col-12 col-xl-12">
		<div class="card card-body border-0 shadow mb-4">
			<div class="d-flex justify-content-between w-100 flex-wrap">
				<div class="mb-4 mb-lg-0">
					<h2 class="h5">{{ __(ucwords(str_replace('_', ' ', $data['section'])).' '.'Item Manage') }}</h2>

				</div>
				<div class="btn-toolbar  mb-4">
					<a  class="btn btn-sm btn-gray-800 d-inline-flex align-items-center"
					    @if($data['with_modal'])  type="button" data-bs-toggle="modal" data-bs-target="#new-item-modal" @else
					    href="{{ route('admin.page.component-item.create', ['component_id' => $data['id']]) }}" @endif>
						<x-svg i="plus"/>
						{{ __('Add New') }}
					</a>
				</div>
			</div>
			
			<div class="table-responsive">
				<table class="table user-table table-hover align-items-center">
					<thead class="thead-light">
					<tr>
						@foreach( array_slice(array_keys((array)$data['item_list_level']),0,3 ) as $name)
							<th class="border-0">{{ __(ucwords(str_replace('_', ' ', $name))) }}</th>
						@endforeach
						<th class="border-0 text-end">{{ __('Action') }}</th>
					</tr>
					</thead>
					<tbody>
					@foreach($data['item_list_value'] as $items)
						<tr>
							@foreach(array_slice($items['content']->toArray(),0,3) as  $item)
								<td>
									<span class="fw-bold">{{ $item['value'] ?? '-' }}</span>
								</td>
							@endforeach
							<td class="text-end">
								<a @if($data['with_modal'])
									    data-id="{{ $items['id'] }}"
								        class="edit-modal-show"
									@else
										href="{{ route('admin.page.component-item.edit', $items['id']) }}"
									@endif
								>
								<x-svg i="edit"/>
								</a>
								<span data-id="{{ $items['id'] }}" class="delete">
											<x-svg i="delete"/>
										</span>
							</td>
						</tr>
					
					@endforeach
					
					</tbody>
				</table>
				@if(count($data['item_list_value']) == 0)
					<h5 class="text-center text-muted py-3">{{ __('No Data Available') }}</h5>
				@endif
			</div>
		</div>
	</div>
</div>