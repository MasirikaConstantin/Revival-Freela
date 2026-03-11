<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Helpers\BasicMailer;
use App\Http\Helpers\UploadFile;
use App\Models\Admin;
use App\Models\Blog\Post;
use App\Models\ClientService\Service;
use App\Models\ClientService\ServiceOrder;
use App\Models\Membership;
use App\Models\Package;
use App\Models\Seller;
use App\Models\Subscriber;
use App\Models\SupportTicket;
use App\Models\Transaction;
use App\Models\User;
use App\Rules\ImageMimeTypeRule;
use App\Rules\MatchEmailRule;
use App\Rules\MatchOldPasswordRule;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
  public function login()
  {
    return view('backend.login');
  }

  public function authentication(Request $request)
  {
    $rules = [
      'username' => 'required',
      'password' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    // get the username and password which has provided by the admin
    $credentials = $request->only('username', 'password');

    if (Auth::guard('admin')->attempt($credentials)) {
      $authAdmin = Auth::guard('admin')->user();

      // check whether the admin's account is active or not
      if ($authAdmin->status == 0) {
        $request->session()->flash('alert', 'Sorry, your account has been deactivated!');

        // logout auth admin as condition not satisfied
        Auth::guard('admin')->logout();

        return redirect()->back();
      } else {
        return redirect()->route('admin.dashboard');
      }
    } else {
      return redirect()->back()->with('alert', 'Oops, username or password does not match!');
    }
  }

  public function forgetPassword()
  {
    return view('backend.forget-password');
  }

  public function forgetPasswordMail(Request $request)
  {
    // validation start
    $rules = [
      'email' => [
        'required',
        'email:rfc,dns',
        new MatchEmailRule('admin')
      ]
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors())->withInput();
    }
    // validation end

    // create a new password and store it in db
    $newPassword = uniqid();

    $admin = Admin::query()->where('email', '=', $request->email)->first();

    $admin->update([
      'password' => Hash::make($newPassword)
    ]);

    // prepare a mail to send newly created password to admin
    $mailData['subject'] = 'Reset Password';

    $mailData['body'] = 'Hi ' . $admin->first_name . ',<br/><br/>Your password has been reset. Your new password is: ' . $newPassword . '<br/>Now, you can login with your new password. You can change your password later.<br/><br/>Thank you.';

    $mailData['recipient'] = $admin->email;

    $mailData['sessionMessage'] = 'A mail has been sent to your email address.';

    BasicMailer::sendMail($mailData);

    return redirect()->back();
  }

  public function redirectToDashboard()
  {
    $information['authAdmin'] = Auth::guard('admin')->user();
    $information['services'] = Service::query()->count();
    $information['serviceOrders'] = ServiceOrder::query()->count();
    $information['posts'] = Post::query()->count();
    $information['users'] = User::query()->count();
    $information['sellers'] = Seller::query()->where('id', '!=', 0)->count();
    $information['memberships'] = Membership::query()->where('seller_id', '!=', 0)->count();
    $information['subscribers'] = Subscriber::query()->count();
    $information['support_tickets'] = SupportTicket::query()->count();
    $information['total_transaction'] = Transaction::query()->count();

    $monthWiseServiceOrders = DB::table('service_orders')
      ->select(DB::raw('month(created_at) as month'), DB::raw('count(id) as total_service_orders'))
      ->where('payment_status', '=', 'completed')
      ->groupBy('month')
      ->whereYear('created_at', '=', date('Y'))
      ->get();
    $monthWiseSubscriptions = DB::table('memberships')
      ->select(DB::raw('month(created_at) as month'), DB::raw('count(id) as total_subscription'))
      ->where([['status', 1], ['seller_id', '!=', 0]])
      ->groupBy('month')
      ->whereYear('created_at', '=', date('Y'))
      ->get();

    $months = [];
    $totalServiceOrders = [];
    $totalSubscription = [];

    for ($i = 1; $i <= 12; $i++) {
      // get all 12 months name
      $monthNum = $i;
      $dateObj = DateTime::createFromFormat('!m', $monthNum);
      $monthName = $dateObj->format('M');
      array_push($months, $monthName);

      // get all 12 months's service orders
      $serviceOrderFound = false;

      foreach ($monthWiseServiceOrders as $serviceOrder) {
        if ($serviceOrder->month == $i) {
          $serviceOrderFound = true;
          array_push($totalServiceOrders, $serviceOrder->total_service_orders);
          break;
        }
      }

      if ($serviceOrderFound == false) {
        array_push($totalServiceOrders, 0);
      }
      // get all 12 months's service orders
      $subscriptionFound = false;

      foreach ($monthWiseSubscriptions as $subscription) {
        if ($subscription->month == $i) {
          $subscriptionFound = true;
          array_push($totalSubscription, $subscription->total_subscription);
          break;
        }
      }

      if ($subscriptionFound == false) {
        array_push($totalSubscription, 0);
      }
    }

    $information['months'] = $months;
    $information['totalServiceOrders'] = $totalServiceOrders;
    $information['subscriptionArr'] = $totalSubscription;

    return view('backend.admin.dashboard', $information);
  }

  public function changeTheme(Request $request)
  {
    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      ['admin_theme_version' => $request->admin_theme_version]
    );

    return redirect()->back();
  }

  public function monthly_earning(Request $request)
  {
    if ($request->filled('year')) {
      $date = $request->input('year');
    } else {
      $date = date('Y');
    }
    $monthWiseTotalIncomes = DB::table('transactions')
      ->select(DB::raw('month(created_at) as month'), DB::raw('sum(grand_total) as total'))
      ->where('payment_status', 'completed')
      ->whereIn('transcation_type', [1, 5])
      ->groupBy('month')
      ->whereYear('created_at', '=', $date)
      ->get();


    $months = [];
    $incomes = [];
    for ($i = 1; $i <= 12; $i++) {
      // get all 12 months name
      $monthNum = $i;
      $dateObj = DateTime::createFromFormat('!m', $monthNum);
      $monthName = $dateObj->format('F');
      array_push($months, $monthName);

      // get all 12 months's income of equipment booking
      $incomeFound = false;
      foreach ($monthWiseTotalIncomes as $incomeInfo) {
        if ($incomeInfo->month == $i) {
          $incomeFound = true;
          array_push($incomes, $incomeInfo->total);
          break;
        }
      }
      if ($incomeFound == false) {
        array_push($incomes, 0);
      }
    }
    $information['months'] = $months;
    $information['incomes'] = $incomes;

    return view('backend.admin.lifetime-earning', $information);
  }

  //monthly  income
  public function monthly_profit(Request $request)
  {
    if ($request->filled('year')) {
      $date = $request->input('year');
    } else {
      $date = date('Y');
    }
    //get grand total from admin services
    $monthWiseTotalPackageIncomes = DB::table('transactions')
      ->select(DB::raw('month(created_at) as month'), DB::raw('sum(grand_total) as total'))
      ->where([['seller_id', null], ['payment_status', 'completed']])
      ->whereIn('transcation_type', [1])
      ->groupBy('month')
      ->whereYear('created_at', '=', $date)
      ->get();

    //get grand total from seller buy plan
    $monthWiseTotalSubscriptionIncomes = DB::table('transactions')
      ->select(DB::raw('month(created_at) as month'), DB::raw('sum(grand_total) as total'))
      ->where('payment_status', 'completed')
      ->whereIn('transcation_type', [5])
      ->groupBy('month')
      ->whereYear('created_at', '=', $date)
      ->get();
    $monthWiseTotalTaxes = DB::table('transactions')
      ->select(DB::raw('month(created_at) as month'), DB::raw('sum(tax) as total'))
      ->where([['seller_id', '!=', null], ['payment_status', 'completed']])
      ->whereIn('transcation_type', [1])
      ->groupBy('month')
      ->whereYear('created_at', '=', $date)
      ->get();

    $months = [];
    $packageIncomes = [];
    $subscriptionIncomes = [];
    $taxes = [];
    for ($i = 1; $i <= 12; $i++) {
      // get all 12 months name
      $monthNum = $i;
      $dateObj = DateTime::createFromFormat('!m', $monthNum);
      $monthName = $dateObj->format('M');
      array_push($months, $monthName);

      // get all 12 months's income of booking
      $subFound = false;
      foreach ($monthWiseTotalSubscriptionIncomes as $subInfo) {
        if ($subInfo->month == $i) {
          $subFound = true;
          array_push($subscriptionIncomes, $subInfo->total);
          break;
        }
      }
      if ($subFound == false) {
        array_push($subscriptionIncomes, 0);
      }

      // get all 12 months's income of booking
      $incomeFound = false;
      foreach ($monthWiseTotalPackageIncomes as $incomeInfo) {
        if ($incomeInfo->month == $i) {
          $incomeFound = true;
          array_push($packageIncomes, $incomeInfo->total);
          break;
        }
      }
      if ($incomeFound == false) {
        array_push($packageIncomes, 0);
      }

      // get all 12 months's taxes of  booking
      $taxFound = false;
      foreach ($monthWiseTotalTaxes as $taxInfo) {
        if ($taxInfo->month == $i) {
          $taxFound = true;
          array_push($taxes, $taxInfo->total);
          break;
        }
      }
      if ($taxFound == false) {
        array_push($taxes, 0);
      }
    }
    $information['months'] = $months;
    $information['packageIncomes'] = $packageIncomes;
    $information['subscriptionIncomes'] = $subscriptionIncomes;
    $information['taxes'] = $taxes;

    return view('backend.admin.total-profit', $information);
  }

  public function editProfile()
  {
    $adminInfo = Auth::guard('admin')->user();

    return view('backend.admin.edit-profile', compact('adminInfo'));
  }

  public function updateProfile(Request $request)
  {
    $admin = Admin::where('id', Auth::guard('admin')->user()->id)->firstOrFail();

    $rules = [];

    if (is_null($admin->image)) {
      $rules['image'] = 'required';
    }
    if ($request->hasFile('image')) {
      $rules['image'] = new ImageMimeTypeRule();
    }

    $rules['username'] = [
      'required',
      Rule::unique('admins')->ignore($admin->id)
    ];

    $rules['email'] = [
      'required',
      'email:rfc,dns',
      Rule::unique('admins')->ignore($admin->id)
    ];

    $rules['first_name'] = 'required';

    $rules['last_name'] = 'required';

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    if ($request->hasFile('image')) {
      $newImg = $request->file('image');
      $oldImg = $admin->image;
      $imageName = UploadFile::update('./assets/img/admins/', $newImg, $oldImg);
    }

    $admin->update([
      'first_name' => $request->first_name,
      'last_name' => $request->last_name,
      'image' => $request->hasFile('image') ? $imageName : $admin->image,
      'username' => $request->username,
      'email' => $request->email
    ]);

    $request->session()->flash('success', 'Profile updated successfully!');

    return redirect()->back();
  }

  public function changePassword()
  {
    return view('backend.admin.change-password');
  }

  public function updatePassword(Request $request)
  {
    $rules = [
      'current_password' => [
        'required',
        new MatchOldPasswordRule('admin')
      ],
      'new_password' => 'required|confirmed',
      'new_password_confirmation' => 'required'
    ];

    $messages = [
      'new_password.confirmed' => 'Password confirmation does not match.',
      'new_password_confirmation.required' => 'The confirm new password field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    $admin = Admin::where('id', Auth::guard('admin')->user()->id)->firstOrFail();

    $admin->update([
      'password' => Hash::make($request->new_password)
    ]);

    $request->session()->flash('success', 'Password updated successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  public function logout(Request $request)
  {
    Auth::guard('admin')->logout();

    return redirect()->route('admin.login');
  }

  //transaction 
  public function transcation(Request $request)
  {
    $transcation_id = null;
    if ($request->filled('transcation_id')) {
      $transcation_id = $request->transcation_id;
    }

    $info['transcations'] = Transaction::when($transcation_id, function ($query) use ($transcation_id) {
      return $query->where('transcation_id', 'like', '%' . $transcation_id . '%');
    })->orderByDesc('id')->paginate(10);

    return view('backend.admin.transcation', $info);
  }
}
