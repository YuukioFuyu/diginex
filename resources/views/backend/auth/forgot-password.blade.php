@extends('backend.auth.index')
@section('title')
	{{ __('Forgot Password') }}
@endsection
@section('auth_form')
	<form method="POST" action="{{ route('admin.password.email') }}" class="mt-4">
		@csrf
		<div class="form-group mb-4">
			<label for="email">{{ __('Your Email') }}</label>
			<div class="input-group">
                                        <span class="input-group-text" id="basic-addon1">
                                            <x-svg i="mail"/>
                                        </span>
				<input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Email" id="email" autofocus required>
			</div>
		</div>
		<div class="d-grid">
			<button type="submit" class="btn btn-gray-800">{{ __('Email Password Reset Link') }}</button>
		</div>
	</form>
@endsection

