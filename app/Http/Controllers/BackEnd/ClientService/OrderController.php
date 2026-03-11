<?php

namespace App\Http\Controllers\BackEnd\ClientService;

use App\Events\MessageStored;
use App\Exports\ServiceOrdersExport;
use App\Http\Controllers\Controller;
use App\Http\Helpers\BasicMailer;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\MessageRequest;
use App\Models\BasicSettings\Basic;
use App\Models\ClientService\ServiceOrder;
use App\Models\ClientService\ServiceOrderMessage;
use App\Models\ClientService\ServiceReview;
use App\Models\Language;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Seller;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Mews\Purifier\Facades\Purifier;
use PDF;

class OrderController extends Controller
{
  public function orders(Request $request)
  {
    $orderNumber = $paymentStatus = $orderStatus = $seller = null;

    if ($request->filled('order_no')) {
      $orderNumber = $request['order_no'];
    }
    if ($request->filled('payment_status')) {
      $paymentStatus = $request['payment_status'];
    }
    if ($request->filled('order_status')) {
      $orderStatus = $request['order_status'];
    }
    if ($request->filled('seller')) {
      $seller = $request['seller'];
    }

    $orders = ServiceOrder::query()->when($orderNumber, function (Builder $query, $orderNumber) {
      return $query->where('order_number', 'like', '%' . $orderNumber . '%');
    })
      ->when($paymentStatus, function (Builder $query, $paymentStatus) {
        return $query->where('payment_status', '=', $paymentStatus);
      })
      ->when($orderStatus, function (Builder $query, $orderStatus) {
        return $query->where('order_status', '=', $orderStatus);
      })
      ->when($seller, function (Builder $query, $seller) {
        if ($seller == 'admin') {
          $seller_id = null;
        } else {
          $seller_id = $seller;
        }
        return $query->where('seller_id', '=', $seller_id);
      })
      ->orderByDesc('id')
      ->paginate(10);

    $language = Language::query()->where('is_default', '=', 1)->first();

    $orders->map(function ($order) use ($language) {
      $service = $order->service()->first();
      $order['serviceTitle'] = $service->content()->where('language_id', $language->id)->pluck('title')->first();
      $order['serviceSlug'] = $service->content()->where('language_id', $language->id)->pluck('slug')->first();

      $package = $order->package()->first();

      if (is_null($package)) {
        $order['packageName'] = NULL;
      } else {
        $order['packageName'] = $package->name;
      }
    });

    $sellers = Seller::select('id', 'username')->where('id', '!=', 0)->get();

    return view('backend.client-service.order.index', compact('orders', 'sellers'));
  }

  public function disputs(Request $request)
  {
    $order_no = null;
    if ($request->filled('order_no')) {
      $order_no = $request->order_no;
    }
    $collection = ServiceOrder::where('raise_status', '!=', 0)
      ->when($order_no, function ($query) use ($order_no) {
        return $query->where('order_number', 'like', '%' . $order_no . '%');
      })
      ->orderByDesc('id')
      ->paginate(10);

    $language = Language::query()->where('is_default', '=', 1)->first();

    $collection->map(function ($order) use ($language) {
      $service = $order->service()->first();
      $order['serviceTitle'] = $service->content()->where('language_id', $language->id)->pluck('title')->first();
      $order['serviceSlug'] = $service->content()->where('language_id', $language->id)->pluck('slug')->first();
    });
    return view('backend.client-service.order.disputs', compact('collection'));
  }

  //disput_update 
  public function disput_update(Request $request, $id)
  {
    $order = ServiceOrder::findOrFail($id);
    $order->update([
      'raise_status' => $request->raise_status
    ]);
    Session::flash('success', 'Raise disput status has been updated successfully.');
    return back();
  }

