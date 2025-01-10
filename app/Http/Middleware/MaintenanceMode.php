<?php

namespace App\Http\Middleware;

use Artisan;
use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceMode
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->app->isDownForMaintenance()) {
            if (! setting('maintenance_mode')) {
                Artisan::call('up');
            }
        } elseif (setting('maintenance_mode')) {
            $secretKey = setting('secret_key');
            Artisan::call("down --render=\"errors.maintenance\" --secret=\"$secretKey\"");
        }

        return $next($request);
    }
}
