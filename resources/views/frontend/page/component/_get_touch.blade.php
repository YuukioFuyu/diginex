@php($content = $data->content)
<section class="quote-area">
	<div class="container">
		<div class="quote__wrp gradient-bg">
			<div class="counter__shape wow slideInRight" data-wow-delay="200ms" data-wow-duration="1500ms">
				<img src="{{ asset($content->shape->value) }}" alt="shape">
			</div>
			<div class="quote__shape bobble__animation">
				<img src="{{ asset($content->shape2->value) }}" alt="shape">
			</div>
			<div class="d-flex flex-wrap gap-4 align-items-center justify-content-between">
				<div class="section-header">
					<h5 class="wow fadeInLeft text-white" data-wow-delay="00ms" data-wow-duration="1500ms">
						<svg class="me-1" width="28" height="12" viewBox="0 0 28 12" fill="none"
						     xmlns="http://www.w3.org/2000/svg">
							<rect x="0.75" y="0.75" width="18.5" height="10.5" rx="5.25" stroke="white"
							      stroke-width="1.5" />
							<rect x="8.75" y="0.75" width="18.5" height="10.5" rx="5.25" fill="white"
							      stroke="white" stroke-width="1.5" />
						</svg>
						{{ $content->sub_heading->value }}
					</h5>
					<h2 class="wow fadeInLeft text-white" data-wow-delay="200ms" data-wow-duration="1500ms">{!! $content->heading->value !!}</h2>
				</div>
				<a href="{{ $content->button_link->value }}" class="btn-one wow fadeInUp" data-wow-delay="200ms"
				   data-wow-duration="1500ms">{{ $content->button_title->value }} <i class="fa-regular fa-arrow-right-long"></i></a>
			</div>
		</div>
	</div>
</section>