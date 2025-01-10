@extends('frontend.blog.index')
@section('title')
	{{ __('Blog Details') }}
@endsection
@section('blog_content')
	<div class="col-lg-8 order-2 order-lg-1">
		<div class="blog__item blog-single__left-item shadow-none">
			<div class="image">
				<img src="{{ asset($blog->cover) }}" alt="image">
			</div>
			<div class="blog__content p-0">
				<ul class="pb-3 pt-30 bor-bottom d-flex gap-4 flex-wrap align-items-center">
					<li>
						<x-svg i="user"/>
						<span class="primary-hover transition">{{ __('By') }} {{ $blog->author->first_name . ' ' . $blog->author->last_name }}</span>
					</li>
					<li>
						<x-svg i="calendar"/>
						<span>{{ $blog->created_at->format('d M, Y') }}</span>
					</li>
					<li>
						<x-svg i="tag"/>
						<a href="{{ route('blog.filter', ['category' => $blog->category_id]) }}"><span
								class="primary-hover transition"> {{ _tr($blog->category->name) }}</span></a>
					</li>
				</ul>
				<h3 class="blog-single__title mt-20">{{ _tr($blog->title) }}</h3>
				<p class="mb-20 mt-20">{{ _tr($blog->summary) }}</p>
				
				{!!_tr( $blog->content ) !!}
				
				<div class="tags-share mt-40">
					<div class="tags">
						<strong>{{ __('Tags') }}:</strong>
						@foreach(array_slice(json_decode($blog->tag), 0, 3) as $tag)
							<a href="#0">{{ $tag->value }}</a>
						@endforeach
					</div>
					<div class="share">
						<strong>{{ __('Share') }}:</strong>
						@include('frontend.blog.partial._share',['fullUrl' => urlencode(Request::fullUrl())])
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection