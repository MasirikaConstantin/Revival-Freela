<?php

namespace App\Http\Controllers\BackEnd\User;

use App\Http\Controllers\Controller;
use App\Http\Helpers\MegaMailer;
use App\Http\Helpers\UploadFile;
use App\Models\BasicSettings\Basic;
use App\Models\Follower;
use App\Models\SupportTicket;
use App\Models\User;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
  public function index(Request $request)
  {
    $searchKey = null;

    if ($request->filled('info')) {
      $searchKey = $request['info'];
    }

    $users = User::query()->when($searchKey, function ($query, $searchKey) {
      return $query->where('username', 'like', '%' . $searchKey . '%')
        ->orWhere('email', 'like', '%' . $searchKey . '%');
    })
      ->orderByDesc('id')
      ->paginate(10);

    return view('backend.end-user.user.index', compact('users'));
  }

  public function registerUser(Request $request)
  {
    $request->validate([
      'username' => 'required|unique:users|max:255',
      'email_address' => 'required|email:rfc,dns|unique:users|max:255',
      'password' => 'required|confirmed',
      'password_confirmation' => 'required',
    ]);
    $websiteTitle = Basic::query()->pluck('website_title')->first();

    $user = new User();
    $user->username = $request->username;
    $user->email_address = $request->email_address;
    $user->password = Hash::make($request->password);


    $user->email_verified_at = date('Y-m-d H:i:s');
    $user->status = 1;
    $user->verification_token = null;
    $user->save();

    /**
     * prepare a verification mail and, send it to user to verify his/her email address,
     * get the mail template information from db
     */

    $bs = Basic::select('website_title')->first();
    $mailer = new MegaMailer();
    $data = [
      'toMail' => $request->email_address,
      'toName' => $request->username,
      'username' => $request->username,
      'password' => $request->password,
      'user_type' => 'customer',
      'website_title' => $bs->website_title,
      'templateType' => 'add_user_by_admin'
    ];
    $mailer->mailFromAdmin($data);

    Session::flash('success', 'Customer added successfully!');
    return Response::json(['status' => 'success'], 200);
  }

  public function updateEmailStatus(Request $request, $id)
  {
    $user = User::query()->find($id);

    if ($request['email_status'] == 'verified') {
      $user->update([
        'email_verified_at' => date('Y-m-d H:i:s')
      ]);
    } else {
      $user->update([
        'email_verified_at' => NULL
      ]);
    }

    $request->session()->flash('success', 'Email status updated successfully!');

    return redirect()->back();
  }

  public function updateAccountStatus(Request $request, $id)
  {
    $user = User::query()->find($id);

    if ($request['account_status'] == 1) {
      $user->update([
        'status' => 1
      ]);
    } else {
      $user->update([
        'status' => 0
      ]);
    }

    $request->session()->flash('success', 'Account status updated successfully!');

    return redirect()->back();
  }

  public function edit($id)
  {
    $user = User::query()->findOrFail($id);
    $information['user'] = $user;
    return view('backend.end-user.user.edit', $information);
  }
  public function update(Request $request, $id)
  {
    $rules = [
      'image' => $request->hasFile('image') ? [
        new ImageMimeTypeRule(),
        'dimensions:min_width=80,max_width=80,min_width=80,min_height=80'
      ] : '',
      'first_name' => 'required',
      'last_name' => 'required',
      'username' => [
        'required',
        Rule::unique('users', 'username')->ignore($id)
      ],
      'email_address' => [
        'required',
        Rule::unique('users', 'email_address')->ignore($id)
      ],
      'phone_number' => 'required',
      'city' => 'required',
      'country' => 'required',
      'address' => 'required',
    ];
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return response()->json(['errors' => $validator->getMessageBag()], 400);
    }
    $user = User::findOrFail($id);

    if ($request->hasFile('image')) {
      $newImg = $request->file('image');
      $oldImg = $user->image;
      $imageName = UploadFile::update('./assets/img/users/', $newImg, $oldImg);
    }

    $user->update($request->except('image') + [
      'image' => $request->hasFile('image') ? $imageName : $user->image
    ]);
    Session::flash('success', 'Updated customer information successfully.');
    return response()->json(['status' => 'success'], 200);
  }

  public function show($id)
  {
    $user = User::query()->findOrFail($id);
    $information['userInfo'] = $user;

    return view('backend.end-user.user.details', $information);
  }

  public function changePassword($id)
  {
    $userInfo = User::query()->findOrFail($id);

    return view('backend.end-user.user.change-password', compact('userInfo'));
  }

  public function updatePassword(Request $request, $id)
  {
    $rules = [
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
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    $user = User::query()->find($id);

    $user->update([
      'password' => Hash::make($request->new_password)
    ]);

    $request->session()->flash('success', 'Password updated successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function destroy($id)
  {
    $this->deleteUser($id);

    return redirect()->back()->with('success', 'Customer deleted successfully!');
  }

  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $this->deleteUser($id);
    }

    $request->session()->flash('success', 'Customers deleted successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function deleteUser($id)
  {
    $user = User::query()->find($id);

    // delete all the service orders of this user
    $serviceOrders = $user->serviceOrder()->get();

    if (count($serviceOrders) > 0) {
      foreach ($serviceOrders as $order) {
        // delete messages of each order
        $messages = $order->message()->get();

        if (count($messages) > 0) {
          foreach ($messages as $msg) {
            // delete uploaded file
            @unlink(public_path('assets/file/message-files/' . $msg->file_name));

            // delete message
            $msg->delete();
          }
        }

        // delete zip file which has uploaded by the user
        $informations = json_decode($order->informations);

        if (!is_null($informations)) {
          foreach ($informations as $key => $information) {
            if ($information->type == 8) {
              @unlink(public_path('assets/file/zip-files/' . $information->value));
            }
          }
        }

        // delete order receipt
        @unlink(public_path('assets/img/attachments/service/' . $order->receipt));

        // delete order invoice
        @unlink(public_path('assets/file/invoices/service/' . $order->invoice));

        // delete service order
        $order->delete();
      }
    }

    // delete all the service reviews of this user
    $serviceReviews = $user->serviceReview()->get();

    if (count($serviceReviews) > 0) {
      foreach ($serviceReviews as $review) {
        $review->delete();
      }
    }

    // delete all the support tickets of this user
    $tickets = SupportTicket::where([['user_id', $user->id], ['user_type', 'user']])->get();

    if (count($tickets) > 0) {
      foreach ($tickets as $ticket) {
        // delete all the conversations of each ticket
        $conversations = $ticket->conversation()->get();

        if (count($conversations) > 0) {
          foreach ($conversations as $conversation) {
            // delete attachment of this conversation
            @unlink(public_path('assets/file/ticket-files/' . $conversation->attachment));

            // delete conversation
            $conversation->delete();
          }
        }

        // delete attachment of this ticket
        @unlink(public_path('assets/file/ticket-files/' . $ticket->attachment));

        // delete ticket
        $ticket->delete();
      }
    }
    //delete support tickets end

    // delete all the wishlisted services of this user
    $wishlistedServices = $user->wishlistedService()->get();

    if (count($wishlistedServices) > 0) {
      foreach ($wishlistedServices as $service) {
        $service->delete();
      }
    }

    $followings = Follower::where([['follower_id', $user->id], ['type', 'user']])->get();
    foreach ($followings as $following) {
      $following->delete();
    }

    // delete user image
    @unlink(public_path('assets/img/users/' . $user->image));

    // delete user info from db
    $user->delete();
  }

  public function secretLogin(Request $request, $id)
  {
    $user = User::where('id', $id)->first();
    if ($user) {
      Auth::guard('web')->login($user, true);

      return redirect()->route('user.dashboard')
        ->withSuccess('You have Successfully logged in');
    }

    return redirect()->route('user.login')->withSuccess('Oppes! You have entered invalid credentials');
  }
}
