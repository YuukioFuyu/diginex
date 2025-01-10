@extends('frontend.layouts.app')
@section('title')
	{{ __('Home') }}
@endsection
@section('content')
	@foreach($components as $data)
		@includeIf('frontend.page.component._'.$data->section)
	@endforeach
@endsection

