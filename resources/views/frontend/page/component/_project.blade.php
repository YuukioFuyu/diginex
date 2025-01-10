<section class="project-three-area secondary-bg">
	<div class="service-two__shape-left sway_Y__animationY">
		<img src="{{ asset($data->content->left_shape->value) }}" alt="shape">
	</div>
	<div class="service-two__shape-right sway_Y__animation">
		<img src="{{ asset($data->content->right_shape->value) }}" alt="shape">
	</div>
	<div class="case-two__container">
		<div class="section-header text-center mb-60">
			<h5 class="wow fadeInUp" data-wow-delay="00ms" data-wow-duration="1500ms">
				<img class="me-1" src="{{ asset($data->content->sub_heading_icon->value) }}" alt="icon">
				{{ $data->content->sub_heading->value }}
			</h5>
			<h2 class="text-white wow fadeInUp" data-wow-delay="200ms" data-wow-duration="1500ms">
				{{ $data->content->heading->value }}</h2>
		</div>
		<div class="swiper case__slider ms-0">
			<div class="swiper-wrapper">
				@foreach($data->items as $item)
					@php($content = $item->content)
				<div class="swiper-slide">
					<div class="project-three__item">
						<div class="image case__image">
							<img src="{{ asset($content->cover_image->value) }}" alt="image">
						</div>
						<div class="case__content">
							<span class="primary-color sm-font">{{ $content->category->value }}</span>
							<h3><a href="{{ route('content.details', ['section' => $data->section, 'id' => $item->id]) }}" class="text-white primary-hover">{{ $content->heading->value }}</a>
							</h3>
						</div>
						<a href="{{ route('content.details', ['section' => $data->section, 'id' => $item->id]) }}" class="case__btn">
							<i class="fa-regular fa-arrow-right"></i>
						</a>
					</div>
				</div>
				@endforeach
			</div>
		</div>
		<div class="mt-60 text-center wow fadeInUp" data-wow-delay="200ms" data-wow-duration="1500ms">
			<div class="dot case__dot"></div>
		</div>
	</div>
</section>