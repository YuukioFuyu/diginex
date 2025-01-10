@php($paginateBlogs = paginate($blogs,(int)$data->content->per_page_blog->value))
<section class="blog-single-area pt-120 pb-120">
	<div class="container">
		<div class="row g-4">
			
			<div class="col-lg-8 order-2 order-lg-1">
				@foreach($paginateBlogs as $blog)
					<div class="blog-two__grid-item shadow-none mb-30">
						<a href="{{ route('blog.details', $blog->slug) }}" class="blog__image d-block image">
							<img src="{{ asset($blog->cover) }}" alt="image">
							<div class="blog-tag">
								<h3 class="text-white">{{ $blog->created_at->format('d') }}</h3>
								<span class="text-white">{{ $blog->created_at->format('M') }}</span>
							</div>
						</a>
						<div class="blog__content">
							<ul class="blog-info mb-20">
								<li>
									<x-svg i="tag"/>
									<a href="{{ route('blog.filter', ['category' => $blog->category_id]) }}">{{ _tr($blog->category->name) }}</a>
								</li>
							
							</ul>
							<h3><a href="{{ route('blog.details', $blog->slug) }}" class="primary-hover">{{ _tr($blog->title) }}</a></h3>
							<p class="mt-10">{{ \Str::limit(_tr($blog->summary), 100) }}</p>
							<div class="about__info justify-content-between flex-wrap gap-3 mt-25">
								<div class="d-flex gap-2 align-items-center">
									<img class="blog-avatar" src="{{ asset($blog->author->avatar) }}" alt="image">
									<div class="info">
										<h5>{{ $blog->author->first_name . ' ' . $blog->author->last_name }}</h5>
									</div>
								</div>
								<a href="{{ route('blog.details', $blog->slug) }}" class="btn-one">{{ __('Read More') }} <i
										class="fa-regular fa-arrow-right-long"></i></a>
							</div>
						</div>
					</div>
				@endforeach
				
				{{ $paginateBlogs->links('general.pagination.front-pagination') }}
					@if(count($paginateBlogs) == 0)
						<div class="alert alert-info">
							{{ __('No Data found') }}
						</div>
					@endif
			</div>
			
			@include('frontend.blog.partial._side_bar')
		</div>
	</div>
</section>