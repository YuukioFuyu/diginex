<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

{{--Head Part Included Here--}}
@include('backend.layouts.partials._head')
<body>

{{--Dynamic Auth Content Will Be Displayed Here--}}
@yield('content')

{{--Script Part Included Here--}}
@include('backend.layouts.partials._script')

</body>
</html>