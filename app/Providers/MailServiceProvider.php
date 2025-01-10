<?php

namespace App\Providers;

use App\Models\Plugin;
use Config;
use Illuminate\Support\ServiceProvider;

class MailServiceProvider extends ServiceProvider
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
        $smtpCredentials = Plugin::credentials('smtp');

        // Set default 'from' name for all mailers
        Config::set('mail.from.name', setting('site_title'));


        // Configure SMTP if enabled and active
        if ($smtpCredentials['status'] === true) {
            Config::set('mail.default', 'smtp');
            Config::set('mail.from.address', $smtpCredentials['email_from']);
            Config::set('mail.mailers.smtp', [
                'transport' => 'smtp',
                'host' => $smtpCredentials['mail_host'],
                'port' => $smtpCredentials['mail_port'],
                'username' => $smtpCredentials['mail_username'],
                'password' => $smtpCredentials['mail_password'],
                'encryption' => $smtpCredentials['fields']['encryption'],
            ]);
        }
    }
}
