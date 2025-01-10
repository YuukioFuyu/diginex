<section class="case-single-area pt-120 pb-120">
	<div class="container">
		<div class="case-single__item">
			<div class="image">
				<img src="{{ asset($content->details_image->value) }}" alt="image">
			</div>
			<h3 class="case-single__title mt-40 mb-30">{{ $content->heading->value }}</h3>
			<p>{{ $content->summary->value }}</p>
			<ul class="case-date py-4 bor-top bor-bottom mt-40 mb-4">
				<li><span>{{ __('Completed Date') }}:</span> {{ $content->completed_date->value }}</li>
				<li><span>{{ __('Category') }}:</span> {{ $content->category->value }}</li>
				<li><span>{{ __('Client') }}:</span> {{ $content->client->value }}</li>
				<li><span>{{ __('Location') }}:</span> {{ $content->location->value }}</li>
			</ul>
		
			{!! $content->details->value !!}
	</div>
</section>