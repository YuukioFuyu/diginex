<section class="testimonial-three-area pt-120 pb-120 bg-image sub-bg light-area"
         data-background="{{ asset($data->content->testimonial_background->value) }}">
	<div class="testimonial-three__wrp">
		<div class="row g-4">
			<div class="col-md-6 col-lg-5 col-xl-3">
				<div class="section-header mb-40">
					<h5 class="wow fadeInUp" data-wow-delay="00ms" data-wow-duration="1500ms">
						<img class="me-1" src="{{ asset($data->content->testimonial_title_icon->value) }}" alt="icon">
						{{ $data->content->sub_heading->value }}
					</h5>
					<h2 class="wow fadeInUp" data-wow-delay="200ms" data-wow-duration="1500ms">{{ $data->content->heading->value }}</h2>
				</div>
				<div class="testimonial-three__arry-btn d-flex gap-3 wow fadeInDown" data-wow-delay="400ms"
				     data-wow-duration="1500ms">
					<button class="arry-prev testimonial-three__arry-prev"><i
							class="fa-light fa-chevron-left"></i></button>
					<button class="arry-next testimonial-three__arry-next active"><i
							class="fa-light fa-chevron-right"></i></button>
				</div>
			</div>
			<div class="col-md-6 col-lg-7 col-xl-9">
				<div class="swiper testimonial-three__slider">
					<div class="swiper-wrapper">
						@foreach($data->items as $item)
							@php($content = $item->content)
						<div class="swiper-slide">
							<div class="testimonial-three__item">
								<div class="d-flex align-items-center gap-3">
									<div class="testimonial-three__image">
										<svg width="24" height="18" viewBox="0 0 24 18" fill="none"
										     xmlns="http://www.w3.org/2000/svg">
											<path d="M0 0V18L9 9V0H0ZM15 0V18L24 9V0H15Z" fill="#3C72FC" />
										</svg>
										<img src="{{ asset($content->testimonial_item_avatar->value) }}"
										     alt="image">
									</div>
									<div class="con">
										<h4>{{ $content->name->value }}</h4>
										<span>{{ $content->profession->value }}</span>
									</div>
								</div>
								<p class="mt-30">“ {{ $content->comment->value }} ”</p>
								<div class="star mt-20">
									@for ($i = 1; $i <= 5; $i++)
										@if ($i <= $content->rating->value)
											<i class="fa-sharp fa-solid fa-star"></i>
										@else
											<i class="fa-sharp fa-solid fa-star disable"></i>
										@endif
									@endfor
								</div>
							</div>
						</div>

						@endforeach
					</div>
				</div>
			</div>
		</div>
	</div>
</section>