<?php

namespace App\Http\Middleware;

use App\Http\Helpers\SellerPermissionHelper;
use App\Models\ClientService\Form;
use App\Models\ClientService\Service;
use App\Models\QRCode;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LimitCheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $type = null, $json = null)
    {
        $seller_id = Auth::guard('seller')->user()->id;
        $currentPackage = SellerPermissionHelper::currentPackagePermission($seller_id);
        if ($currentPackage) {
            if ($type == 'form') {
                $total_form = Form::where('seller_id', $seller_id)->count();
                if ($currentPackage) {
                    if ($total_form >= $currentPackage->number_of_form_add) {
                        $request->session()->flash('warning', 'Your Form limit is exceeded.');
                        return response()->json(['status' => 'success'], 200);
                    }
                }
            } elseif ($type == 'service') {
                $total_service = Service::where('seller_id', $seller_id)->count();
                if ($currentPackage) {
                    if ($total_service >= $currentPackage->number_of_service_add) {
                        $request->session()->flash('warning', 'Your service limit is exceeded.');
                        return response()->json(['status' => 'success'], 200);
                    }
                }
            } elseif ($type == 'service-featured') {
                $total_featured_serive = Service::where([['seller_id', $seller_id], ['is_featured', 'yes']])->count();
                if ($currentPackage) {
                    if ($total_featured_serive >= $currentPackage->number_of_service_featured) {
                        if ($request['is_featured'] == 'yes') {
                            $request->session()->flash('warning', 'Your service featured limit is exceeded.');
                            return back();
                        }
                    }
                }
            } elseif ($type == 'live-chat') {
                if ($currentPackage) {
                    if ($currentPackage->live_chat_status == 0) {
                        $request->session()->flash('warning', 'In your current package live chat is disabled.');
                        return redirect()->route('seller.dashboard');
                    }
                }
            } elseif ($type == 'qr_code_save') {
                $saved_qr = QRCode::where('seller_id', $seller_id)->count();
                if ($currentPackage) {
                    if ($currentPackage->qr_builder_status == 1 && $saved_qr >= $currentPackage->qr_code_save_limit) {
                        $request->session()->flash('warning', 'Your QR Code save limit is exceeded.');
                        return back();
                    }
                }
            } elseif ($type == 'qr_code_status') {
                if ($currentPackage) {
                    if ($currentPackage->qr_builder_status == 0) {
                        $request->session()->flash('warning', 'In you current package QR Code feature is unavailable.');
                        return redirect()->route('seller.dashboard');
                    }
                }
            }
        } else {
            Session::flash('warning', 'Please purchase a new package or extend your current package.');
            if ($json == 'except-json') {
                return back();
            } else {
                return response()->json(['status' => 'success'], 200);
            }
        }

        return $next($request);
    }
}
