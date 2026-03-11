<?php

namespace App\Http\Controllers\FrontEnd;

use App\Events\MessageStored;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Http\Helpers\BasicMailer;
use App\Http\Helpers\SellerPermissionHelper;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\MessageRequest;
use App\Http\Requests\SupportTicket\ConversationRequest;
use App\Http\Requests\SupportTicket\TicketRequest;
use App\Http\Requests\User\ForgetPasswordRequest;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\ResetPasswordRequest;
use App\Http\Requests\User\SignupRequest;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\MailTemplate;
use App\Models\ClientService\Service;
use App\Models\ClientService\ServiceOrder;
use App\Models\ClientService\ServiceOrderMessage;
use App\Models\Follower;
use App\Models\Seller;
use App\Models\SupportTicket;
use App\Models\TicketConversation;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Mews\Purifier\Facades\Purifier;

class UserController extends Controller
{
  public function login()
  {
    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_customer_login', 'meta_description_customer_login')->first();

    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $queryResult['breadcrumb'] = $misc->getBreadcrumb();

    $queryResult['bs'] = Basic::query()->select('google_recaptcha_status', 'facebook_login_status', 'google_login_status')->first();

    return view('frontend.login', $queryResult);
  }

  public function redirectToFacebook()
  {
    return Socialite::driver('facebook')->redirect();
  }

  public function handleFacebookCallback()
  {
    return $this->authenticationViaProvider('facebook');
  }

  public function redirectToGoogle()
  {
    return Socialite::driver('google')->redirect();
  }

  public function handleGoogleCallback()
  {
    return $this->authenticationViaProvider('google');
  }

  public function authenticationViaProvider($driver)
  {
    // get the url from session which will be redirect after login
    if (Session::has('redirectTo')) {
      $redirectURL = Session::get('redirectTo');
    } else {
      $redirectURL = route('user.dashboard');
    }

    $responseData = Socialite::driver($driver)->user();
    $userInfo = $responseData->user;

    $isUser = User::query()->where('email_address', '=', $userInfo['email'])->first();

    if (!empty($isUser)) {
      // log in
      if ($isUser->status == 1) {
        Auth::login($isUser);

        return redirect($redirectURL);
      } else {
        Session::flash('error', 'Sorry, your account has been deactivated.');

        return redirect()->route('user.login');
      }
    } else {
      // get user avatar and save it
      $avatar = $responseData->getAvatar();
      $fileContents = file_get_contents($avatar);

      $avatarName = $responseData->getId() . '.jpg';
      $path = public_path('assets/img/users/');

      file_put_contents($path . $avatarName, $fileContents);

      // sign up
      $user = new User();

      if ($driver == 'facebook') {
        $user->first_name = $userInfo['name'];
      } else {
        $user->first_name = $userInfo['given_name'];
        $user->last_name = $userInfo['family_name'];
      }

      $user->image = $avatarName;
      $user->email_address = $userInfo['email'];
      $user->email_verified_at = date('Y-m-d H:i:s');
      $user->status = 1;
      $user->provider = ($driver == 'facebook') ? 'facebook' : 'google';
      $user->provider_id = $userInfo['id'];
      $user->save();

      Auth::login($user);

      return redirect($redirectURL);
    }
  }

  public function loginSubmit(LoginRequest $request)
  {
    // get the url from session which will be redirect after login
    if ($request->session()->has('redirectTo')) {
      $redirectURL = $request->session()->get('redirectTo');
    } else {
      $redirectURL = route('user.dashboard');
    }

    // get the email-address and password which has provided by the user
    $credentials = $request->only('username', 'password');

    // login attempt
    if (Auth::guard('web')->attempt($credentials)) {
      $authUser = Auth::guard('web')->user();

      // first, check whether the user's email address verified or not
      if (is_null($authUser->email_verified_at)) {
        $request->session()->flash('error', 'Please, verify your email address.');

        // logout auth user as condition not satisfied
        Auth::guard('web')->logout();

        return redirect()->back();
      }

      // second, check whether the user's account is active or not
      if ($authUser->status == 0) {
        $request->session()->flash('error', 'Sorry, your account has been deactivated.');

        // logout auth user as condition not satisfied
        Auth::guard('web')->logout();

        return redirect()->back();
      }

      // before, redirect to next url forget the session value
      if ($request->session()->has('redirectTo')) {
        $request->session()->forget('redirectTo');
      }


      // otherwise, redirect auth user to next url
      return redirect($redirectURL);
    } else {
      $request->session()->flash('error', 'Incorrect username or password!');

      return redirect()->back();
    }
  }

