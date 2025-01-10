@extends('frontend.layouts.user.auth')
@section('title',__('Register'))
@section('user-auth-content')
    <div class="card card-md">
        <div class="card-body">
            <h2 class="mt-4 text-center">💪 {{ __('Create New Account') }}</h2>
            <h4 class="mb-4 text-muted text-center">{{ __('Sign up to continue with') }} {{ setting('site_title') }}</h4>
            @if ($errors->any())
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    @foreach($errors->all() as $error)
                        <strong>{{$error}}</strong>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                </div>
            @endif
            <form action="{{ route('register') }}" method="post" autocomplete="off" novalidate>
                @csrf

                <div class="row">
                    <div class="col-sm-4 col-md-6">
                        <div class="mb-3">
                            <label class="form-label">{{ __('First Name') }}</label>
                            <input type="text" name="first_name" class="form-control" placeholder="{{ __('Ex: John') }}"
                                   autocomplete="off" required>
                        </div>
                    </div>
                    <div class="col-sm-4 col-md-6">
                        <div class="mb-3">
                            <label class="form-label">{{ __('Last Name') }}</label>
                            <input type="text" name="last_name" class="form-control" placeholder="{{ __('Ex: Doe') }}" autocomplete="off" required>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('Email address') }}</label>
                    <input type="email" name="email" class="form-control" placeholder="{{ __('your@email.com') }}" autocomplete="off" required>
                </div>

               <div class="row">
                   <div class="col-sm-4 col-md-6 mb-3">
                       <label class="form-label">{{ __('New Password') }}</label>
                       <input type="password" name="password" class="form-control" placeholder="New password" autocomplete="off" required>
                   </div>
                   <div class="col-sm-4 col-md-6 mb-3">
                       <label class="form-label">{{ __('Password') }}</label>
                       <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" autocomplete="off" required>
                   </div>
               </div>

                <div class="mb-3">
                    <label class="form-check">
                        <input type="checkbox" name="terms" class="form-check-input">
                        <span class="form-check-label">{{ __('Agree the') }} <a href="./terms-condition" tabindex="-1">{{ __('Terms and Conditions') }}</a>.</span>
                    </label>
                </div>
                <div class="form-footer">
                    <button type="submit" class="btn btn-primary w-100">{{ __('Sign in') }}</button>
                </div>
            </form>
        </div>
    </div>
    <div class="text-center text-secondary mt-3">
        {{ __("Already have account") }}? <a href="{{ route('login') }}" tabindex="-1">{{ __('Sign In') }}</a>
    </div>

@endsection
