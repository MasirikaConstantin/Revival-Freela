<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\BasicSettings\Basic;
use App\Models\ClientService\Service;
use App\Models\ClientService\ServiceCategory;
use App\Models\ClientService\ServiceOrder;
use App\Models\Follower;
use App\Models\Seller;
use App\Models\SellerInfo;
use App\Models\Skill;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SellerController extends Controller
{
    //index
    public function index(Request $request)
    {
        $misc = new MiscellaneousController();
        $language = $misc->getLanguage();

        $queryResult['seoInfo'] = $language->seoInfo()->select('seller_page_meta_keywords', 'seller_page_meta_description')->first();

        $queryResult['pageHeading'] = $misc->getPageHeading($language);

        $queryResult['breadcrumb'] = $misc->getBreadcrumb();
        $name = $location = null;
        $sellerIds = [];
        if ($request->filled('name')) {
            $name = $request->name;
            $u_infos = Seller::where('username', 'like', '%' . $name . '%')->where('id', '!=', 0)->get();
            $s_infos = SellerInfo::where([['seller_infos.name', 'like', '%' . $name . '%'], ['language_id', $language->id]])->get();

            foreach ($u_infos as $info) {
                if (!in_array($info->id, $sellerIds)) {
                    array_push($sellerIds, $info->id);
                }
            }
            foreach ($s_infos as $s_info) {
                if (!in_array($s_info->seller_id, $sellerIds)) {
                    array_push($sellerIds, $s_info->seller_id);
                }
            }
        }

        if ($request->filled('location')) {
            $location = $request->location;
        }

        if ($request->filled('location')) {
            $seller_contents = SellerInfo::where('country', 'like', '%' . $location . '%')
                ->orWhere('city', 'like', '%' . $location . '%')
                ->orWhere('state', 'like', '%' . $location . '%')
                ->orWhere('zip_code', 'like', '%' . $location . '%')
                ->orWhere('address', 'like', '%' . $location . '%')
                ->get();
            foreach ($seller_contents as $seller_content) {
                if (!in_array($seller_content->seller_id, $sellerIds)) {
                    array_push($sellerIds, $seller_content->seller_id);
                }
            }
        }

        $queryResult['sellers'] = Seller::where('sellers.status', 1)
            ->join('memberships', 'memberships.seller_id', 'sellers.id')
            ->where([
                ['memberships.status', 1],
                ['memberships.start_date', '<=', Carbon::now()->format('Y-m-d')],
                ['memberships.expire_date', '>=', Carbon::now()->format('Y-m-d')],
            ])

            ->when($name, function ($query) use ($sellerIds) {
                return $query->whereIn('sellers.id', $sellerIds);
            })
            ->when($location, function ($query) use ($sellerIds) {
                return $query->whereIn('sellers.id', $sellerIds);
            })
            ->where('sellers.id', '!=', 0)
            ->select('sellers.*', 'sellers.id as sellerId', 'memberships.*')
            ->orderBy('sellers.id', 'asc')
            ->paginate(10);
        return view('frontend.seller.index', $queryResult);
    }
    //details 
    public function details(Request $request)
    {

        $misc = new MiscellaneousController();

        $language = $misc->getLanguage();
        $queryResult['language'] = $language;

        $queryResult['breadcrumb'] = $misc->getBreadcrumb();

        if ($request->admin == true) {
            $seller = Admin::first();
            $seller_id = 0;
            $queryResult['total_service'] = Service::where('seller_id', 0)->count();
        } else {
            $seller = Seller::join('memberships', 'memberships.seller_id', 'sellers.id')
                ->where([
                    ['memberships.status', 1],
                    ['memberships.start_date', '<=', Carbon::now()->format('Y-m-d')],
                    ['memberships.expire_date', '>=', Carbon::now()->format('Y-m-d')],
                ])
                ->where('sellers.username', $request->username)
                ->select('sellers.*')
                ->firstOrFail();
            $sellerInfo = SellerInfo::where([['seller_id', $seller->id], ['language_id', $language->id]])->first();
            $queryResult['skills'] = Skill::where([['language_id', $language->id], ['status', 1]])->get();
            $queryResult['sellerInfo'] = $sellerInfo;
            $seller_id = $seller->id;
        }
        $queryResult['seller'] = $seller;

        $queryResult['categories'] = ServiceCategory::where([['language_id', $language->id], ['status', 1]])->get();

        $all_services = Service::join('service_contents', 'services.id', '=', 'service_contents.service_id')
            ->where([['services.service_status', 1], ['service_contents.language_id', '=', $language->id], ['services.seller_id', $seller_id]])
            ->select('services.id', 'services.thumbnail_image', 'service_contents.title', 'service_contents.slug', 'services.average_rating', 'services.package_lowest_price', 'services.quote_btn_status')
            ->orderByDesc('services.id')
            ->get();
        // review

        $all_services->map(function ($service) {
            $service['reviewCount'] = $service->review()->count();
        });
        // wishlist
        if (Auth::guard('web')->check() == true) {
            $all_services->map(function ($service) {
                $authUser = Auth::guard('web')->user();

                $listedService = $service->wishlist()->where('user_id', $authUser->id)->first();

                if (empty($listedService)) {
                    $service['wishlisted'] = false;
                } else {
                    $service['wishlisted'] = true;
                }
            });
        }

        $queryResult['all_services'] = $all_services;
        $queryResult['order_completed'] = ServiceOrder::where([['order_status', 'completed'], ['seller_id', $seller_id]])->count();
        $queryResult['currencyInfo'] = $this->getCurrencyInfo();
        $queryResult['languageId'] = $language->id;

        $queryResult['followers'] = Follower::where('following_id', $seller->id)->limit(10)->get();
        $queryResult['followings'] = Follower::where([['follower_id', $seller->id], ['type', 'seller']])->limit(10)->get();
        $queryResult['bs'] = Basic::query()->select('google_recaptcha_status', 'to_mail')->first();

        return view('frontend.seller.details', $queryResult);
    }
    public function followers($username)
    {
        $misc = new MiscellaneousController();
        $seller = Seller::where('username', $username)->firstOrFail();
        $language = $misc->getLanguage();
        $queryResult['language'] = $language;

        $queryResult['breadcrumb'] = $misc->getBreadcrumb();
        $queryResult['username'] = $username;
        $queryResult['followers'] = Follower::where('following_id', $seller->id)->paginate(20);
        return view('frontend.seller.followers', $queryResult);
    }
    public function following($username)
    {
        $misc = new MiscellaneousController();
        $seller = Seller::where('username', $username)->firstOrFail();
        $language = $misc->getLanguage();
        $queryResult['language'] = $language;

        $queryResult['breadcrumb'] = $misc->getBreadcrumb();
        $queryResult['username'] = $username;
        $queryResult['followings'] = Follower::where([['follower_id', $seller->id], ['type', 'seller']])->paginate(20);
        return view('frontend.seller.following', $queryResult);
    }

    //contact
    public function contact(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email:rfc,dns',
            'subject' => 'required',
            'message' => 'required'
        ];
        $info = Basic::select('google_recaptcha_status')->first();
        if ($info->google_recaptcha_status == 1) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }
        $messageArray = [];

        if ($info->google_recaptcha_status == 1) {
            $messageArray['g-recaptcha-response.required'] = 'Please verify that you are not a robot.';
            $messageArray['g-recaptcha-response.captcha'] = 'Captcha error! try again later or contact site admin.';
        }

        $validator = Validator::make($request->all(), $rules, $messageArray);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()], 400);
        }


        $be = Basic::select('smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')->firstOrFail();

        $c_message = nl2br($request->message);
        $msg = "<h4>Name : $request->name</h4>
            <h4>Email : $request->email</h4>
            <p>Message : </p> 
            <p>$c_message</p>
            ";

        $data = [
            'to' => $request->seller_email,
            'subject' => $request->subject,
            'message' => $msg,
        ];

        if ($be->smtp_status == 1) {
            try {
                $smtp = [
                    'transport' => 'smtp',
                    'host' => $be->smtp_host,
                    'port' => $be->smtp_port,
                    'encryption' => $be->encryption,
                    'username' => $be->smtp_username,
                    'password' => $be->smtp_password,
                    'timeout' => null,
                    'auth_mode' => null,
                ];
                Config::set('mail.mailers.smtp', $smtp);
            } catch (\Exception $e) {
                Session::flash('error', $e->getMessage());
                return back();
            }
        }
        try {
            if ($be->smtp_status == 1) {
                Mail::send([], [], function (Message $message) use ($data, $be) {
                    $fromMail = $be->from_mail;
                    $fromName = $be->from_name;
                    $message->to($data['to'])
                        ->subject($data['subject'])
                        ->from($fromMail, $fromName)
                        ->html($data['message'], 'text/html');
                });
            }
            Session::flash('success', 'Message sent successfully.');
            return 'success';
        } catch (\Exception $e) {
            Session::flash('error', 'Something went wrong.');
            return 'success';
        }
    }

    public function follow_seller(Request $request)
    {
        if (empty($request->user_id) || empty($request->type)) {
            return redirect()->route('user.login');
        } elseif (empty($request->following_id)) {
            Session::flash('warning', 'Something went wrong.');
            return redirect()->back();
        } else {
            $user_id = $request->user_id;
            $type = $request->type;
            if ($type == 'user') {
                $user = User::where('id', $user_id)->first();
                if (!$user) {
                    Session::flash('warning', 'Something went wrong.');
                    return redirect()->back();
                }
            } elseif ($type == 'seller') {
                $seller = Seller::where('id', $user_id)->first();
                if (!$seller) {
                    Session::flash('warning', 'Something went wrong.');
                    return redirect()->back();
                }
            }
            //already exist or not
            $data = Follower::where([['follower_id', $user_id], ['type', $type], ['following_id', $request->following_id]])->first();
            if ($data) {
                Session::flash('warning', 'You already following this seller.');
                return back();
            } else {
                $in = [
                    'follower_id' => $user_id,
                    'following_id' => $request->following_id,
                    'type' => $type,
                ];
                Follower::create($in);
                Session::flash('success', 'Thank you for following.');
                return back();
            }
        }
    }
    public function unfollow_seller(Request $request)
    {
        if (!$request->user_id && $request->type) {
            return redirect()->route('user.login');
        } elseif (!$request->following_id) {
            Session::flash('warning', 'Something went wrong.');
            return redirect()->back();
        } else {
            $user_id = $request->user_id;
            $type = $request->type;
            if ($type == 'user') {
                $user = User::where('id', $user_id)->first();
                if (!$user) {
                    Session::flash('warning', 'Something went wrong.');
                    return redirect()->back();
                }
            } elseif ($type == 'seller') {
                $seller = Seller::where('id', $user_id)->first();
                if (!$seller) {
                    Session::flash('warning', 'Something went wrong.');
                    return redirect()->back();
                }
            }
            //already exist or not
            $data = Follower::where([['follower_id', $user_id], ['type', $type], ['following_id', $request->following_id]])->first();
            if ($data) {
                Session::flash('warning', 'You unfollow this seller.');
                $data->delete();
                return back();
            } else {
                Session::flash('warning', 'You already following this seller.');
                return back();
            }
        }
    }
}
