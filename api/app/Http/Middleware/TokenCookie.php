<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class TokenCookie
{
  public function handle($request, Closure $next)
  {
    if ($request->cookie('token')) {
      $request->headers->set('Authorization','Bearer '.$request->cookie('token'));
    }
    if (Auth::guard('api')->user() == null) {
      return response()->json('Invalid Token', 403);
    }
    return $next($request);
  }
}
