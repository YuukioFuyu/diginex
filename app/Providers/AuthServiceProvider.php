<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Plugin;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Language' => 'App\Policies\LanguagePolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $googleReCaptchaCredentials = Plugin::credentials('google-auth');
        $facebookReCaptchaCredentials = Plugin::credentials('facebook-auth');
        config()->set([
            'services.google.client_id' => $googleReCaptchaCredentials['google_client_id'],
            'services.google.client_secret' => $googleReCaptchaCredentials['google_client_secret'],
            'services.google.status' => $googleReCaptchaCredentials['status'],

            'services.facebook.client_id' => $facebookReCaptchaCredentials['app_id'],
            'services.facebook.client_secret' => $facebookReCaptchaCredentials['app_secret'],
            'services.facebook.status' => $facebookReCaptchaCredentials['status'],
        ]);
    }
}
