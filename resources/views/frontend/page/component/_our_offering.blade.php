<section class="offer-area secondary-bg pt-120 pb-200">
	<div class="offer__shadow wow fadeIn" data-wow-delay="200ms" data-wow-duration="1500ms">
		<img src="{{ asset($data->content->shadow_shape->value) }}" alt="shadow">
	</div>
	<div class="offer__shape-left">
		<img class="wow fadeInUpBig" data-wow-delay="400ms" data-wow-duration="1500ms"
		     src="{{ asset($data->content->shape_left->value) }}" alt="shape">
	</div>
	<div class="offer__shape-right">
		<img class="wow fadeInDownBig" data-wow-delay="400ms" data-wow-duration="1500ms"
		     src="{{ asset($data->content->shape_right->value) }}" alt="shape">
	</div>
	<div class="container">
		<div class="d-flex gap-4 flex-wrap align-items-center justify-content-between mb-95">
			<div class="section-header">
				<h5 class="wow fadeInLeft" data-wow-delay="00ms" data-wow-duration="1500ms">
					<img class="me-1" src="{{ asset($data->content->title_icon->value) }}" alt="icon">
					{{ $data->content->sub_heading->value }}
				</h5>
				<h2 class="text-white wow fadeInLeft" data-wow-delay="200ms" data-wow-duration="1500ms">
					{!! nl2br(wordwrap($data->content->heading->value, 30, "<br>")) !!}
				</h2>
			</div>
			<a href="{{ $data->content->button_link->value }}" class="btn-one wow fadeInUp" data-wow-delay="200ms"
			   data-wow-duration="1500ms">{{ $data->content->button_text->value }} <i class="fa-regular fa-arrow-right-long"></i></a>
		</div>
		<div class="row g-4">
			@foreach($data->items as $item)
				@php($content = $item->content)
				<div class="col-lg-2 col-md-4 col-sm-6 wow bounceInUp" data-wow-delay="{{ $loop->iteration }}00ms"
					     data-wow-duration="1500ms">
						<div class="offer__item">
							<div class="shape-top">
								<img src="{{ asset($content->shape_top->value) }}" alt="shape">
							</div>
							<div class="shape-bottom">
								<img src="{{ asset($content->shape_bottom->value) }}" alt="shape">
							</div>
							<div class="offer__icon">
								<img src="{{ asset($content->svg_icon->value) }}" alt="icon">
							</div>
							<h4 class="text-white mt-20">{{ $content->title->value }}</h4>
						</div>
					</div>
			@endforeach
		</div>
	</div>
</section>
