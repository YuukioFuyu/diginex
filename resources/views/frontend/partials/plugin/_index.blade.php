@foreach($plugins as $pluginCode => $plugin)
	@if($plugin['status'] == \App\Constants\Status::TRUE)
		@includeWhen($pluginCode == 'fb', 'frontend.partials.plugin._fb')
		@includeWhen($pluginCode == 'tawk', 'frontend.partials.plugin._tawk')
		@includeWhen($pluginCode == 'google-analytics', 'frontend.partials.plugin._google_analytics')
	@endif
@endforeach
