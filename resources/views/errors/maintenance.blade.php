@extends('errors.layouts')
@section('title')
	{{ setting('maintenance_title') }}
@endsection
@section('error-content')
	<section class="vh-100 d-flex align-items-center justify-content-center">
		<div class="container">
			<div class="row">
				<div class="col-12 text-center d-flex align-items-center justify-content-center">
					<div>
						<img class="img-fluid w-75" src="{{ asset(setting('maintenance_cover')) }}" alt="404 not found">
						<h1 class="mt-5">{{ setting('maintenance_title') }}</h1>
						<p class="lead my-4">{{ setting('maintenance_text') }}</p>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection