<div class="modal fade" id="new-item-modal" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h2 class="h6 modal-title">{{ __(ucwords(str_replace('_', ' ', $data['section'])).' '.'Section Item') }}</h2>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-12 col-xl-12">
						@include('backend.page.component.partial._new_form_data')
					</div>
				</div>
			</div>
		
		</div>
	</div>
</div>