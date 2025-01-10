<section class="blog-area pt-120 pb-120">
	<div class="container">
		<div class="section-header text-center mb-60">
			<h5 class="wow fadeInUp" data-wow-delay="00ms" data-wow-duration="1500ms">
				<img class="me-1" src="{{ asset($data->content->title_icon->value) }}" alt="icon">
				{{ $data->content->sub_heading->value }}
			</h5>
			<h2 class="wow fadeInUp" data-wow-delay="200ms" data-wow-duration="1500ms"> {{ $data->content->heading->value }} </h2>
		</div>
		<div class="row g-4">
		@foreach($blogs->take(3) as $blog)
			<div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="{{ $loop->iteration }}00ms"
			     data-wow-duration="1500ms">
				<div class="blog__item">
					<a href="{{ route('blog.details', ['slug' => $blog->slug]) }}" class="blog__image d-block image">
						<img class="component-blog-cover" src="{{ asset($blog->cover) }}" alt="image">
						<div class="blog-tag">
							<h3 class="text-white">{{ $blog->created_at->format('d') }}</h3>
							<span class="text-white">{{ $blog->created_at->format('M') }}</span>
						</div>
					</a>
					<div class="blog__content">
						<ul class="blog-info pb-20 bor-bottom mb-20">
							<li>
								<x-svg i="user"/>
								<span>{{ __('By') }} {{ $blog->author->first_name . ' ' . $blog->author->last_name }}</span>
							</li>
						</ul>
						<h3><a href="{{ route('blog.details', ['slug' => $blog->slug]) }}" class="primary-hover">{{ _tr($blog->title) }}</a></h3>
						<a class="mt-25 read-more-btn" href="{{ route('blog.details', ['slug' => $blog->slug]) }}">{{ __('Read More') }} <i
								class="fa-regular fa-arrow-right-long"></i></a>
					</div>
				</div>
			</div>
		@endforeach
		</div>
	</div>
</section>