  public function updatePaymentStatus(Request $request, $id)
  {
    $order = ServiceOrder::query()->find($id);

    if ($request['payment_status'] == 'completed') {
      $order->update([
        'payment_status' => 'completed'
      ]);
      $statusMsg = 'Your payment is complete.';
      // generate an invoice in pdf format
      $invoice = $this->generateInvoice($order);
      // then, update the invoice field info in database
      $order->update([
        'invoice' => $invoice
      ]);
    } else if ($request['payment_status'] == 'pending') {

      if ($order->invoice) {

        @unlink(public_path('assets/file/invoices/service/' . $order->invoice));
      }
      $order->update([
        'payment_status' => 'pending',
        'invoice' => null,
      ]);

      $statusMsg = 'payment is pending.';
    } else {
      if ($order->invoice) {
        @unlink(public_path('assets/file/invoices/service/' . $order->invoice));
      }
      $order->update([
        'payment_status' => 'rejected',
        'invoice' => null
      ]);

      $statusMsg = 'payment has been rejected.';
    }

    $mailData = [];

    if (isset($invoice)) {
      $mailData['invoice'] = public_path('assets/file/invoices/service/' . $invoice);
    }

    $mailData['subject'] = 'Notification of payment status';

    $mailData['body'] = 'Hi ' . $order->name . ',<br/><br/>This email is to notify the payment status of your order: #' . $order->order_number . '.<br/>' . $statusMsg;

    $mailData['recipient'] = $order->email_address;

    $mailData['sessionMessage'] = 'Payment status updated & mail has been sent successfully!';

    BasicMailer::sendMail($mailData);

    return redirect()->back();
  }

  public function generateInvoice($order)
  {

    $invoiceName = $order->order_number . '.pdf';
    $directory = './assets/file/invoices/service/';

    @mkdir(public_path($directory), 0775, true);

    $fileLocation = $directory . $invoiceName;
    $arrData['orderInfo'] = $order;

    // get language
    $language = Language::query()->where('is_default', '=', 1)->first();

    // get service title
    $service = $order->service()->first();
    $arrData['serviceTitle'] = $service->content()->where('language_id', $language->id)->pluck('title')->first();

    // get package title
    $package = $order->package()->first();

    if (is_null($package)) {
      $arrData['packageTitle'] = NULL;
    } else {
      $arrData['packageTitle'] = $package->name;
    }

    PDF::loadView('frontend.service.invoice', $arrData)->save(public_path($fileLocation));

    return $invoiceName;
  }

  public function updateOrderStatus(Request $request, $id)
  {
    $order = ServiceOrder::query()->find($id);

    if ($request['order_status'] == 'completed') {
      $order->update([
        'order_status' => 'completed'
      ]);

      $statusMsg = 'Your order is completed.';

      if ($order->seller_id != null) {
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
      } else {
        $pre_balance = null;
        $after_balance = null;
      }

      $transaction_data = [];
      $transaction_data['order_id'] = $order->id;
      $transaction_data['transcation_type'] = 1;
      $transaction_data['user_id'] = $order->user_id;
      $transaction_data['seller_id'] = $order->seller_id;
      $transaction_data['payment_status'] = $order->payment_status;
      $transaction_data['payment_method'] = $order->payment_method;
      $transaction_data['grand_total'] = $order->grand_total;
      $transaction_data['tax'] = $order->tax;
      $transaction_data['pre_balance'] = $pre_balance;
      $transaction_data['after_balance'] = $after_balance;
      $transaction_data['gateway_type'] = $order->gateway_type;
      $transaction_data['currency_symbol'] = $order->currency_symbol;
      $transaction_data['currency_symbol_position'] = $order->currency_symbol_position;
      storeTransaction($transaction_data);
      $data = [
        'life_time_earning' => $order->grand_total,
        'total_profit' =>  is_null($order->seller_id) ? $order->grand_total : $order->tax,
      ];
      storeEarnings($data);


      $mailData = [];
      $mailData['body'] = 'Hi ' . $order->name . ',<br/><br/>We are pleased to inform you that your recent order with order number: #' . $order->order_number . ' has been successfully completed.';
      $mailData['subject'] = 'Notification of order status';
      $mailData['recipient'] = $order->email_address;

      BasicMailer::sendMail($mailData);
      $mailData['recipient'] = $seller->email;

      $mailData['body'] = 'Hi ' . $seller->username . ',<br/><br/>We are pleased to inform you that your recent project with order number: #' . $order->order_number . ' has been successfully completed.';
      $mailData['sessionMessage'] = 'Order status updated & mail has been sent successfully!';
      BasicMailer::sendMail($mailData);
    } else {
      $order->update([
        'order_status' => 'rejected'
      ]);

      $mailData = [];
      $mailData['body'] = 'Hi ' . $order->name . ',<br/><br/>We are sorry to inform you that your recent project with order number: #' . $order->order_number . ' has been rejected.';

      $mailData['subject'] = 'Notification of order status';

      $mailData['recipient'] = $order->email_address;

      $mailData['sessionMessage'] = 'Order status updated & mail has been sent successfully!';

      BasicMailer::sendMail($mailData);
    }
    return redirect()->back();
  }

