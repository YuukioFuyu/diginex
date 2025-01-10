<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {

        $socialUser = Socialite::driver($provider)->user();

        [$firstName, $lastName] = $this->parseName($socialUser->getName());

        // Find or create the user
        $user = User::firstOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $socialUser->getEmail(),
                'password' => bcrypt(Str::random(16)), // Assign a random password
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
            ]
        );

        // Log the user in
        Auth::login($user);

        return redirect()->intended('/user/dashboard');
    }

    /**
     * Parse the full name into first and last names.
     */
    private function parseName($fullName)
    {
        $name = Str::of($fullName)->explode(' ');

        return [$name->get(0) ?? '', $name->get(1) ?? ''];
    }
}
