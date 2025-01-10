@php($content = $data->content)
<section class="process-area pt-120 pb-120">
	<div class="container">
		<div class="section-header text-center mb-60">
			<h5 class="wow fadeInUp" data-wow-delay="00ms" data-wow-duration="1500ms">
				<img class="me-1" src="{{ asset($content->title_icon->value) }}" alt="icon">
				{{ $content->sub_heading->value }}
			</h5>
			<h2 class="wow fadeInUp" data-wow-delay="200ms" data-wow-duration="1500ms">
				{{ $content->heading->value }}
			</h2>
		</div>
		<div class="row g-4">
			@foreach($data->items as $item)
				@php($item = $item->content)
				<div class="col-lg-4 wow fadeInUp" data-wow-delay="00ms" data-wow-duration="1500ms">
					<div class="process__item mb-100">
						<div class="process-arry bobble__animation">
							<img src="{{ asset($item->array_icon->value) }}" alt="arry-icon">
						</div>
						<div class="process__image">
							<img src="{{ asset($item->process_icon->value) }}" alt="image">
							<span class="process-number">{{ $item->process_number->value }}</span>
						</div>
						<div class="process__content">
							<h4 class="mt-25 mb-10">
								{{ $item->title->value }}
							</h4>
							<p>{{ $item->description->value }}</p>
						</div>
					</div>
				</div>
			@endforeach
		</div>
	</div>
</section>