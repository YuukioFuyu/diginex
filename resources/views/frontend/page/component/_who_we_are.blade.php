<section class="about-two-area pt-120">
	<div class="about-two__shape">
		<img src="{{ asset($data->content->shape->value) }}" alt="shape">
	</div>
	<div class="container">
		<div class="row g-4">
			<div class="col-xl-6 wow fadeInRight" data-wow-delay="200ms" data-wow-duration="1500ms">
				<div class="about-two__left-item">
					<div class="dots">
						<img class="sway_Y__animation" src="{{ asset($data->content->dot->value) }}" alt="shape">
					</div>
					<div class="shape-halper">
						<img class="sway__animation" src="{{ asset($data->content->circle->value) }}"
						     alt="shape">
					</div>
					<div class="image big-image">
						<img src="{{ asset($data->content->big_image->value) }}" alt="image">
					</div>
					<div class="image sm-image">
						<img src="{{ asset($data->content->sm_image->value) }}" alt="image">
					</div>
					<div class="circle-shape">
						<img class="animation__rotate" src="{{ asset($data->content->circle_two->value) }}"
						     alt="shape">
					</div>
				</div>
			</div>
			<div class="col-xl-6">
				<div class="section-header mb-40">
					<h5 class="wow fadeInUp" data-wow-delay="00ms" data-wow-duration="1500ms">
						<img class="me-1" src="{{ asset($data->content->title_icon->value) }}" alt="icon">
						{{ $data->content->sub_heading->value }}
					</h5>
					<h2 class="wow fadeInUp" data-wow-delay="200ms" data-wow-duration="1500ms">{{ $data->content->heading->value }} </h2>
					<p class="wow fadeInUp" data-wow-delay="400ms" data-wow-duration="1500ms">{{ $data->content->summary->value }}</p>
				</div>
				<div class="about-two__right-item wow fadeInDown" data-wow-delay="200ms"
				     data-wow-duration="1500ms">
					<ul>
						<li><i class="fa-solid fa-check"></i>{{ $data->content->list_line_1->value }}</li>
						<li><i class="fa-solid fa-check"></i>{{ $data->content->list_line_2->value }}</li>
					</ul>
					<ul>
						<li><i class="fa-solid fa-check"></i>{{ $data->content->list_line_3->value }}</li>
						<li><i class="fa-solid fa-check"></i>{{ $data->content->list_line_4->value }}</li>
					</ul>
				</div>
				<div class="about__info mt-50 wow fadeInDown" data-wow-delay="400ms" data-wow-duration="1500ms">
					<a href="{{ $data->content->button_link->value }}" class="btn-one">{{ $data->content->button_text->value }} <i class="fa-regular fa-arrow-right-long"></i></a>
					<img src="{{ asset($data->content->signature->value) }}" alt="singature">
				</div>
			</div>
		</div>
	</div>
</section>