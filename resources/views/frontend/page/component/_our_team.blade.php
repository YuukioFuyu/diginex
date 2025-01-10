<section class="team-area pt-120 pb-120">
	<div class="container">
		<div class="section-header text-center mb-60">
			<h5 class="wow fadeInUp" data-wow-delay="00ms" data-wow-duration="1500ms">
				<img class="me-1" src="{{ asset($data->content->title_icon->value) }}" alt="icon">
				{{ $data->content->sub_heading->value }}
			</h5>
			<h2 class="wow fadeInUp" data-wow-delay="200ms" data-wow-duration="1500ms">{{ $data->content->heading->value }}</h2>
		</div>
		<div class="row g-4">
            @foreach($data->items  as $team)
				@php($content = $team->content)
			<div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="{{ $loop->iteration }}00ms"
			     data-wow-duration="1500ms">
				<div class="team__item">
					<div class="image">
						<img src="{{ asset($content->avatar->value) }}" alt="image">
					</div>
					<div class="team__content">
						<h4><a class="text-white" href="{{ route('content.details', ['section' => $data->section, 'id' => $team->id]) }}">{{ $content->name->value }}</a></h4>
						<span class="text-white">{{ $content->work_at->value }}</span>
					</div>
					<div class="team__share">
						<ul>
							<li>
								<a href="{{ $content->facebook_profile->value }}"><i class="fa-brands fa-facebook-f"></i></a>
							</li>
							<li><a href="{{ $content->instagram_profile->value }}"><i class="fa-brands fa-instagram"></i></a></li>
							<li><a href="{{ $content->linkedin_profile->value }}"><i class="fa-brands fa-linkedin-in"></i></a></li>
						</ul>
						<button><i class="fa-sharp fa-light fa-share-nodes"></i></button>
					</div>
				</div>
			</div>
			@endforeach
		</div>
	</div>
</section>