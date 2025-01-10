@php($content = $data->content)
<section class="case-two-area secondary-bg pt-120">
	<div class="case-two__bg">
		<img src="{{ asset($content->background->value) }}" alt="image">
	</div>
	<div class="container">
		<div class="d-flex gap-4 flex-wrap align-items-center justify-content-between mb-60">
			<div class="section-header">
				<h5 class="wow fadeInLeft" data-wow-delay="00ms" data-wow-duration="1500ms">
					<svg class="me-1" width="20" height="12" viewBox="0 0 20 12" fill="none"
					     xmlns="http://www.w3.org/2000/svg">
						<rect x="0.75" y="0.747803" width="18.5" height="10.5" rx="5.25" stroke="#3C72FC"
						      stroke-width="1.5"/>
						<mask id="path-2-inside-1_1120_297" fill="white">
							<path
								d="M3 5.9978C3 3.78866 4.79086 1.9978 7 1.9978H13C15.2091 1.9978 17 3.78866 17 5.9978C17 8.20694 15.2091 9.9978 13 9.9978H7C4.79086 9.9978 3 8.20694 3 5.9978Z"/>
						</mask>
						<path
							d="M3 5.9978C3 2.96024 5.46243 0.497803 8.5 0.497803H11.5C14.5376 0.497803 17 2.96024 17 5.9978C17 4.61709 15.2091 3.4978 13 3.4978H7C4.79086 3.4978 3 4.61709 3 5.9978ZM17 5.9978C17 9.03537 14.5376 11.4978 11.5 11.4978H8.5C5.46243 11.4978 3 9.03537 3 5.9978C3 7.37851 4.79086 8.4978 7 8.4978H13C15.2091 8.4978 17 7.37851 17 5.9978ZM3 9.9978V1.9978V9.9978ZM17 1.9978V9.9978V1.9978Z"
							fill="#3C72FC" mask="url(#path-2-inside-1_1120_297)"/>
					</svg>
					
					{{ $content->sub_heading->value }}
				</h5>
				<h2 class="text-white wow fadeInLeft" data-wow-delay="200ms" data-wow-duration="1500ms">
					{{ $content->heading->value }}
				</h2>
			</div>
			<div class="arry-btn  d-flex gap-3 wow fadeInUp" data-wow-delay="200ms" data-wow-duration="1500ms">
				<button class="arry-prev case__arry-prev"><i class="fa-light fa-chevron-left"></i></button>
				<button class="arry-next case__arry-next active"><i
						class="fa-light fa-chevron-right"></i></button>
			</div>
		</div>
	</div>
	<div class="case-two__container">
		<div class="swiper case-two__slider">
			<div class="swiper-wrapper">
				@foreach($data->items as $item)
					@php($content = $item->content)
					<div class="swiper-slide">
						<div class="case-two__item">
							<div class="image case-two__image">
								<img src="{{ asset($content->cover_image->value) }}" alt="image">
							</div>
							<div class="case-two__content">
								<span>{{ $content->category->value }}</span>
								<h4><a href="{{ route('content.details', ['section' => $data->section, 'id' => $item->id]) }}" class="text-white">{{ $content->heading->value }}</a></h4>
							</div>
							<a href="{{ route('content.details', ['section' => $data->section, 'id' => $item->id]) }}" class="case-two__btn">
								<i class="fa-regular fa-arrow-right"></i>
							</a>
						</div>
					</div>
				@endforeach
			</div>
		</div>
	</div>
</section>