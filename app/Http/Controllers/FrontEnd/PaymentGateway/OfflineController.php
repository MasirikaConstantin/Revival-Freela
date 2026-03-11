<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\ClientService\OrderProcessController;
use App\Http\Helpers\UploadFile;
use App\Models\ClientService\Service;
use App\Models\PaymentGateway\OfflineGateway;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class OfflineController extends Controller
{
  public function index(Request $request, $data, $paymentFor)
  {
    $gatewayId = $request->gateway;
    $offlineGateway = OfflineGateway::query()->findOrFail($gatewayId);

    // validation start
    if ($offlineGateway->has_attachment == 1) {
      $rules = [
        'attachment' => [
          'required',
          new ImageMimeTypeRule()
        ]
      ];

      $message = [
        'attachment.required' => 'Please attach your payment receipt.'
      ];

      $validator = Validator::make($request->only('attachment'), $rules, $message);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator->errors())->withInput();
      }
    }
    // validation end

    if ($paymentFor == 'service') {
      $directory = './assets/img/attachments/service/';
    } else {
      $directory = './assets/img/attachments/';
    }

    // store attachment in local storage
    if ($request->hasFile('attachment')) {
      $attachmentName = UploadFile::store($directory, $request->file('attachment'));
    }

    $currencyInfo = $this->getCurrencyInfo();

    if ($paymentFor != 'invoice') {
      $data['currencyText'] = $currencyInfo->base_currency_text;
      $data['currencyTextPosition'] = $currencyInfo->base_currency_text_position;
      $data['currencySymbol'] = $currencyInfo->base_currency_symbol;
      $data['currencySymbolPosition'] = $currencyInfo->base_currency_symbol_position;
      $data['paymentMethod'] = $offlineGateway->name;
      $data['gatewayType'] = 'offline';
      $data['paymentStatus'] = 'pending';
      $data['orderStatus'] = 'pending';
      $data['receiptName'] = $request->exists('attachment') ? $attachmentName : null;
    }

    if ($paymentFor == 'service') {
      // store service order information in database
      $selected_service = Service::where('id', $data['serviceId'])->select('seller_id')->first();
      if ($selected_service->seller_id != 0) {
        $data['seller_id'] = $selected_service->seller_id;
        $checkPermission = sellerPermission($selected_service->seller_id, 'service-order');
        if ($checkPermission['status'] == 'false') {
          Session::flash('error', 'The seller maximum order limit exceeded.');
          return back();
        }
      } else {
        $data['seller_id'] = null;
      }
      $orderProcess = new OrderProcessController();

      // store service order information in database
      $orderProcess->storeData($data);

      $serviceSlug = $data['slug'];

      return redirect()->route('service.place_order.complete', ['slug' => $serviceSlug, 'via' => 'offline']);
    } else {
      // update info in db
      $invoice = $data['invoice'];

      $invoice->update([
        'payment_status' => 'pending',
        'payment_method' => $offlineGateway->name,
        'gateway_type' => 'offline',
        'receipt' => isset($attachmentName) ? $attachmentName : null
      ]);

      return redirect()->route('pay.complete', ['via' => 'offline']);
    }
  }
}
