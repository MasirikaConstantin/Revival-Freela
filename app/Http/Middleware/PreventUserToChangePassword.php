<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreventUserToChangePassword
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle(Request $request, Closure $next)
  {
    $authUser = Auth::guard('web')->user();

    if (!is_null($authUser->password)) {
      return $next($request);
    } else {
      return redirect()->route('user.dashboard');
    }
  }
}