  public function show($id)
  {
    $order = ServiceOrder::query()->findOrFail($id);
    $queryResult['orderInfo'] = $order;

    $language = Language::query()->where('is_default', '=', 1)->first();

    // get service title
    $service = $order->service()->first();
    $queryResult['serviceTitle'] = $service->content()->where('language_id', $language->id)->select('title', 'slug')->first();

    // get package title
    $package = $order->package()->first();

    if (is_null($package)) {
      $queryResult['packageTitle'] = NULL;
    } else {
      $queryResult['packageTitle'] = $package->name;
    }

    return view('backend.client-service.order.details', $queryResult);
  }

  public function message($id)
  {

    $order = ServiceOrder::query()->findOrFail($id);
    $queryResult['order'] = $order;
    $language = Language::query()->where('is_default', '=', 1)->first();
    $service = $order->service()->first();
    $queryResult['serviceInfo'] = $service->content()->where('language_id', $language->id)->first();

    $messages = $order->message()->get();

    $messages->map(function ($message) {
      if ($message->person_type == 'admin') {
        $message['admin'] = $message->admin()->first();
      } else {
        $message['user'] = $message->user()->first();
      }
    });

    $queryResult['messages'] = $messages;

    $queryResult['bs'] = Basic::query()->select('pusher_key', 'pusher_cluster')->first();

    return view('backend.client-service.order.message', $queryResult);
  }

  public function storeMessage(MessageRequest $request, $id)
  {
    if ($request->hasFile('attachment')) {
      $file = $request->file('attachment');
      $fileName = UploadFile::store('./assets/file/message-files/', $file);
      $fileOriginalName = $file->getClientOriginalName();
    }

    $orderMsg = new ServiceOrderMessage();
    $orderMsg->person_id = Auth::guard('admin')->user()->id;
    $orderMsg->person_type = 'admin';
    $orderMsg->order_id = $id;
    $orderMsg->message = $request->filled('msg') ? Purifier::clean($request->msg, 'youtube') : NULL;
    $orderMsg->file_name = isset($fileName) ? $fileName : NULL;
    $orderMsg->file_original_name = isset($fileOriginalName) ? $fileOriginalName : NULL;
    $orderMsg->save();

    event(new MessageStored());

    return response()->json(['status' => 'Message stored.', 200]);
  }

