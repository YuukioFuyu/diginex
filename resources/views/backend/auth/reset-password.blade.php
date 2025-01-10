@extends('backend.auth.index')
@section('title')
	{{ __('Password Reset') }}
@endsection
@section('auth_form')
	<form method="POST" action="{{ route('admin.password.store') }}" class="mt-4">
		@csrf
		<input type="hidden" name="token" value="{{ $request->route('token') }}">
		<div class="form-group mb-4">
			<label for="email">{{ __('Your Email') }}</label>
			<div class="input-group">
                                        <span class="input-group-text" id="basic-addon1">
                                            <x-svg i="mail"/>
                                        </span>
				<input type="email" name="email" class="form-control" value="{{ old('email', $request->email) }}" placeholder="Email" id="email" autofocus required>
			</div>
		</div>
		<div class="form-group">
			<div class="form-group mb-4">
				<label for="password">{{ __('New Password') }}</label>
				<div class="input-group">
                                            <span class="input-group-text" id="basic-addon2">
                                                <x-svg i="lock"/>
                                            </span>
					<input type="password" name="password" placeholder="New Password" class="form-control" id="password" required>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="form-group mb-4">
				<label for="password">{{ __('Confirm Password') }}</label>
				<div class="input-group">
                                            <span class="input-group-text" id="basic-addon2">
                                                <x-svg i="lock"/>
                                            </span>
					<input type="password" placeholder="Confirm Password" name="password_confirmation" class="form-control" id="password" required>
				</div>
			</div>
		</div>
		<div class="d-grid">
			<button type="submit" class="btn btn-gray-800">{{ __('Reset Password') }}</button>
		</div>
	</form>
@endsection
