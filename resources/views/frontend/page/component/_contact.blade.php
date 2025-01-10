@php($contact = trim(explode(',', $data->content->contact_number->value)[0]))
<section class="contact-area pt-120 pb-120">
	<div class="container">
		<div class="row g-4">
			<div class="col-lg-5">
				<div class="contact__left-item primary-bg">
					<h3 class="text-white mb-30">{{ $data->content->left_headding->value }}</h3>
					<p class="text-white">{{ $data->content->left_information->value }}</p>
					<ul class="mt-40 mb-40">
						<li>
							<i>
								<x-svg i="call-us"/>
							</i>
							<div>
								<span class="text-white">{{ __('Call Us 7/24') }}</span>
								<h3 class="mt-1"><a class="text-white" href="tel:{{ $contact }}">{{ $contact }}</a>
								</h3>
							</div>
						</li>
						<li>
							<i>
								<x-svg i="mail-us"/>
							</i>
							<div>
								<span class="text-white">{{ __('Make a Quote') }}</span>
								<h3 class="mt-1"><a class="text-white" href="#">{{ $data->content->support_mail->value }}</a>
								</h3>
							</div>
						</li>
						<li>
							<i>
								<x-svg i="location"/>
							</i>
							<div>
								<span class="text-white">{{ __('Location') }}</span>
								<h3 class="mt-1"><a class="text-white" href="#">{{ $data->content->address->value }}</a>
								</h3>
							</div>
						</li>
					</ul>
					<h4 class="text-white mb-20">
						{{ __('Follow Social:') }}
					</h4>
					<div class="social">
					@foreach(\App\Models\Social::where('status', 1)->get()  as $social)
						<a href="{{ $social->url }}" target="{{ $social->target }}"><i class="{{ $social->icon_class }}"></i></a>
					@endforeach
					</div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="contact__right-item">
					<div class="section-header mb-20">
						<h5 class="wow fadeInUp pb-2" data-wow-delay="00ms" data-wow-duration="1500ms">
							<img class="me-1" src="{{ asset('general/static/icon/section-title.png') }}" alt="icon">
							{{ $data->content->button_title->value }}
						</h5>
						<h2 class="wow fadeInUp" data-wow-delay="200ms" data-wow-duration="1500ms">{{ __('Ready to Get Started?') }}</h2>
						<p class="wow fadeInUp mt-3" data-wow-delay="400ms" data-wow-duration="1500ms">{{ $data->content->right_information->value }}</p>
					</div>
					<div class="contact__form">
						<form action="{{ route('contact.us') }}" method="post">
							@csrf
							<div class="row">
								<div class="col-6">
									<label for="name">{{ __('Your Name') }}*</label>
									<input id="name" name="name" class="bg-transparent bor" type="text" required
									       placeholder="Your Name">
								</div>
								<div class="col-6">
									<label for="email">{{ __('Your Email') }}*</label>
									<input class="bg-transparent bor" name="email" id="email" type="email" required
									       placeholder="Your Email">
								</div>
							</div>
							<div class="text-area">
								<label for="massage">{{ __('Your Message') }}*</label>
								<textarea class="bg-transparent bor" name="message" id="massage" required
								          placeholder="Write Message"></textarea>
							</div>
							<div class="btn-two">
                                        <span class="btn-circle">
                                        </span>
								<button type="submit" class="btn-one">{{ __('Send Message') }} <i
										class="fa-regular fa-arrow-right-long"></i></button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<div class="contact__map">
	<iframe
		src="https://maps.google.com/maps?q={{ $data->content->Latitude->value }},{{ $data->content->Longitude->value }}&hl=es;z=14&output=embed"
		width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
		referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>
