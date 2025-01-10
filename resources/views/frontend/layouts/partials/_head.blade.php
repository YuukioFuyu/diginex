<head>
	<meta charset="{{ setting('meta_charset') }}">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="title" content="{{ setting('meta_title') }}">
	<meta name="description" content="{{ setting('meta_description') }}">
	<meta name="keywords" content="{{ setting('meta_keywords') }}" />
	<meta property="og:image" content="{{ asset(setting('meta_image')) }}" />

	<title>@yield('title') - {{ setting('site_title')  }}</title>
	<link rel="shortcut icon" href="{{ asset(setting('site_favicon')) }}" type="image/x-icon"/>
	<link rel="stylesheet" href="{{ asset('general/css/bootstrap.min.css') }}">

	<link rel="stylesheet" href="{{ asset('frontend/css/meanmenu.css') }}">
	<link rel="stylesheet" href="{{ asset('frontend/css/all.min.css') }}">
	<link rel="stylesheet" href="{{ asset('frontend/css/swiper-bundle.min.css') }}">
	<link rel="stylesheet" href="{{ asset('frontend/css/magnific-popup.css') }}">
	<link rel="stylesheet" href="{{ asset('frontend/css/animate.css') }}">
	<link rel="stylesheet" href="{{ asset('frontend/css/nice-select.css') }}">
	<link rel="stylesheet" href="{{ asset('general/css/simple-notify.min.css?v2.01') }}" />
	<link rel="stylesheet" href="{{ asset('frontend/css/style.css?v2.01') }}">
	<link rel="stylesheet" href="{{ asset('frontend/css/custom.css?v2.01') }}">

	@if(setting('site_preloader') !== 'none')
		<link rel="stylesheet" href="{{ asset('frontend/css/preloader.css?v2.01') }}">
	@endif

	{{--Custom CSS--}}
	@php($css = \App\Models\SiteCustom::value('css'))
	@if($css->is_active)
		<style>
			{{ $css->value }}
		</style>
	@endif


	{{--site color set dynamically--}}
	@include('frontend.layouts.partials._site_color')
	{{--end site color --}}

	@yield('style')
	@stack('style')
</head>
