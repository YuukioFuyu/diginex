@extends('frontend.page.index')
@section('title')
	{{ $title }}
@endsection
@section('page_content')
	@foreach($components as $data)
		@includeIf('frontend.page.component._'.$data->section)
	@endforeach
@endsection