  public function forgetPassword()
  {
    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_customer_forget_password', 'meta_description_customer_forget_password')->first();

    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $queryResult['breadcrumb'] = $misc->getBreadcrumb();

    return view('frontend.forget-password', $queryResult);
  }

  public function forgetPasswordMail(ForgetPasswordRequest $request)
  {
    $user = User::query()->where('email_address', '=', $request->email_address)->first();

    // store user email in session to use it later
    $request->session()->put('userEmail', $user->email_address);

    // get the mail template information from db
    $mailTemplate = MailTemplate::query()->where('mail_type', '=', 'reset_password')->first();
    $mailData['subject'] = $mailTemplate->mail_subject;
    $mailBody = $mailTemplate->mail_body;

    // get the website title info from db
    $websiteTitle = Basic::query()->pluck('website_title')->first();

    $name = $user->first_name . ' ' . $user->last_name;

    $link = '<a href=' . url("user/reset-password") . '>Click Here</a>';

    $mailBody = str_replace('{customer_name}', $name, $mailBody);
    $mailBody = str_replace('{password_reset_link}', $link, $mailBody);
    $mailBody = str_replace('{website_title}', $websiteTitle, $mailBody);

    $mailData['body'] = $mailBody;

    $mailData['recipient'] = $user->email_address;

    $mailData['sessionMessage'] = 'A mail has been sent to your email address.';

    BasicMailer::sendMail($mailData);

    return redirect()->back();
  }

  public function resetPassword()
  {
    $misc = new MiscellaneousController();

    $breadcrumb = $misc->getBreadcrumb();

    return view('frontend.reset-password', compact('breadcrumb'));
  }

  public function resetPasswordSubmit(ResetPasswordRequest $request)
  {
    if ($request->session()->has('userEmail')) {
      // get the user email from session
      $emailAddress = $request->session()->get('userEmail');

      $user = User::query()->where('email_address', '=', $emailAddress)->first();

      $user->update([
        'password' => Hash::make($request->new_password)
      ]);

      $request->session()->flash('success', 'Password updated successfully.');
    } else {
      $request->session()->flash('error', 'Something went wrong!');
    }

    return redirect()->route('user.login');
  }

  public function signup()
  {
    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_customer_signup', 'meta_description_customer_signup')->first();

    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $queryResult['breadcrumb'] = $misc->getBreadcrumb();

    $queryResult['recaptchaStatus'] = Basic::query()->pluck('google_recaptcha_status')->first();

    return view('frontend.signup', $queryResult);
  }

  public function signupSubmit(SignupRequest $request)
  {
    $websiteTitle = Basic::query()->pluck('website_title')->first();

    $user = new User();
    $user->username = $request->username;
    $user->email_address = $request->email_address;
    $user->password = Hash::make($request->password);

    // first, generate a random string
    $randStr = Str::random(20);

    // second, generate a token
    $token = md5($randStr . $request->username . $request->email);

    $user->verification_token = $token;
    $user->save();

    /**
     * prepare a verification mail and, send it to user to verify his/her email address,
     * get the mail template information from db
     */
    $mailTemplate = MailTemplate::query()->where('mail_type', '=', 'verify_email')->first();
    $mailData['subject'] = $mailTemplate->mail_subject;
    $mailBody = $mailTemplate->mail_body;

    $link = '<a href=' . url("user/signup-verify/" . $token) . '>Click Here</a>';

    $mailBody = str_replace('{username}', $request->username, $mailBody);
    $mailBody = str_replace('{verification_link}', $link, $mailBody);
    $mailBody = str_replace('{website_title}', $websiteTitle, $mailBody);

    $mailData['body'] = $mailBody;

    $mailData['recipient'] = $request->email_address;

    $mailData['sessionMessage'] = 'A verification link has been sent to your email address.';

    BasicMailer::sendMail($mailData);

    return redirect()->back();
  }

  public function signupVerify(Request $request, $token)
  {
    try {
      $user = User::query()->where('verification_token', '=', $token)->firstOrFail();

      // after verify user email, put "null" in the "verification token"
      $user->update([
        'email_verified_at' => date('Y-m-d H:i:s'),
        'status' => 1,
        'verification_token' => null
      ]);

      $request->session()->flash('success', 'Your email address has been verified.');

      // after email verification, authenticate this user
      Auth::guard('web')->login($user);

      return redirect()->route('user.dashboard');
    } catch (ModelNotFoundException $e) {
      $request->session()->flash('error', 'Could not verify your email address!');

      return redirect()->route('user.signup');
    }
  }

