<div class="brand-three-area bg-image pt-100 pb-100 light-area" data-background="{{ asset($data->content->case_background->value) }}">
	<div class="brand-three__line1">
		<img src="{{ asset($data->content->brand_line_1->value) }}" alt="shape">
	</div>
	<div class="brand-three__shape1">
		<img src="{{ asset($data->content->brand_shape_1->value) }}" alt="shape">
	</div>
	<div class="brand-three__line2">
		<img src="{{ asset($data->content->brand_line_2->value) }}" alt="shape">
	</div>
	<div class="brand-three__shape2">
		<img src="{{ asset($data->content->brand_shape_2->value) }}" alt="shape">
	</div>
	<div class="container">
		<div class="swiper brand__slider">
			<div class="swiper-wrapper">
				@foreach($data->items as $item)
					@php($content = $item->content)
				<div class="swiper-slide">
					<div class="brand__image image">
						<img src="{{ asset($content->brand_logo->value) }}" alt="image">
					</div>
				</div>
				@endforeach
			
			</div>
		</div>
	</div>
</div>
