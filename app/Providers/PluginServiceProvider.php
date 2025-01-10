<?php

namespace App\Providers;

use App\Models\Plugin;
use Illuminate\Support\ServiceProvider;

class PluginServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $googleReCaptchaCredentials = Plugin::credentials('google-recaptcha');
        config()->set([
            'services.recaptcha.key' => $googleReCaptchaCredentials['recaptcha_key'],
            'services.recaptcha.secret' => $googleReCaptchaCredentials['recaptcha_secret'],
            'services.recaptcha.status' => $googleReCaptchaCredentials['status'],
        ]);
    }
}