  public function redirectToDashboard()
  {
    $misc = new MiscellaneousController();

    $queryResult['breadcrumb'] = $misc->getBreadcrumb();

    $user = Auth::guard('web')->user();

    $queryResult['authUser'] = $user;

    $queryResult['numOfServiceOrders'] = $user->serviceOrder()->count();

    $queryResult['numOfWishlistedServices'] = $user->wishlistedService()->count();
    $queryResult['numOfsupportTicket'] = $user->supportTickets()->count();

    return view('frontend.user.dashboard', $queryResult);
  }
  public function followings()
  {
    $misc = new MiscellaneousController();

    $queryResult['breadcrumb'] = $misc->getBreadcrumb();

    $user = Auth::guard('web')->user();

    $queryResult['followings'] = Follower::where([['follower_id', $user->id], ['type', 'user']])->paginate(10);

    return view('frontend.user.following', $queryResult);
  }

  public function editProfile()
  {
    $misc = new MiscellaneousController();

    $queryResult['breadcrumb'] = $misc->getBreadcrumb();

    $queryResult['authUser'] = Auth::guard('web')->user();

    return view('frontend.user.edit-profile', $queryResult);
  }

  public function updateProfile(UpdateProfileRequest $request)
  {

    $authUser = Auth::guard('web')->user();

    if ($request->hasFile('image')) {
      $newImg = $request->file('image');
      $oldImg = $authUser->image;
      $imageName = UploadFile::update('./assets/img/users/', $newImg, $oldImg);
    }

    $authUser->update($request->except('image') + [
      'image' => $request->hasFile('image') ? $imageName : $authUser->image
    ]);

    $request->session()->flash('success', 'Your profile has been updated successfully.');

    return redirect()->back();
  }

  public function changePassword()
  {
    $misc = new MiscellaneousController();

    $breadcrumb = $misc->getBreadcrumb();

    return view('frontend.user.change-password', compact('breadcrumb'));
  }

  public function updatePassword(UpdatePasswordRequest $request)
  {
    $user = Auth::guard('web')->user();

    $user->update([
      'password' => Hash::make($request->new_password)
    ]);

    $request->session()->flash('success', 'Password updated successfully.');

    return redirect()->back();
  }

  public function serviceOrders()
  {
    $misc = new MiscellaneousController();

    $queryResult['breadcrumb'] = $misc->getBreadcrumb();

    $authUser = Auth::guard('web')->user();

    $orders = $authUser->serviceOrder()->orderByDesc('id')->get();

    $language = $misc->getLanguage();

    $orders->map(function ($order) use ($language) {
      $service = $order->service()->first();
      $order['serviceInfo'] = $service->content()->where('language_id', $language->id)->select('title', 'slug')->first();
    });

    $queryResult['orders'] = $orders;

    return view('frontend.user.service-orders', $queryResult);
  }

  public function raise_request($id, $status)
  {
    $order = ServiceOrder::where([['id', $id], ['user_id', Auth::guard('web')->user()->id]])->firstOrFail();
    if ($status == 1) {
      $order->raise_status = 1;
      $order->save();
      Session::flash('success', 'Your raise request has been successfully submited. Admin will contact you soon.');
    } else {
      $order->raise_status = 0;
      $order->save();
      Session::flash('error', 'Your raise request has been canceled.');
    }
    return back();
  }

  public function serviceOrderDetails($id)
  {
    $misc = new MiscellaneousController();

    $queryResult['breadcrumb'] = $misc->getBreadcrumb();

    $order = ServiceOrder::where([['id', $id], ['user_id', Auth::guard('web')->user()->id]])->firstOrFail();
    $queryResult['orderInfo'] = $order;

    $language = $misc->getLanguage();

    // get service title
    $service = $order->service()->first();
    $queryResult['serviceInfo'] = $service->content()->where('language_id', $language->id)->select('title', 'slug')->first();

    // get package title
    $package = $order->package()->first();

    if (is_null($package)) {
      $queryResult['packageTitle'] = NULL;
    } else {
      $queryResult['packageTitle'] = $package->name;
    }

    return view('frontend.user.service-order-details', $queryResult);
  }

