@extends('frontend.blog.index')
@section('title')
	{{ __('Blog Filter Result') }}
@endsection
@section('blog_content')
	<div class="col-lg-8 order-2 order-lg-1">
		@foreach($results as $result)
		<div class="blog-two__grid-item shadow-none mb-30">
			<a href="{{ route('blog.details', $result->slug) }}" class="blog__image d-block image">
				<img src="{{ asset($result->cover) }}" alt="image">
				<div class="blog-tag">
					<h3 class="text-white">{{ $result->created_at->format('d') }}</h3>
					<span class="text-white">{{ $result->created_at->format('M') }}</span>
				</div>
			</a>
			<div class="blog__content">
				<ul class="blog-info mb-20">
					<li>
						<x-svg i="tag"/>
						<a href="{{ route('blog.filter', ['category' => $result->category_id]) }}">{{ _tr($result->category->name) }}</a>
					</li>
					
				</ul>
				<h3><a href="{{ route('blog.details', $result->slug) }}" class="primary-hover">{{ _tr($result->title) }}</a></h3>
				<p class="mt-10">{{ \Str::limit(_tr($result->summary), 100) }}</p>
				<div class="about__info justify-content-between flex-wrap gap-3 mt-25">
					<div class="d-flex gap-2 align-items-center">
						<img class="blog-avatar" src="{{ asset($result->author->avatar) }}" alt="image">
						<div class="info">
							<h5>{{ $result->author->first_name . ' ' . $result->author->last_name }}</h5>
						</div>
					</div>
					<a href="{{ route('blog.details', $result->slug) }}" class="btn-one">{{ __('Read More') }} <i
							class="fa-regular fa-arrow-right-long"></i></a>
				</div>
			</div>
		</div>
		@endforeach

		{{ $results->links('general.pagination.front-pagination') }}
		
		@if(count($results) == 0)
			<div class="alert alert-info">
				{{ __('No result found') }}
			</div>
		@endif
		
	</div>
@endsection