@php($content = $data->content)
<section class="blog-two-area pb-120">
	<div class="container">
		<div class="d-flex flex-wrap gap-4 align-items-center justify-content-between mb-60">
			<div class="section-header">
				<h5 class="wow fadeInLeft" data-wow-delay="00ms" data-wow-duration="1500ms">
					<svg class="me-1" width="20" height="12" viewBox="0 0 20 12" fill="none"
					     xmlns="http://www.w3.org/2000/svg">
						<rect x="0.75" y="0.748047" width="18.5" height="10.5" rx="5.25" stroke="#3C72FC"
						      stroke-width="1.5"/>
						<mask id="path-2-inside-1_1120_300" fill="white">
							<path
								d="M3 5.99805C3 3.78891 4.79086 1.99805 7 1.99805H13C15.2091 1.99805 17 3.78891 17 5.99805C17 8.20719 15.2091 9.99805 13 9.99805H7C4.79086 9.99805 3 8.20719 3 5.99805Z"/>
						</mask>
						<path
							d="M3 5.99805C3 2.96048 5.46243 0.498047 8.5 0.498047H11.5C14.5376 0.498047 17 2.96048 17 5.99805C17 4.61734 15.2091 3.49805 13 3.49805H7C4.79086 3.49805 3 4.61734 3 5.99805ZM17 5.99805C17 9.03561 14.5376 11.498 11.5 11.498H8.5C5.46243 11.498 3 9.03561 3 5.99805C3 7.37876 4.79086 8.49805 7 8.49805H13C15.2091 8.49805 17 7.37876 17 5.99805ZM3 9.99805V1.99805V9.99805ZM17 1.99805V9.99805V1.99805Z"
							fill="#3C72FC" mask="url(#path-2-inside-1_1120_300)"/>
					</svg>
					{{ $content->sub_heading->value }}
				</h5>
				<h2 class="wow fadeInLeft" data-wow-delay="200ms" data-wow-duration="1500ms">{{ $content->heading->value }}</h2>
			</div>
			<a href="{{ $content->button_link->value }}" class="btn-one wow fadeInUp" data-wow-delay="200ms"
			   data-wow-duration="1500ms">{{ $content->button_text->value }} <i class="fa-regular fa-arrow-right-long"></i></a>
		</div>
		
		<div class="row g-4">
			@foreach($blogs->take(3) as $blog)
				@if($loop->first)
					<div class="col-lg-6 wow fadeInRight" data-wow-delay="200ms" data-wow-duration="1500ms">
						<div class="blog-two__grid-item">
							<a href="{{ route('blog.details', ['slug' => $blog->slug]) }}" class="blog__image d-block image">
								<img src="{{ asset($blog->cover) }}" alt="image">
								<div class="blog-tag">
									<h3 class="text-white">{{ $blog->created_at->format('d') }}</h3>
									<span class="text-white">{{ $blog->created_at->format('M') }}</span>
								</div>
							</a>
							<div class="blog__content">
								<ul class="blog-info mb-20">
									<li>
										<svg width="20" height="20" viewBox="0 0 20 20" fill="none"
										     xmlns="http://www.w3.org/2000/svg">
											<g clip-path="url(#clip0_880_270)">
												<path
													d="M8.05666 18.75H8.05504C7.46832 18.7495 6.91657 18.5207 6.50187 18.1052L0.660341 12.2553C-0.194072 11.3994 -0.194072 10.0065 0.660341 9.15058L8.53478 1.26102C9.3463 0.44792 10.426 0 11.575 0H16.5709C17.7824 0 18.7682 0.985546 18.7682 2.19726V7.17785C18.7682 8.32602 18.3208 9.40532 17.5084 10.2167L9.60951 18.1074C9.19455 18.5218 8.64306 18.75 8.05666 18.75ZM11.575 1.46484C10.8179 1.46484 10.1064 1.75998 9.57163 2.29579L1.69707 10.1853C1.41222 10.4708 1.41222 10.9349 1.69707 11.2203L7.53857 17.0702C7.6767 17.2086 7.86051 17.285 8.05619 17.2851H8.05677C8.1529 17.2854 8.24812 17.2666 8.33694 17.2299C8.42577 17.1931 8.50643 17.1391 8.57427 17.071L16.4732 9.18046C17.0086 8.6458 17.3034 7.93447 17.3034 7.17788V2.19726C17.3034 1.79341 16.9748 1.46484 16.5709 1.46484H11.575ZM13.458 7.43408C12.2465 7.43408 11.2608 6.44853 11.2608 5.23681C11.2608 4.0251 12.2465 3.03955 13.458 3.03955C14.6696 3.03955 15.6553 4.0251 15.6553 5.23681C15.6553 6.44853 14.6696 7.43408 13.458 7.43408ZM13.458 4.50439C13.0542 4.50439 12.7256 4.83296 12.7256 5.23681C12.7256 5.64067 13.0542 5.96924 13.458 5.96924C13.862 5.96924 14.1905 5.64067 14.1905 5.23681C14.1905 4.83296 13.862 4.50439 13.458 4.50439Z"
													fill="#3C72FC"/>
											</g>
											<defs>
												<clipPath>
													<rect width="20" height="20" fill="white"/>
												</clipPath>
											</defs>
										</svg>
										<a href="{{ route('blog.filter', ['category' => $blog->category_id]) }}">{{ _tr($blog->category->name) }}</a>
									</li>
								</ul>
								<h3><a href="{{ route('blog.details', ['slug' => $blog->slug]) }}" class="primary-hover">{{ _tr($blog->title) }}</a></h3>
								<p class="mt-10">{{ Str::limit(_tr($blog->summary),'80') }}</p>
								<div class="about__info justify-content-between flex-wrap gap-3 mt-25">
									<div class="d-flex gap-2 align-items-center">
										<img class="blog-avatar" src="{{ asset($blog->author->avatar) }}" alt="image">
										<div class="info">
											<a href="#0" class="primary-color">{{ __('By Author') }}</a>
											<h5>{{ $blog->author->first_name . ' ' . $blog->author->last_name }}</h5>
										</div>
									</div>
									<a href="{{ route('blog.details', ['slug' => $blog->slug]) }}" class="btn-one">{{ __('Read More') }} <i
											class="fa-regular fa-arrow-right-long"></i></a>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
					@else
						<div class="blog-two__list-item @if($loop->index == 1) mb-30 @endif  wow fadeInUp" data-wow-delay="{{$loop->index}}00ms"
						     data-wow-duration="1500ms">
							<a href="{{ route('blog.details', ['slug' => $blog->slug]) }}" class="blog__image d-block image">
								<img class="blog-square" src="{{ asset($blog->cover) }}" alt="image">
								<div class="blog-tag">
									<h3 class="text-white">{{ $blog->created_at->format('d') }}</h3>
									<span class="text-white">{{ $blog->created_at->format('M') }}</span>
								</div>
							</a>
							<div class="blog__content">
								<ul class="blog-info mb-20">
									<li>
										<svg width="20" height="20" viewBox="0 0 20 20" fill="none"
										     xmlns="http://www.w3.org/2000/svg">
											<g clip-path="url(#clip0_880_270)">
												<path
													d="M8.05666 18.75H8.05504C7.46832 18.7495 6.91657 18.5207 6.50187 18.1052L0.660341 12.2553C-0.194072 11.3994 -0.194072 10.0065 0.660341 9.15058L8.53478 1.26102C9.3463 0.44792 10.426 0 11.575 0H16.5709C17.7824 0 18.7682 0.985546 18.7682 2.19726V7.17785C18.7682 8.32602 18.3208 9.40532 17.5084 10.2167L9.60951 18.1074C9.19455 18.5218 8.64306 18.75 8.05666 18.75ZM11.575 1.46484C10.8179 1.46484 10.1064 1.75998 9.57163 2.29579L1.69707 10.1853C1.41222 10.4708 1.41222 10.9349 1.69707 11.2203L7.53857 17.0702C7.6767 17.2086 7.86051 17.285 8.05619 17.2851H8.05677C8.1529 17.2854 8.24812 17.2666 8.33694 17.2299C8.42577 17.1931 8.50643 17.1391 8.57427 17.071L16.4732 9.18046C17.0086 8.6458 17.3034 7.93447 17.3034 7.17788V2.19726C17.3034 1.79341 16.9748 1.46484 16.5709 1.46484H11.575ZM13.458 7.43408C12.2465 7.43408 11.2608 6.44853 11.2608 5.23681C11.2608 4.0251 12.2465 3.03955 13.458 3.03955C14.6696 3.03955 15.6553 4.0251 15.6553 5.23681C15.6553 6.44853 14.6696 7.43408 13.458 7.43408ZM13.458 4.50439C13.0542 4.50439 12.7256 4.83296 12.7256 5.23681C12.7256 5.64067 13.0542 5.96924 13.458 5.96924C13.862 5.96924 14.1905 5.64067 14.1905 5.23681C14.1905 4.83296 13.862 4.50439 13.458 4.50439Z"
													fill="#3C72FC"/>
											</g>
											<defs>
												<clipPath>
													<rect width="20" height="20" fill="white"/>
												</clipPath>
											</defs>
										</svg>
										<a href="{{ route('blog.filter', ['category' => $blog->category_id]) }}">{{ _tr($blog->category->name) }}</a>
									</li>
								</ul>
								<h3><a href="{{ route('blog.details', ['slug' => $blog->slug]) }}" class="primary-hover">{{ _tr($blog->title) }}</a></h3>
								<div class="about__info mt-30">
									<div class="d-flex gap-2 align-items-center">
										<img class="blog-avatar" src="{{ asset($blog->author->avatar) }}" alt="image">
										<div class="info">
											<a href="#0" class="primary-color">{{ __('By Author') }}</a>
											<h5>{{ $blog->author->first_name . ' ' . $blog->author->last_name }}</h5>
										</div>
									</div>
								</div>
							</div>
						</div>
					@endif
			@endforeach
					</div>
		</div>
	
	
	</div>
</section>