  public function message($id)
  {
    $misc = new MiscellaneousController();

    $queryResult['breadcrumb'] = $misc->getBreadcrumb();

    $order = ServiceOrder::where([['id', $id], ['user_id', Auth::guard('web')->user()->id]])->firstOrFail();

    //check live chat status active or not for this user
    if (!is_null($order->seller_id)) {

      $checkPermission =  SellerPermissionHelper::getPackageInfo($order->seller_id, $order->seller_membership_id);
      if ($checkPermission != true) {
        Session::flash('success', 'Live chat is not active for this seller order.');
        return redirect()->route('user.dashboard');
      }
    }
    $queryResult['order'] = $order;

    $misc = new MiscellaneousController();
    $language = $misc->getLanguage();

    $service = $order->service()->first();
    $queryResult['serviceInfo'] = $service->content()->where('language_id', $language->id)->first();

    $messages = $order->message()->get();

    $messages->map(function ($message) {
      if ($message->person_type == 'user') {
        $message['user'] = $message->user()->first();
      } else {
        $message['admin'] = $message->admin()->first();
      }
    });

    $queryResult['messages'] = $messages;

    $queryResult['bs'] = Basic::query()->select('pusher_key', 'pusher_cluster')->first();

    return view('frontend.user.service-order-message', $queryResult);
  }

  public function storeMessage(MessageRequest $request, $id)
  {
    if ($request->hasFile('attachment')) {
      $file = $request->file('attachment');
      $fileName = UploadFile::store('./assets/file/message-files/', $file);
      $fileOriginalName = $file->getClientOriginalName();
    }

    $orderMsg = new ServiceOrderMessage();
    $orderMsg->person_id = Auth::guard('web')->user()->id;
    $orderMsg->person_type = 'user';
    $orderMsg->order_id = $id;
    $orderMsg->message = $request->filled('msg') ? Purifier::clean($request->msg, 'youtube') : NULL;
    $orderMsg->file_name = isset($fileName) ? $fileName : NULL;
    $orderMsg->file_original_name = isset($fileOriginalName) ? $fileOriginalName : NULL;
    $orderMsg->save();



    event(new MessageStored());

    return response()->json(['status' => 'Message stored.', 200]);
  }

  public function serviceWishlist()
  {
    $misc = new MiscellaneousController();

    $queryResult['breadcrumb'] = $misc->getBreadcrumb();

    $authUser = Auth::guard('web')->user();

    $listedServices = $authUser->wishlistedService()->orderByDesc('id')->get();

    $language = $misc->getLanguage();

    $listedServices->map(function ($listedService) use ($language) {
      $service = Service::query()->find($listedService->service_id);

      $listedService['serviceContent'] = $service->content()->where('language_id', $language->id)->first();
    });

    $queryResult['listedServices'] = $listedServices;

    return view('frontend.user.service-wishlist', $queryResult);
  }

  public function removeService($service_id)
  {
    try {
      $user = Auth::guard('web')->user();

      $listedService = $user->wishlistedService()->where('service_id', $service_id)->firstOrFail();

      $listedService->delete();

      return redirect()->back()->with('success', 'Service has been removed.');
    } catch (ModelNotFoundException $e) {
      return redirect()->back()->with('error', 'Service not found!');
    }
  }

  public function tickets()
  {
    $misc = new MiscellaneousController();

    $queryResult['breadcrumb'] = $misc->getBreadcrumb();

    $authUser = Auth::guard('web')->user();

    $queryResult['tickets'] = $authUser->ticket()->orderByDesc('id')->get();

    return view('frontend.user.support-tickets', $queryResult);
  }

  public function createTicket()
  {
    $misc = new MiscellaneousController();

    $breadcrumb = $misc->getBreadcrumb();

    return view('frontend.user.create-ticket', compact('breadcrumb'));
  }

  public function storeTempFile(Request $request)
  {
    // deleting other temp files
    $tempFiles = glob('assets/file/temp/*');

    if (count($tempFiles) > 0) {
      foreach ($tempFiles as $file) {
        @unlink(public_path($file));
      }
    }

    // storing new file as a temp file
    $file = $request->file('attachment');
    UploadFile::store('./assets/file/temp/', $file);

    return Response::json(['status' => 'success'], 200);
  }

  public function storeTicket(TicketRequest $request)
  {
    // deleting temp files
    $tempFiles = glob('assets/file/temp/*');

    if (count($tempFiles) > 0) {
      foreach ($tempFiles as $file) {
        @unlink(public_path($file));
      }
    }

    // storing new file
    if ($request->hasFile('attachment')) {
      $file = $request->file('attachment');
      $fileName = UploadFile::store('./assets/file/ticket-files/', $file);
    }

    $ticket = new SupportTicket();
    $ticket->user_id = Auth::guard('web')->user()->id;
    $ticket->user_type = 'user';
    $ticket->admin_id = 1;
    $ticket->ticket_number = uniqid();
    $ticket->subject = $request->subject;
    $ticket->message = Purifier::clean($request->message, 'youtube');
    $ticket->attachment = isset($fileName) ? $fileName : NULL;
    $ticket->save();

    $request->session()->flash('success', 'Ticket submitted successfully.');

    return redirect()->back();
  }

