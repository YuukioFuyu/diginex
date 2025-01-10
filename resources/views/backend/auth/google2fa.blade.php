@extends('backend.auth.index')
@section('title')
	{{ __('Google 2FA Verification') }}
@endsection
@section('auth_form')
	<form method="POST" action="{{ route('admin.profile.2fa-verify') }}" class="mt-4">
		@csrf
		<div class="form-group">
			<div class="form-group mb-4">
				<label for="one_time_password">{{ __('Enter Your One-time PIN') }}</label>
				<div class="input-group">
                    <span class="input-group-text" id="basic-addon2">
                        <x-svg i="lock"/>
                    </span>
					<input type="password" placeholder="One-time PIN" name="one_time_password"   class="form-control" id="one_time_password" required>
				</div>
			</div>
		</div>
		<div class="d-grid">
			<button type="submit" class="btn btn-gray-800">{{ __('Authenticate Now') }}</button>
		</div>
	</form>
@endsection
