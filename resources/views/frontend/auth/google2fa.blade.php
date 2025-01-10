@extends('frontend.layouts.user.auth')
@section('title')
	{{ __('Google 2FA Verification') }}
@endsection
@section('user-auth-content')
	<form method="POST" action="{{ route('user.profile.2fa-verify') }}" class="mt-4">
		@csrf
		<div class="form-group">
			<div class="form-group mb-4">
				<label for="one_time_password" class="form-label" >{{ __('Enter Your One-time PIN') }}</label>
				<div class="input-group">
                    <span class="input-group-text" id="basic-addon2">
                        <x-svg i="lock"/>
                    </span>
					<input type="password" placeholder="One-time PIN" name="one_time_password"   class="form-control" id="one_time_password" required>
				</div>
			</div>
		</div>
		<div class="d-grid">
			<button type="submit" class="btn btn-primary"> <x-icon name="login"/> {{ __('Authenticate Now') }}</button>
		</div>
	</form>
@endsection
