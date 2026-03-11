<?php

namespace App\Http\Controllers\Seller;

use App\Events\MessageStored;
use App\Exports\ServiceOrdersExport;
use App\Http\Controllers\Controller;
use App\Http\Helpers\BasicMailer;
use App\Http\Helpers\SellerPermissionHelper;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\MessageRequest;
use App\Models\BasicSettings\Basic;
use App\Models\ClientService\ServiceOrder;
use App\Models\ClientService\ServiceOrderMessage;
use App\Models\ClientService\ServiceReview;
use App\Models\Language;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\PaymentGateway\OnlineGateway;
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
        $orderNumber = $paymentStatus = $orderStatus = null;

        if ($request->filled('order_no')) {
            $orderNumber = $request['order_no'];
        }
        if ($request->filled('payment_status')) {
            $paymentStatus = $request['payment_status'];
        }
        if ($request->filled('order_status')) {
            $orderStatus = $request['order_status'];
        }

        $orders = ServiceOrder::query()->where('seller_id', Auth::guard('seller')->user()->id)
            ->when($orderNumber, function (Builder $query, $orderNumber) {
                return $query->where('order_number', 'like', '%' . $orderNumber . '%');
            })
            ->when($paymentStatus, function (Builder $query, $paymentStatus) {
                return $query->where('payment_status', '=', $paymentStatus);
            })
            ->when($orderStatus, function (Builder $query, $orderStatus) {
                return $query->where('order_status', '=', $orderStatus);
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

        return view('seller.order.index', compact('orders'));
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

    public function show($id)
    {
        $order = ServiceOrder::query()->where([['id', $id], ['seller_id', Auth::guard('seller')->user()->id]])->firstOrFail();
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

        return view('seller.order.details', $queryResult);
    }

    public function message($id)
    {
        $order = ServiceOrder::query()->where([['id', $id], ['seller_id', Auth::guard('seller')->user()->id]])->firstOrFail();

        //check live chat status active or not for this user
        if (!is_null($order->seller_id)) {
            $checkPermission =  SellerPermissionHelper::getPackageInfo($order->seller_id, $order->seller_membership_id);
            if ($checkPermission != true) {
                Session::flash('success', 'Live chat is not active for this order.');
                return redirect()->route('seller.dashboard');
            }
        }

        $queryResult['order'] = $order;
        $language = Language::query()->where('is_default', '=', 1)->first();
        $service = $order->service()->first();
        $queryResult['serviceInfo'] = $service->content()->where('language_id', $language->id)->first();

        $messages = $order->message()->get();

        $messages->map(function ($message) {
            if ($message->person_type == 'admin') {
                $message['admin'] = $message->admin()->first();
            } elseif ($message->person_type == 'seller') {
                $message['seller'] = $message->seller()->first();
            } else {
                $message['user'] = $message->user()->first();
            }
        });

        $queryResult['messages'] = $messages;

        $queryResult['bs'] = Basic::query()->select('pusher_key', 'pusher_cluster')->first();

        return view('seller.order.message', $queryResult);
    }

    public function storeMessage(MessageRequest $request, $id)
    {
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = UploadFile::store('./assets/file/message-files/', $file);
            $fileOriginalName = $file->getClientOriginalName();
        }

        $orderMsg = new ServiceOrderMessage();
        $orderMsg->person_id = Auth::guard('seller')->user()->id;
        $orderMsg->person_type = 'seller';
        $orderMsg->order_id = $id;
        $orderMsg->message = $request->filled('msg') ? Purifier::clean($request->msg, 'youtube') : null;
        $orderMsg->file_name = isset($fileName) ? $fileName : null;
        $orderMsg->file_original_name = isset($fileOriginalName) ? $fileOriginalName : null;
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
                ->where('seller_id', Auth::guard('seller')->user()->id)
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

        return view('seller.order.report', $queryResult);
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
            return response()->json(['errors' => $validator->getMessageBag()], 400);
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
