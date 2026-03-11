<?php

namespace App\Http\Middleware;

use App\Models\ClientService\ServiceOrder;
use App\Models\Shop\ProductOrder;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

class EnsureUserHasAccess
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

    $id = $request->route('id');

    if (
      URL::current() == Route::is('user.service_order.details') ||
      URL::current() == Route::is('user.service_order.message')
    ) {
      $serviceOrder = ServiceOrder::query()->findOrFail($id);
      if ($authUser->id == $serviceOrder->user_id) {
        if (URL::current() == Route::is('user.service_order.message') && $serviceOrder->payment_status != 'completed') {
          return response()->view('errors.404');
        } else {

          return $next($request);
        }
      } else {
        return response()->view('errors.404');
      }
    } else if (URL::current() == Route::is('user.product_order.details')) {
      $productOrder = ProductOrder::query()->findOrFail($id);

      if ($authUser->id == $productOrder->user_id) {
        return $next($request);
      } else {
        return response()->view('errors.404');
      }
    }
  }
}
