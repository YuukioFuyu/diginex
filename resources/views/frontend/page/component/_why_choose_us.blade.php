@php($content = $data->content)
<section class="choose-area sub-bg pt-120 pb-120">
	<div class="choose__video-btn">
		<div class="video-btn video-pulse wow fadeIn" data-wow-delay="200ms" data-wow-duration="1500ms">
			<a class="video-popup" href="{{ $content->video_link->value }}"><i
					class="fa-solid fa-play"></i></a>
		</div>
	</div>
	<div class="choose__shape-right1 wow slideInRight d-none d-lg-block" data-wow-delay="200ms"
	     data-wow-duration="1500ms">
		<img src="{{ asset($content->shape_right->value) }}" alt="shape">
	</div>
	<div class="choose__shape-right2 wow slideInRight d-none d-lg-block" data-wow-delay="200ms"
	     data-wow-duration="1000ms">
		<img src="{{ asset($content->shape_right2->value) }}" alt="shape">
	</div>
	<div class="choose__shape-left sway__animation">
		<img src="{{ asset($content->shape_left->value) }}" alt="shape">
	</div>
	<div class="container">
		<div class="row g-4">
			<div class="col-lg-6 d-block d-lg-none">
				<div class="image">
					<img src="{{ asset($content->cover_photo->value) }}" alt="image">
				</div>
			</div>
			<div class="col-lg-6">
				<div class="section-header mb-40">
					<h5 class="wow fadeInUp" data-wow-delay="00ms" data-wow-duration="1500ms">
						<svg class="me-1" width="20" height="12" viewBox="0 0 20 12" fill="none"
						     xmlns="http://www.w3.org/2000/svg">
							<rect x="0.75" y="0.75" width="18.5" height="10.5" rx="5.25" stroke="#3C72FC"
							      stroke-width="1.5" />
							<mask id="path-2-inside-1_682_455" fill="white">
								<path
									d="M3 6C3 3.79086 4.79086 2 7 2H13C15.2091 2 17 3.79086 17 6C17 8.20914 15.2091 10 13 10H7C4.79086 10 3 8.20914 3 6Z" />
							</mask>
							<path
								d="M3 6C3 2.96243 5.46243 0.5 8.5 0.5H11.5C14.5376 0.5 17 2.96243 17 6C17 4.61929 15.2091 3.5 13 3.5H7C4.79086 3.5 3 4.61929 3 6ZM17 6C17 9.03757 14.5376 11.5 11.5 11.5H8.5C5.46243 11.5 3 9.03757 3 6C3 7.38071 4.79086 8.5 7 8.5H13C15.2091 8.5 17 7.38071 17 6ZM3 10V2V10ZM17 2V10V2Z"
								fill="#3C72FC" mask="url(#path-2-inside-1_682_455)" />
						</svg>

						{{ $content->section_sub_heading->value }}
					</h5>
					<h2 class="wow fadeInUp" data-wow-delay="200ms" data-wow-duration="1500ms"> {{ $content->section_heading->value }}</h2>
				</div>
				<div class="row g-4 wow fadeInUp" data-wow-delay="400ms" data-wow-duration="1500ms">
					<div class="col-md-6">
						<div class="about__right-item">
							<div class="icon">
								<img src="{{ asset($content->right_item_icon->value) }}" alt="icon">
							</div>
							<div class="content">
								<h4 class="mb-1">{{ $content->right_item_heading->value }}</h4>
								<p>{{ $content->right_item_description->value }}</p>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="about__right-item">
							<div class="icon">
								<img src="{{ asset($content->right_item_icon2->value) }}" alt="icon">
							</div>
							<div class="content">
								<h4 class="mb-1">{{ $content->right_item_heading2->value }}</h4>
								<p>{{ $content->right_item_description2->value }}</p>
							</div>
						</div>
					</div>
				</div>
				
				
				<div class="progress-area wow fadeInDown mt-40" data-wow-delay="00ms"
				     
				     
				     data-wow-duration="1500ms">
					<div class="progress__title mb-10">
						<h5>{{ $content->progress_title->value }}</h5>
						<span><span class="progress-count">{{ $content->progress_count->value }}</span>%</span>
					</div>
					<div class="progress">
						<div class="progress-bar wow slideInLeft" data-wow-duration=".86s" role="progressbar"
						     style="width: {{ $content->progress_count->value }}%;" aria-valuenow="{{ $content->progress_count->value }}" aria-valuemin="0" aria-valuemax="100">
						</div>
					</div>
				</div>
				<div class="progress-area wow fadeInDown mt-20" data-wow-delay="200ms"
				     data-wow-duration="1500ms">
					<div class="progress__title mb-10">
						<h5>{{ $content->progress2_title->value }}</h5>
						<span><span class="progress-count">{{ $content->progress2_count->value }}</span>%</span>
					</div>
					<div class="progress">
						<div class="progress-bar wow slideInLeft" data-wow-duration=".{{ $content->progress2_count->value }}s" role="progressbar"
						     style="width: {{ $content->progress2_count->value }}%;" aria-valuenow="{{ $content->progress2_count->value }}" aria-valuemin="0" aria-valuemax="100">
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-6 d-none d-lg-block">
				<div class="choose__image image">
					<img src="{{ asset($content->cover_photo->value) }}" alt="image">
				</div>
			</div>
		</div>
	</div>
</section>