  public function ticketConversation($id)
  {

    $misc = new MiscellaneousController();

    $queryResult['breadcrumb'] = $misc->getBreadcrumb();

    $ticket = SupportTicket::query()->where([['user_id', Auth::guard('web')->user()->id], ['id', $id]])->firstOrFail();
    $queryResult['ticket'] = $ticket;

    $queryResult['conversations'] = $ticket->conversation()->get();

    return view('frontend.user.ticket-conversation', $queryResult);
  }

  public function ticketReply(ConversationRequest $request, $id)
  {
    // deleting temp files
    $tempFiles = glob('assets/file/temp/*');

    if (count($tempFiles) > 0) {
      foreach ($tempFiles as $file) {
        @unlink(public_path($file));
      }
    }

    // storing new file
    if ($request->hasFile('attachment')) {
      $file = $request->file('attachment');
      $fileName = UploadFile::store('./assets/file/ticket-files/', $file);
    }

    $conversation = new TicketConversation();
    $conversation->ticket_id = $id;
    $conversation->person_id = Auth::guard('web')->user()->id;
    $conversation->person_type = 'user';
    $conversation->reply = Purifier::clean($request->reply, 'youtube');
    $conversation->attachment = isset($fileName) ? $fileName : NULL;
    $conversation->save();

    $request->session()->flash('success', 'Reply submitted successfully.');

    return redirect()->back();
  }

  public function logoutSubmit(Request $request)
  {
    Auth::guard('web')->logout();

    if ($request->session()->has('redirectTo')) {
      $request->session()->forget('redirectTo');
    }

    return redirect()->route('user.login');
  }

  public function confirm_order($id)
  {
    $user_id = Auth::guard('web')->user()->id;
    $order = ServiceOrder::where([['user_id', $user_id], ['id', $id]])->firstOrFail();
    if (!is_null($order->grand_total)) {
      if (!is_null($order->seller_id)) {
        $arrData['seller_id'] = $order->seller_id;
        $seller = Seller::where('id', $order->seller_id)->first();
        if ($seller) {
          $pre_balance = $seller->amount;
          $after_balance = $seller->amount + ($order->grand_total - $order->tax);
          $seller->amount = $after_balance;
          $seller->save();
        } else {
          $pre_balance = null;
          $after_balance = null;
        }

        //send email to seller 
        $mailData = [];

        $mailData['subject'] = 'Completion Notification for Project ' . $order->order_number;

        $mailData['body'] = 'Hi ' . $order->name . ',<br/><br/>We are pleased to inform you that your recent project with order number: #' . $order->order_number . 'has been successfully completed.';

        $mailData['recipient'] = $order->email_address;

        BasicMailer::sendMail($mailData);
        //send email to seller end 

      } else {
        $pre_balance = null;
        $after_balance = null;
      }

      //add balance to seller account and transcation
      $transaction_data = [];
      $transaction_data['order_id'] = $order->id;
      $transaction_data['transcation_type'] = 1;
      $transaction_data['user_id'] = $order->user_id;
      $transaction_data['seller_id'] = $order->seller_id;
      $transaction_data['payment_status'] = $order->payment_status;
      $transaction_data['payment_method'] = $order->payment_method;
      $transaction_data['grand_total'] = $order->grand_total;
      $transaction_data['pre_balance'] = $pre_balance;
      $transaction_data['tax'] = $order->tax;
      $transaction_data['after_balance'] = $after_balance;
      $transaction_data['gateway_type'] = $order->gateway_type;
      $transaction_data['currency_symbol'] = $order->currency_symbol;
      $transaction_data['currency_symbol_position'] = $order->currency_symbol_position;
      storeTransaction($transaction_data);
      $data = [
        'life_time_earning' => $order->grand_total,
        'total_profit' => is_null($order->seller_id) ? $order->grand_total : $order->tax,
      ];
      storeEarnings($data);
    }
    $order->order_status = 'completed';
    $order->save();

    //add balance to seller account and transcation end
    Session::flash('success', 'Order completed successfully.');
    return back();
  }
}
