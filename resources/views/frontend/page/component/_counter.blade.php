<section class="counter-area">
	<div class="container">
		<div class="counter__wrp gradient-bg">
			<div class="counter__shape wow slideInRight" data-wow-delay="200ms" data-wow-duration="1500ms">
				<img src="{{ asset($data->content->shape_background->value) }}" alt="shape">
			</div>
			@foreach($data->items as $item)
				@php($content = $item->content)
			<div class="counter__item wow bounceInUp" data-wow-delay="00ms" data-wow-duration="1000ms">
				<img src="{{ asset($content->icon->value) }}" alt="icon">
				<div class="content">
					<h3><span class="count">{{ $content->counter->value }}</span>+</h3>
					<p class="text-white">{{ $content->title->value }}</p>
				</div>
			</div>
			@endforeach
		</div>
	</div>
</section>