  public function destroy($id)
  {
    $this->deleteOrder($id);

    return redirect()->back()->with('success', 'Order deleted successfully!');
  }

  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $this->deleteOrder($id);
    }

    $request->session()->flash('success', 'Orders deleted successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  // order deletion code
  public function deleteOrder($id)
  {
    $order = ServiceOrder::query()->find($id);

    // delete zip file(s) which has uploaded by user
    $informations = json_decode($order->informations);

    if (!is_null($informations)) {
      foreach ($informations as $key => $information) {
        if ($information->type == 8) {
          @unlink(public_path('assets/file/zip-files/' . $information->value));
        }
      }
    }

    // delete the receipt
    @unlink(public_path('assets/img/attachments/service/' . $order->receipt));

    // delete the invoice
    @unlink(public_path('assets/file/invoices/service/' . $order->invoice));

    // delete messages of this service-order
    $messages = $order->message()->get();

    foreach ($messages as $msgInfo) {
      if (!empty($msgInfo->file_name)) {
        @unlink(public_path('assets/file/message-files/' . $msgInfo->file_name));
      }

      $msgInfo->delete();
    }

    // delete review of this service-order
    $review = ServiceReview::query()->where('user_id', '=', $order->user_id)
      ->where('service_id', '=', $order->service_id)
      ->first();

    if (!empty($review)) {
      $review->delete();
    }

    // delete service-order
    $order->delete();
  }


  public function report(Request $request)
  {
    $queryResult['onlineGateways'] = OnlineGateway::query()->where('status', '=', 1)->get();
    $queryResult['offlineGateways'] = OfflineGateway::query()->where('status', '=', 1)->orderBy('serial_number', 'asc')->get();

    $from = $to = $paymentGateway = $paymentStatus = $orderStatus = null;

    if ($request->filled('payment_gateway')) {
      $paymentGateway = $request->payment_gateway;
    }
    if ($request->filled('payment_status')) {
      $paymentStatus = $request->payment_status;
    }
    if ($request->filled('order_status')) {
      $orderStatus = $request->order_status;
    }

    if ($request->filled('from') && $request->filled('to')) {
      $from = Carbon::parse($request->from)->toDateString();
      $to = Carbon::parse($request->to)->toDateString();

      $records = ServiceOrder::query()
        ->whereDate('created_at', '>=', $from)
        ->whereDate('created_at', '<=', $to)
        ->when($paymentGateway, function (Builder $query, $paymentGateway) {
          return $query->where('payment_method', '=', $paymentGateway);
        })
        ->when($paymentStatus, function (Builder $query, $paymentStatus) {
          return $query->where('payment_status', '=', $paymentStatus);
        })
        ->when($orderStatus, function (Builder $query, $orderStatus) {
          return $query->where('order_status', '=', $orderStatus);
        })
        ->select('order_number', 'name', 'email_address', 'service_id', 'package_id', 'package_price', 'addons', 'addon_price', 'tax', 'grand_total', 'currency_symbol', 'currency_symbol_position', 'payment_method', 'payment_status', 'order_status', 'created_at')
        ->orderByDesc('id');

      $collection_1 = $this->manipulateCollection($records->get());
      Session::put('service_orders', $collection_1);

      $collection_2 = $this->manipulateCollection($records->paginate(10));
      $queryResult['orders'] = $collection_2;
    } else {
      Session::put('service_orders', null);
      $queryResult['orders'] = [];
    }

    return view('backend.client-service.order.report', $queryResult);
  }

  public function manipulateCollection($orders)
  {
    $language = Language::query()->where('is_default', '=', 1)->first();

    $orders->map(function ($order) use ($language) {
      // service title
      $service = $order->service()->first();
      $order['serviceTitle'] = $service->content()->where('language_id', $language->id)->pluck('title')->first();

      // package name
      $package = $order->package()->first();

      if (is_null($package)) {
        $order['packageName'] = NULL;
      } else {
        $order['packageName'] = $package->name;
      }

      // addons name
      $addonIds = json_decode($order->addons);

      if (empty($addonIds)) {
        $addons = [];
      } else {
        $addons = [];

        foreach ($addonIds as $key => $value) {
          $addonName = $service->addon()->where('id', $value->id)->pluck('name')->first();

          array_push($addons, $addonName);
        }
      }

      $order['addonNames'] = $addons;

      // format created_at date
      $dateObj = Carbon::parse($order->created_at);
      $order['createdAt'] = $dateObj->format('M d, Y');
    });

    return $orders;
  }

  public function exportReport()
  {
    if (Session::has('service_orders')) {
      $serviceOrders = Session::get('service_orders');

      if (count($serviceOrders) == 0) {
        Session::flash('warning', 'No order found to export!');

        return redirect()->back();
      } else {
        return Excel::download(new ServiceOrdersExport($serviceOrders), 'service-orders.csv');
      }
    } else {
      Session::flash('error', 'There has no order to export.');

      return redirect()->back();
    }
  }

  public function sendMail(Request $request, $id)
  {
    // validation
    $rules = [
      'subject' => 'required|max:255'
    ];
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      Session::flash('error', 'Subject feild is required.');
      return back();
    }
    $serviceOrder = ServiceOrder::query()->find($id);
    $mailData = [];
    $mailData['subject'] = $request->subject;

    if ($request->filled('message')) {
      $msg = $request->message;
    } else {
      $msg = '';
    }

    $mailData['body'] = $msg;

    $mailData['recipient'] = $serviceOrder->email_address;

    $mailData['sessionMessage'] = 'Mail has been sent successfully!';

    BasicMailer::sendMail($mailData);
    return redirect()->back();
  }
}
