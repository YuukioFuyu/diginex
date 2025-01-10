<?php

namespace App\Providers;

use App\Constants\Status;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // Load Pusher configuration
        $pusherConfig = pluginCredentials('pusher');
        if ($pusherConfig['status'] === Status::TRUE) {
            // Set up Pusher configuration dynamically
            config()->set('broadcasting.connections.pusher', [
                'driver' => 'pusher',
                'key' => $pusherConfig['pusher_app_key'],
                'secret' => $pusherConfig['pusher_app_secret'],
                'app_id' => $pusherConfig['pusher_app_id'],
                'options' => [
                    'cluster' => $pusherConfig['pusher_app_cluster'],
                    'useTLS' => true,
                    'host' => 'api-ap2.pusher.com',
                    'port' => '443',
                    'scheme' => 'https',
                    'encrypted' => true,
                ],
            ]);
        }

        // Register broadcast routes
        Broadcast::routes(['middleware' => ['web']]);

        // Load channels file for broadcasting
        require base_path('routes/channels.php');
    }
}
