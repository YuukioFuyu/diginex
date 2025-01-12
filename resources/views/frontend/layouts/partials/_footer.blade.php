
@if(setting('footer_visibility'))
	{{-- Footer area start here --}}
<footer class="footer-two-area secondary-bg">
	<div class="footer__shape-regular-left wow slideInLeft" data-wow-delay="00ms" data-wow-duration="1500ms">
		<img src="{{ asset(setting('footer_slide_left_regular')) }}" alt="shape">
	</div>
	<div class="footer__shape-solid-left wow slideInLeft" data-wow-delay="200ms" data-wow-duration="1500ms">
		<img class="sway_Y__animation" src="{{ asset(setting('footer_slide_left_solid')) }}" alt="shape">
	</div>
	<div class="footer__shape-solid-right wow slideInRight" data-wow-delay="00ms" data-wow-duration="1500ms">
		<img class="sway_Y__animation" src="{{ asset(setting('footer_right_regular')) }}" alt="shape">
	</div>
	<div class="footer__shape-regular-right wow slideInRight" data-wow-delay="200ms" data-wow-duration="1500ms">
		<img src="{{ asset(setting('footer_right_solid')) }}" alt="shape">
	</div>
	<div class="footer__shadow-shape">
		<img src="{{ asset(setting('footer_shadow_shape')) }}" alt="shodow">
	</div>
	<div class="container">
		<div class="footer__wrp pt-100 pb-100">
			<div class="footer__item item-big wow fadeInUp" data-wow-delay="00ms" data-wow-duration="1500ms">
				<a href="{{ route('home') }}" class="logo mb-30">
					<img src="{{ asset(setting('light_logo')) }}" alt="image">
				</a>
				<p>{{ $contactInfo->content->footer_description->value }}</p>
				<div class="social-icon">
					@foreach($socials as $social)
						<a href="{{ $social->url }}" target="{{ $social->target }}"><i class="{{ $social->icon_class }}"></i></a>
					@endforeach
				</div>
			</div>


			<div class="footer__item item-sm wow fadeInUp" data-wow-delay="200ms" data-wow-duration="1500ms">
				<h3 class="footer-title">{{ __('Our Services') }}</h3>
				<ul>
					@foreach(\App\Models\Services::getAllWithCache(5) as $service)
						<li><a href="{{ route('service.details', ['slug' => $service->slug]) }}"><i
									class="fa-regular fa-angles-right me-1"></i> {{ _tr($service->name) }}</a></li>
					@endforeach
				</ul>
			</div>

			<div class="footer__item item-sm wow fadeInUp" data-wow-delay="200ms" data-wow-duration="1500ms">
				<h3 class="footer-title">{{ __('Quick Link') }}</h3>
				<ul>
					@foreach($navigations as $nav)
						<li><a href="{{ $nav->slug == '/' ? route('home') : $nav->slug }}" target="{{ $nav->target }}"><i class="fa-regular fa-angles-right me-1"></i> {{ _tr($nav->name) }}</a></li>
					@endforeach
				</ul>
			</div>


			<div class="footer__item item-big wow fadeInUp" data-wow-delay="600ms" data-wow-duration="1500ms">
				<h3 class="footer-title">{{ __('Contact Us') }}</h3>
				<p class="mb-20">{{ $contactInfo->content->address->value }}</p>
				<ul class="footer-contact">
					<li>
						<i class="fa-regular fa-clock"></i>
						<div class="info">
							<h5>
								{{ __('Opening Hours') }}:
							</h5>
							<p>{{ $contactInfo->content->opening_hours->value }}</p>
						</div>
					</li>
					<li>
						<i class="fa-duotone fa-phone"></i>
						<div class="info">
							<h5>
								{{ __('Phone Call') }}:
							</h5>
							<p>{{ $contactInfo->content->contact_number->value }}</p>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="footer__copyright">
		<div class="container">
			<div
				class="d-flex gap-1 flex-wrap align-items-center justify-content-md-between justify-content-center">
				<p class="wow fadeInDown" data-wow-delay="00ms" data-wow-duration="1500ms">{!! footer_copyright() !!}</p>
				<ul class="d-flex align-items-center gap-4 wow fadeInDown" data-wow-delay="200ms"
				    data-wow-duration="1500ms">
					<li><a href="{{ setting('terms_condition_link') }}">{{ __('Terms & Conditions') }}</a></li>
					<li><a href="{{ setting('privacy_policy_link') }}">{{ __('Privacy Policy') }}</a></li>
				</ul>
			</div>
		</div>
	</div>
</footer>
{{-- Footer area end here --}}
@endif
{{-- Back to top area start here --}}
@if(setting('scroll_up'))
	<div class="scroll-up">
		<x-svg i="scroll-circle"/>
	</div>
@endif

{{-- Back to top area end here
 --}}
