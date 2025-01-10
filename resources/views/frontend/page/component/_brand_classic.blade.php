@php($content = $data->content)
<div class="brand-area">
	<div class="container">
		<div class="brand__wrp">
			<div class="brand__shape">
				<img src="{{ asset($content->shape->value) }}" alt="">
			</div>
			<div class="swiper brand__slider">
				<div class="swiper-wrapper">
					@foreach($data->items as $item)
					
						@php($itemContent = $item->content)
						<div class="swiper-slide">
							<div class="brand__image image">
								<img src="{{ asset($itemContent->brand_logo->value) }}" alt="image">
							</div>
						</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>
</div>