@php use App\Constants\Status; @endphp
<div class="col-12 col-xl-12">
	<div class="card">
        <div class="nav-card-header bg-light">
            🔒 {{ __('Two Factor Authentication (2FA)') }}
        </div>
        <div class="card-body border-0 shadow mb-4">
            @if(null !== $user->google2fa_secret)
            <p>{{ __('Two Factor Authentication (2FA) strengthens access security by requiring two methods to verify your identity. It protects against phishing, social engineering, and password brute force attacks.') }}</p>

            @if($user->google2fa_enabled == Status::ACTIVE)
            <p>{{ __('Two Factor Authentication (2FA) is currently enabled.') }}</p>
            <span class="text-muted pb-2">{{ __('Enter Your Login Password') }}</span>
            @else
            {!! app('pragmarx.google2fa')->getQRCodeInline(config('app.name'), $user->email, $user->google2fa_secret) !!}
            <p>{{ __('Scan the QR code with your Google Authenticator App') }}</p>
            <span class="text-muted pb-2">{{ __('Enter the PIN from Google Authenticator App') }}</span>
            @endif

            <form action="{{ route('admin.profile.2fa-auth') }}" method="POST">
                @csrf
                <div class="form-group">
                    <input type="password" name="one_time_password" class="form-control">
                </div>
                <div class="mt-4">
                    @if($user->google2fa_enabled == Status::ACTIVE)
                    <button type="submit" class="btn btn-gray-800 animate-up-2" value="{{ Status::DISABLE }}"
                            name="status">{{ __('Disable 2FA') }}</button>
                    @else
                    <button type="submit" class="btn btn-gray-800 animate-up-2" value="{{ Status::ENABLE }}"
                            name="status">{{ __('Enable 2FA') }}</button>
                    @endif
                </div>
            </form>
            @else
            <div class="mt-3">
                <p class="text-muted">{{ __('Enable Two Factor Authentication to protect your account') }}</p>
                <a href="{{ route('admin.profile.2fa-generate') }}"
                   class="btn btn-gray-800 animate-up-2">{{ __('Generate 2FA Secret') }}</a>
            </div>
            @endif
        </div>
    </div>
</div>
