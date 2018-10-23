<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IsApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (request()->get('apikey') === config('app.apikey')) {
            return $next($request);
        }
        return response()->json(['errors' => ['not.auth' => 'Not Authorzied']], 500, [], JSON_PRETTY_PRINT);
    }
}
