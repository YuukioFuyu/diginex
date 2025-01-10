<section class="about-area about-three-area sub-bg pt-120">
	<div class="about__shape wow slideInLeft" data-wow-delay="400ms" data-wow-duration="1500ms">
		<img src="{{ asset($data->content->line_shape->value) }}" alt="shape">
	</div>
	<div class="about-three__box-up wow slideInRight" data-wow-delay="00ms" data-wow-duration="1500ms">
		<img class="sway_Y__animationY" src="{{ asset($data->content->box_up_shape->value) }}" alt="shape">
	</div>
	<div class="about-three__box-down wow slideInRight" data-wow-delay="200ms" data-wow-duration="1500ms">
		<img class="sway_Y__animation" src="{{ asset($data->content->box_down_shape->value) }}" alt="shape">
	</div>
	<div class="container">
		<div class="row g-4 align-items-center">
			<div class="col-lg-5 order-2 order-lg-1">
				<div class="about-three__left-item">
					<div class="section-header mb-40">
						<h5 class="wow fadeInUp" data-wow-delay="00ms" data-wow-duration="1500ms">
							<img class="me-1" src="{{ asset($data->content->section_title_icon->value) }}" alt="icon">
							{{ $data->content->section_sub_heading->value }}
						</h5>
						<h2 class="wow fadeInUp" data-wow-delay="200ms" data-wow-duration="1500ms">{{ $data->content->section_heading->value }}</h2>
						<p class="wow fadeInUp" data-wow-delay="400ms" data-wow-duration="1500ms">{{ $data->content->section_description->value }}</p>
					</div>
					<div class="about-three__info bor-bottom pb-30">
						<div class="row g-4 wow fadeInDown" data-wow-delay="200ms" data-wow-duration="1500ms">
							<div class="col-md-6">
								<div class="about__right-item">
									<div class="icon">
										<img src="{{ asset($data->content->right_item_icon->value) }}" alt="icon">
									</div>
									<div class="content">
										<h4 class="mb-1">{{ $data->content->right_item_heading->value }}</h4>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="about__right-item">
									<div class="icon">
										<img src="{{ asset($data->content->right_item_icon_2->value) }}" alt="icon">
									</div>
									<div class="content">
										<h4 class="mb-1">{{ $data->content->right_item_heading_2->value }}</h4>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="about__info mt-30 wow fadeInDown" data-wow-delay="400ms"
					     data-wow-duration="1500ms">
						<div class="d-flex flex-wrap gap-2 align-items-center">
							<img src="{{ asset($data->content->center_item_avatar->value) }}" alt="image">
							<div class="info">
								<h5>{{ $data->content->center_item_heading->value }}</h5>
								<span class="sm-font">{{ $data->content->center_item_sub_heading->value }}</span>
							</div>
						</div>
						<div class="d-flex flex-wrap gap-2 align-items-center">
							<div class="about-call-icon">
                                <span>
                                    <x-svg i="call" />
                                </span>
							</div>
							<div class="info">
								<span class="sm-font fw-600 secondary-color">{{ $data->content->center_item_heading_2->value }}</span>
								<h5>{{ $data->content->center_item_sub_heading_2->value }}</h5>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-7 order-1 order-lg-2">
				<div class="faq__image about-three__image image wow fadeInRight" data-wow-delay="200ms"
				     data-wow-duration="1500ms">
					<div class="about-three-dot">
						<img class="sway__animationX" src="{{ asset($data->content->dot_shape->value) }}" alt="shape">
					</div>
					<div class="about-three-count p-4 d-flex align-items-center gap-3">
						<img class="icon" src="{{ asset($data->content->count_icon->value) }}" alt="icon">
						<div class="con">
							<h3><span class="count">{{ $data->content->count_heading->value }}</span>+</h3>
							<span class="secondary-color sm-font">{{ $data->content->count_sub_heading->value }}</span>
						</div>
					</div>
					<div class="faq__line sway__animation">
						<img src="{{ asset($data->content->faq_line->value) }}" alt="image">
					</div>
					<img src="{{ asset($data->content->main_image->value) }}" alt="image">
				</div>
			</div>
		</div>
	</div>
</section>