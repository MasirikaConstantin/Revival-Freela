<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Helpers\SellerPermissionHelper;
use App\Models\BasicSettings\Basic;
use App\Models\Language;
use App\Models\Membership;
use App\Models\Package;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\PaymentGateway\OnlineGateway;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class BuyPlanController extends Controller
{
    public function index()
    {
        $membership = Membership::first();
        $abs = Basic::first();
        Config::set('app.timezone', $abs->timezone);



        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['bex'] = $currentLang->basic_extended;
        $data['packages'] = Package::where('status', '1')->where('id', '<>', 999999)->get();

        $nextPackageCount = Membership::query()->where([
            ['seller_id', Auth::guard('seller')->user()->id],
            ['expire_date', '>=', Carbon::now()->toDateString()]
        ])->whereYear('start_date', '<>', '9999')->where('status', '<>', 2)->count();
        //current package
        $data['current_membership'] = Membership::query()->where([
            ['seller_id', Auth::guard('seller')->user()->id],
            ['start_date', '<=', Carbon::now()->toDateString()],
            ['expire_date', '>=', Carbon::now()->toDateString()]
        ])->where('status', 1)->whereYear('start_date', '<>', '9999')->first();
        if ($data['current_membership'] != null) {
            $countCurrMem = Membership::query()->where([
                ['seller_id', Auth::guard('seller')->user()->id],
                ['start_date', '<=', Carbon::now()->toDateString()],
                ['expire_date', '>=', Carbon::now()->toDateString()]
            ])->where('status', 1)->whereYear('start_date', '<>', '9999')->count();
            if ($countCurrMem > 1) {
                $data['next_membership'] = Membership::query()->where([
                    ['seller_id', Auth::guard('seller')->user()->id],
                    ['start_date', '<=', Carbon::now()->toDateString()],
                    ['expire_date', '>=', Carbon::now()->toDateString()]
                ])->where('status', '<>', 2)->whereYear('start_date', '<>', '9999')->orderBy('id', 'DESC')->first();
            } else {
                $data['next_membership'] = Membership::query()->where([
                    ['seller_id', Auth::guard('seller')->user()->id],
                    ['start_date', '>', $data['current_membership']->expire_date]
                ])->whereYear('start_date', '<>', '9999')->where('status', '<>', 2)->first();
            }
            $data['next_package'] = $data['next_membership'] ? Package::query()->where('id', $data['next_membership']->package_id)->first() : null;
        } else {
            $data['next_package'] = null;
        }
        $data['current_package'] = $data['current_membership'] ? Package::query()->where('id', $data['current_membership']->package_id)->first() : null;
        $data['package_count'] = $nextPackageCount;

        return view('seller.buy_plan.index', $data);
    }

    public function checkout($package_id)
    {
        $packageCount = Membership::query()->where([
            ['seller_id', Auth::guard('seller')->user()->id],
            ['expire_date', '>=', Carbon::now()->toDateString()]
        ])->whereYear('start_date', '<>', '9999')->where('status', '<>', 2)->count();

        $hasPendingMemb = SellerPermissionHelper::hasPendingMembership(Auth::guard('seller')->user()->id);


        if ($hasPendingMemb) {
            Session::flash('warning', 'You already have a Pending Membership Request.');
            return back();
        }
        if ($packageCount >= 2) {
            Session::flash('warning', 'You have another package to activate after the current package expires. You cannot purchase / extend any package, until the next package is activated');
            return back();
        }

        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()
                ->get('lang'))
                ->first();
        } else {
            $currentLang = Language::where('is_default', 1)
                ->first();
        }
        $be = $currentLang->basic_extended;
        $online = OnlineGateway::query()->where('status', 1)->get();
        $offline = OfflineGateway::where('status', 1)->orderBy('serial_number', 'asc')->get();
        $data['offline'] = $offline;
        $data['payment_methods'] = $online->concat($offline);

        $data['package'] = Package::query()->findOrFail($package_id);
        $data['membership'] = Membership::query()->where([
            ['seller_id', Auth::guard('seller')->user()->id],
            ['expire_date', '>=', \Carbon\Carbon::now()->format('Y-m-d')]
        ])->where('status', '<>', 2)->whereYear('start_date', '<>', '9999')
            ->latest()
            ->first();
        $data['previousPackage'] = null;
        if (!is_null($data['membership'])) {
            $data['previousPackage'] = Package::query()
                ->where('id', $data['membership']->package_id)
                ->first();
        }
        $data['bex'] = $be;

        $stripe = OnlineGateway::where('keyword', 'stripe')->first();
        $stripe_info = json_decode($stripe->information, true);
        $data['stripe_key'] = $stripe_info['key'];

        return view('seller.buy_plan.checkout', $data);
    }
}
