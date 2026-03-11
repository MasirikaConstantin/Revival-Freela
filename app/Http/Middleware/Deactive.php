<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Deactive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $type = null)
    {
        if (Session::get('secret_login') != 1) {
            if ($type == 'seller') {
                if (Auth::guard('seller')->user()->status == 0) {
                    if ($request->isMethod('POST') || $request->isMethod('PUT')) {
                        session()->flash('warning', 'Your account is deactive or pending now. Please Contact with admin!');
                        return redirect()->back();
                    }
                }
            } elseif ($type == 'user') {
                if (Auth::guard('web')->user()->status == 0) {
                    Auth::guard('web')->logout();
                    Session::flash('error', 'Your account has been banned!');
                    return redirect()->route('user.login');
                }
            }
        }
        return $next($request);
    }
}
