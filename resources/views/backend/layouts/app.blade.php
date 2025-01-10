<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ session('dir') ??  config('app.dir') }}">

{{--Head Part Included Here--}}
@include('backend.layouts.partials._head')
<body >
{{--validation error notification--}}
@if ( !$errors->isEmpty() && $errors->any())
	@php
		notifyEvs('error', $errors->first());
		session()->flash('errors', $errors);
        session()->save();
	@endphp
@endif

{{--Navbar Part Included Here For Another Device--}}
@include('backend.layouts.partials._navbar_small_screen')


{{--Sidebar Part Included Here--}}
@include('backend.layouts.partials._sidebar')

<main class="content ">
	{{--Main Navbar Part Included Here--}}
	@include('backend.layouts.partials._navbar')

	{{--Dynamic Content Will Be Displayed Here--}}
	<div class=" main-content">
        @yield('content')
    </div>

    {{-- delete modal --}}
    @include('backend.partials._delete_modal')
</main>
{{--Script Part Included Here--}}
@include('backend.layouts.partials._script')

</body>
</html>
