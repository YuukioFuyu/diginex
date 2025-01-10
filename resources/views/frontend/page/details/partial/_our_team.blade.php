<section class="team-single pt-120 pb-120">
	<div class="container">
		<div class="row g-4 align-items-center">
			<div class="col-lg-4 col-md-6">
				<div class="team-single__image">
					<img src="{{ asset($content->avatar->value) }}" alt="image">
					<div class="team-info">
						<a href="{{ $content->facebook_profile->value }}"><i class="fa-brands fa-facebook-f"></i></a>
						<a href="{{ $content->instagram_profile->value }}"><i class="fa-brands fa-instagram"></i></a>
						<a href="{{ $content->linkedin_profile->value }}"><i class="fa-brands fa-linkedin-in"></i></a>
						<a href="{{ $content->pinterest_profile->value }}"><i class="fa-brands fa-pinterest-p"></i></a>
					</div>
				</div>
			</div>
			<div class="col-lg-8">
				<div class="team-single__content">
					<div class="title pb-20 mb-20 bor-bottom">
						<h3>{{ $content->name->value }}</h3>
						<span class="primary-color mt-1">{{ $content->work_at->value }}</span>
					</div>
					<div class="team-single__info">
						<h4 class="pb-2">{{ $content->info_title->value }}</h4>
						<p class="mb-20">
							{{ $content->info->value }}
						</p>
					
						<div class="skills mt-40">
							<div class="row g-4">
								<div class="col-md-6">
									<div class="experience-progress-wrapper">
										<div class="experience-progress pb-4">
											<div
												class="experience-title-wrapper d-flex align-items-center justify-content-between">
												<h5 class="experience-title pb-2">{{ $content->skill_1->value }}</h5>
												<span class="exp" style="left:{{ (int)$content->skill_experience_1->value }}%">{{(int) $content->skill_experience_1->value }}%</span>
											</div>
											<div class="progress">
												<div class="progress-bar wow slideInLeft"
												     data-wow-duration=".8s" role="progressbar"
												     style="width: {{ (int)$content->skill_experience_1->value }}%;" aria-valuenow="95" aria-valuemin="0"
												     aria-valuemax="100"></div>
											</div>
										</div>
										<div class="experience-progress pb-4">
											<div
												class="experience-title-wrapper d-flex justify-content-between align-items-center">
												<h5 class="experience-title pb-2">{{ $content->skill_2->value }}</h5>
												<span style="left:{{ (int)$content->skill_experience_2->value }}%">{{(int) $content->skill_experience_2->value }}%</span>
											</div>
											<div class="progress">
												<div class="progress-bar wow slideInLeft"
												     data-wow-duration=".9s" role="progressbar"
												     style="width: {{ (int)$content->skill_experience_1->value }}%;" aria-valuenow="95" aria-valuemin="0"
												     aria-valuemax="100"></div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="experience-progress-wrapper">
										<div class="experience-progress pb-4">
											<div
												class="experience-title-wrapper d-flex align-items-center justify-content-between">
												<h5 class="experience-title pb-2">{{ $content->skill_3->value }}</h5>
												<span class="exp" style="left:{{ (int)$content->skill_experience_3->value }}%">{{ (int) $content->skill_experience_3->value }}%</span>
											</div>
											<div class="progress">
												<div class="progress-bar wow slideInLeft"
												     data-wow-duration=".8s" role="progressbar"
												     style="width: {{ (int)$content->skill_experience_3->value }}%;" aria-valuenow="95" aria-valuemin="0"
												     aria-valuemax="100"></div>
											</div>
										</div>
										<div class="experience-progress pb-4">
											<div
												class="experience-title-wrapper d-flex justify-content-between align-items-center">
												<h5 class="experience-title pb-2">{{ $content->skill_4->value }}</h5>
												<span style="left:{{ (int)$content->skill_experience_4->value }}%">{{ (int) $content->skill_experience_4->value }}%</span>
											</div>
											<div class="progress">
												<div class="progress-bar wow slideInLeft"
												     data-wow-duration=".9s" role="progressbar"
												     style="width: {{ (int)$content->skill_experience_4->value }}%;" aria-valuenow="98" aria-valuemin="0"
												     aria-valuemax="100"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="team-single-history mt-60">
		<div class="container">
			<div class="title pb-30 mb-30 bor-bottom">
				<h3>{{ $content->description_title->value }}</h3>
			</div>
			{!! $content->description->value !!}
		</div>
	</div>
</section>