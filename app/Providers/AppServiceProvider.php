<?php

namespace App\Providers;

use App\Models\Language;
use App\Services\BalanceService;
use App\Services\NotificationTemplateService;
use App\Services\SubscriptionService;
use App\Services\TransactionService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Singleton for the TransactionService
        $this->app->singleton('transaction', function ($app) {
            return new TransactionService;
        });

        // Singleton for the BalanceService
        $this->app->singleton('balance', function ($app) {
            return new BalanceService;
        });

        // Singleton for the SubscriptionService
        $this->app->singleton('subscription', function ($app) {
            return new SubscriptionService;
        });

        // Singleton for the NotificationTemplateService
        $this->app->singleton('notification_template', function ($app) {
            return new NotificationTemplateService;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Only generate the app key if it hasn't been set, and do it once during deployment
        if (env('APP_KEY') === null) {
            Artisan::call('key:generate --force');
        }

        config()->set([
            'app.timezone' => setting('site_timezone', 'UTC'),
            'app.env' => setting('site_environment', 'local'),
            'app.debug' => setting('development_mode', true),
            'app.locale' => Language::default()->code ?? 'en',
            'app.default_language' => Language::default()->code ?? 'en',
            'app.rtl' => Language::default()->is_rtl ? 'rtl' : 'ltr',
        ]);

        // Set default pagination view
        Paginator::defaultView('general.pagination.default');
    }
}
