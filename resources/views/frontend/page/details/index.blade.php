@extends('frontend.page.index')
@section('title')
	{{ $title }}
@endsection
@section('page_content')
	@includeIf('frontend.page.details.partial._'.$section)
@endsection