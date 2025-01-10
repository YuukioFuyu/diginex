<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ session('dir') ??  config('app.dir') }}" data-theme="{{  $theme }}">
{{--Head Part Included Here--}}
@include('frontend.layouts.partials._head')
<body>

{{-- Preloader area start --}}

@if(setting('site_preloader') == 'parallax')
	<div class="loading">
		<span class="text-capitalize">L</span>
		<span>o</span>
		<span>a</span>
		<span>d</span>
		<span>i</span>
		<span>n</span>
		<span>g</span>
	</div>
	<div id="preloader">
	</div>
@endif
{{-- Preloader area end --}}

{{--Mouse cursor &Header start--}}
@if(setting('header_visibility'))
	@include('frontend.layouts.partials._header_'.setting('header_style'))
@endif

@if(setting('cookie_status'))
	@include('frontend.layouts.partials._cookies')
@endif


{{--Sidebar Part Included Here--}}
@include('frontend.layouts.partials._sidebar')

{{--Dynamic Body Part Included Here--}}
<main>
	@yield('content')
</main>

{{--Footer & Scroll Part Included Here--}}
@include('frontend.layouts.partials._footer')


{{--Script Part Included Here--}}
@include('frontend.layouts.partials._script')


</body>
</html>
