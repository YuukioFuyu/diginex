<section class="faq-area sub-bg pt-120 pb-120">
	<div class="faq__shape">
		<img class="sway__animationX" src="{{ asset($data->content->shape->value) }}" alt="shape">
	</div>
	<div class="container">
		<div class="row g-4">
			<div class="col-lg-5 pe-2 pe-lg-5">
				<div class="faq__image image wow fadeInRight" data-wow-delay="200ms" data-wow-duration="1500ms">
					<div class="faq__line sway__animation">
						<img src="{{ asset($data->content->line->value) }}" alt="image">
					</div>
					<img src="{{ asset($data->content->image->value) }}" alt="image">
				</div>
			</div>
			<div class="col-lg-7 mt-60">
				<div class="section-header mb-40">
					<h5 class="wow fadeInUp" data-wow-delay="00ms" data-wow-duration="1500ms">
						<img class="me-1" src="{{ asset($data->content->title_icon->value) }}" alt="icon">
						{{ $data->content->sub_heading->value }}
					</h5>
					<h2 class="wow fadeInUp" data-wow-delay="200ms" data-wow-duration="1500ms"> {{ $data->content->heading->value }}</h2>
				</div>
				<div class="faq__item">
					<div class="accordion" id="accordionExample">
						@foreach($data->items as $content)
							@php($faq = $content->content)
						<div class="accordion-item wow fadeInDown shadow border-none {{ str_replace('_', '-', $theme) }}" data-wow-delay="{{ $loop->iteration }}00ms"
						     data-wow-duration="1500ms">
							<h2 class="accordion-header" id="headingOne{{$content->id}}">
								<button class="accordion-button @if($loop->iteration != 1 ) collapsed @endif" type="button" data-bs-toggle="collapse"
								        data-bs-target="#collapseOne{{$content->id}}" aria-expanded="@if($loop->iteration == 1 ) true @else false @endif"
								        aria-controls="collapseOne{{$content->id}}">
									{{ $faq->question->value }}
								</button>
							</h2>
							<div id="collapseOne{{$content->id}}" class="accordion-collapse collapse @if($loop->iteration == 1 ) show @endif "
							     aria-labelledby="headingOne{{$content->id}}" data-bs-parent="#accordionExample">
								<div class="accordion-body">
									<p> {{ $faq->answer->value }}</p>
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
