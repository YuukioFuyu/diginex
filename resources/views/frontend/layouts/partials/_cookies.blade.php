{{--  Codes --}}
<div class="cookies-wrapper light-area">
	<div class="title-box">
		<x-svg i="cookie"/>
		<h3>{{ setting('cookie_title') }}</h3>
	</div>
	<div class="info">
		<p>
			{{ setting('cookie_summary') }} <a href="{{ route('page', setting('cookie_page')) }}"> {{ __('Read more') }}...</a>
		</p>
	</div>
	<div class="buttons">
		<button class="button wow fadeInUp  btn-one" id="acceptBtn">{{ __('Accept') }}</button>
		<button class="button wow fadeInUp  btn-one">{{ __('Decline') }}</button>
	</div>
</div>


