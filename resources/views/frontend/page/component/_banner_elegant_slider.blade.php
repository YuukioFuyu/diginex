@php($content = $data->content)
<section class="banner-two-area">
	<div class="banner-two__line">
		<img class="sway_Y__animation" src="{{ asset($content->banner_line->value) }}" alt="shape">
	</div>
	<div class="swiper banner__slider">
		<div class="swiper-wrapper">
			@foreach($data->items as $item)
				@php($content = $item->content)
				<div class="swiper-slide">
					<div class="banner-two__line-left" data-animation="slideInLeft" data-duration="3s"
					     data-delay=".3s">
						<img src="{{ asset($content->left_line->value) }}" alt="shape">
					</div>
					<div class="banner-two__shape2" data-animation="slideInRight" data-duration="2s"
					     data-delay=".3s">
						<img src="{{ asset($content->solid_right_down->value) }}" alt="shape">
					</div>
					<div class="banner-two__shape1" data-animation="slideInRight" data-duration="2s"
					     data-delay=".5s">
						<img src="{{ asset($content->solid_right_up->value) }}" alt="shape">
					</div>
					<div class="banner-two__right-shape wow slideInRight" data-wow-delay="200ms"
					     data-wow-duration="1500ms">
						<img class="sway_Y__animation" src="{{ asset($content->right_shape->value) }}"
						     alt="shape">
					</div>
					<div class="banner-two__circle-solid">
						<img class="animation__rotate" src="{{ asset($content->circle_solid->value) }}"
						     alt="shape">
					</div>
					<div class="banner-two__circle-regular">
						<img class="animation__rotateY" src="{{ asset($content->circle_regular->value) }}"
						     alt="shape">
					</div>
					<div class="slide-bg" data-background="{{ asset($content->background->value) }}"></div>
					<div class="container">
						<div class="banner-two__content text-center">
							<h4 data-animation="fadeInUp" data-delay=".3s" class="text-white mb-20">
								{{ $content->sub_heading->value }}
							</h4>
							<h1 data-animation="fadeInUp" data-delay=".5s" class="text-white">
								{{ $content->heading->value }}
							</h1>
							<p data-animation="fadeInUp" data-delay=".7s" class="mt-20">
								{!! $content->summary->value !!}
							</p>
							<a data-animation="fadeInUp" data-delay="1s" href="{{ $content->button_link->value }}"
							   class="btn-one mt-50">{{ $content->button_text->value }} <i class="fa-regular fa-arrow-right-long"></i></a>
						</div>
					</div>
				</div>
			@endforeach
		</div>
	</div>
	<div class="banner__dot-wrp banner-two__dot-wrp">
		<div class="dot-light banner__dot"></div>
	</div>
</section>