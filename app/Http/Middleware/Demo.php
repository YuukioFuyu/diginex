<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class Demo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (Config::get('app.demo')) {
            if (in_array($request->method(), ['POST', 'PUT', 'DELETE'])) {
                if ($request->ajax()) {
                    return response()->json(['status' => 'warning', 'message' => 'You cannot change anything in this demo version'], 200);
                }

                notifyEvs('warning', 'You cannot change anything in this demo version');
                return redirect()->back();
            }
        }

        return $next($request);

    }
}
