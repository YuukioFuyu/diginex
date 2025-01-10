
<div class="banner-three-area">
	<div class="banner-three__bg">
		<img class="sway_Y__animation" src="{{ asset($data->content->background->value) }}" alt="bg-image">
	</div>
	<div class="banner-three__shape-left wow slideInLeft" data-wow-delay="00ms" data-wow-duration="1500ms">
		<img class="sway_Y__animationY" src="{{ asset($data->content->left_shape->value) }}" alt="image">
	</div>
	<div class="banner-three__shape-right wow slideInRight" data-wow-delay="200ms" data-wow-duration="1500ms">
		<img class="sway__animationX" src="{{ asset($data->content->right_shape->value) }}" alt="image">
	</div>
	<div class="banner-three__container">
		<div class="row align-items-center">
			<div class="col-lg-7 order-2 order-lg-1">
				<div class="banner-three__content pt-0 pb-0">
					<h4 class="wow fadeInUp text-white mb-20" data-wow-delay="00ms" data-wow-duration="1500ms">
						{{ $data->content->sub_heading->value }}
					</h4>
					<h1 class="wow fadeInUp text-white" data-wow-delay="200ms" data-wow-duration="1500ms">
						{{ $data->content->heading->value }}
					</h1>
					<p class="wow fadeInUp mt-20" data-wow-delay="400ms" data-wow-duration="1500ms">
						{{ $data->content->content->value }}
					</p>
					<div class="banner-three__info wow fadeInUp mt-50" data-wow-delay="600ms"
					     data-wow-duration="1500ms">
						<a class="wow fadeInUp btn-one mt-0" data-delay="1s" href="{{ $data->content->button_link->value }}">{{ $data->content->button_title->value }} <i class="fa-regular fa-arrow-right-long"></i></a>
						<div class="banner-three__video-btn d-flex gap-4 align-items-center">
							<div class="video-btn video-pulse">
								<a class="video-popup" href="{{ $data->content->video_button_link->value }}"><i
										class="fa-solid fa-play"></i></a>
							</div>
							<h5 class="text-white">{{ $data->content->video_button_title->value }}</h5>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-5 order-1 order-lg-2 wow fadeInLeft" data-wow-delay="200ms"
			     data-wow-duration="1500ms">
				<div class="image">
					<img src="{{ asset($data->content->left_image->value) }}" alt="image">
				</div>
			</div>
		</div>
	</div>
</div>