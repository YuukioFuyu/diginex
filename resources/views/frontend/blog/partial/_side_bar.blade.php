
@php
	$tags = $blogs->flatMap(function ($blog) {
		return json_decode($blog->tag) ?? [];
	})->unique();

	$blogCategory = Cache::rememberForever('blogCategories', function () {
		return App\Models\BlogCategory::with('blog')
			->where('is_active', \App\Constants\Status::TRUE)
			->get();
	});
@endphp

<div class="col-lg-4 order-1 order-lg-2">
	<div class="blog-single__right-item">
		<div class="item sub-bg mb-30">
			<h5 class="title">{{ __('Search') }}</h5>
			<div class="serach-bar light-area">
				<form action="{{ route('blog.filter') }}">
					<input type="text" value="{{ request('search') }}" name="search" placeholder="Search here">
					<button type="submit"><i class="fa-regular fa-magnifying-glass"></i></button>
				</form>
			
			</div>
		</div>
		<div class="item sub-bg mb-30">
			<h5 class="title">{{ __('Category') }}</h5>
			<ul class="category light-area">
				@foreach($blogCategory as $category)
					<li ><a  href="{{ route('blog.filter', ['category' => $category->id]) }}">{{ _tr($category->name) }}</a> <span>({{ $category->blog->count() }})</span></li>
				@endforeach
			
			</ul>
		</div>
		<div class="item sub-bg mb-30">
			<h5 class="title">{{ __('Resent Post') }}</h5>
			<ul class="single-post">
				@foreach($blogs->take(3) as $recent)
					<li>
						<img class="blog-image" src="{{ asset($recent->cover) }}" alt="image">
						<div class="con">
							<span> <x-svg i="calendar"/> {{ $recent->created_at->format('d M, Y') }}</span>
							<h5 class="mt-2"><a href="{{ route('blog.details', $recent->slug) }}" class="primary-hover">{{ _tr($recent->title) }}</a></h5>
						</div>
					</li>
				@endforeach
			
			</ul>
		</div>
		<div class="item sub-bg">
			<h5 class="title">{{ __('Tags') }}</h5>
			<div class="tags light-area">
				@foreach($tags as $tag)
					<a href="{{ route('blog.filter', ['tag' => $tag->value]) }}">{{ $tag->value }}</a>
				@endforeach
			</div>
		</div>
	</div>
</div>