<div class="banner-video-area light-area">
	<div class="container">
		<div class="banner-video__wrp image">
			<img src="{{ asset($data->content->video_image->value) }}" alt="image">
			<div class="banner-video__video-btn">
				<div class="video-btn video-pulse wow fadeIn" data-wow-delay="200ms" data-wow-duration="1500ms">
					<a class="video-popup" href="{{ $data->content->video_link->value }}"><i
							class="fa-solid fa-play"></i></a>
				</div>
			</div>
		</div>
	</div>
</div>