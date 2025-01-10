<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class XSS
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if ($request->isMethod('get')) {
            $userInput = $request->all();
            array_walk_recursive($userInput, function (&$value) {
                $value = strip_tags($value);
            });
            $request->merge($userInput);
        }

        return $next($request);

    }
}
