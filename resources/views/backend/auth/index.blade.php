@extends('backend.layouts.auth')
@section('content')
	<main>
		<section class="vh-lg-100 mt-5 mt-lg-0 bg-soft d-flex align-items-center">
			<div class="container">
				<h1 class="d-flex align-items-center justify-content-center mb-4 h3">@yield('title')</h1>
				<div class="row justify-content-center form-bg-image" data-background-lg="{{ asset(setting('login_bg')) }}">
					<div class="col-12 d-flex align-items-center justify-content-center">
						<div class="bg-white shadow border-0 rounded border-light p-4 p-lg-5 w-100 fmxw-500">
							<div class="text-center text-md-center mb-4 mt-md-0">
								<a href="/">
									<img height="40" src="{{ asset(setting('dark_logo')) }}" alt="">
								</a>
							</div>
							
							@if ($errors->any())
								<div class="alert alert-warning alert-dismissible fade show" role="alert">
									@foreach($errors->all() as $error)
										<strong>{{$error}}</strong>
									@endforeach
									<button type="button" class="btn-close" data-bs-dismiss="alert"
									        aria-label="Close"></button>
								</div>
							@endif
							@if(session('status'))
								<div class="alert alert-success alert-dismissible fade show" role="alert">
									<strong>{{ session('status') }}</strong>
									<button type="button" class="btn-close" data-bs-dismiss="alert"
									        aria-label="Close"></button>
								</div>
							@endif
							
							{{--Auth Form--}}
							@yield('auth_form')
						
						</div>
					</div>
				</div>
			</div>
		</section>
	</main>
@endsection
