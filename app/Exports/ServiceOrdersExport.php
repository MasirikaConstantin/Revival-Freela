<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ServiceOrdersExport implements FromCollection, WithHeadings, WithMapping
{
  protected $orders;

  public function __construct($orders)
  {
    $this->orders = $orders;
  }

  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
    return $this->orders;
  }

  public function headings(): array
  {
    return [
      'Order No.',
      'Customer Name',
      'Customer Email Address',
      'Service',
      'Package',
      'Package Price',
      'Addons',
      'Addon Price',
      'Tax',
      'Total Price',
      'Paid via',
      'Payment Status',
      'Order Status',
      'Order Date'
    ];
  }

  /**
   * @var $order
   */
  public function map($order): array
  {
    // package price
    if (is_null($order->package_price)) {
      $packagePrice = '-';
    } else {
      $packagePrice = ($order->currency_symbol_position == 'left' ? $order->currency_symbol : '') . $order->package_price . ($order->currency_symbol_position == 'right' ? $order->currency_symbol : '');
    }

    // addon names
    if (count($order->addonNames) == 0) {
      $allAddons = '-';
    } else {
      $allAddons = '';

      // get the array length
      $arrLen = count($order->addonNames);

      foreach ($order->addonNames as $key => $addonName) {
        // checking whether the current index is the last position of the array
        if (($arrLen - 1) == $key) {
          $allAddons .= $addonName;
        } else {
          $allAddons .= $addonName . ', ';
        }
      }
    }

    // addon price
    if (is_null($order->addon_price)) {
      $addonPrice = '-';
    } else {
      $addonPrice = ($order->currency_symbol_position == 'left' ? $order->currency_symbol : '') . $order->addon_price . ($order->currency_symbol_position == 'right' ? $order->currency_symbol : '');
    }
    if (is_null($order->tax)) {
      $taxPrice = '-';
    } else {
      $taxPrice = ($order->currency_symbol_position == 'left' ? $order->currency_symbol : '') . $order->tax . ($order->currency_symbol_position == 'right' ? $order->currency_symbol : '');
    }

    // grand total
    if (is_null($order->grand_total)) {
      $grandTotal = 'Requested';
    } else {
      $grandTotal = ($order->currency_symbol_position == 'left' ? $order->currency_symbol : '') . $order->grand_total . ($order->currency_symbol_position == 'right' ? $order->currency_symbol : '');
    }

    // payment status
    if ($order->payment_status == 'completed') {
      $paymentStatus = 'Completed';
    } else if ($order->payment_status == 'pending') {
      $paymentStatus = 'Pending';
    } else {
      $paymentStatus = 'Rejected';
    }

    // order status
    if ($order->order_status == 'pending') {
      $orderStatus = 'Pending';
    } else if ($order->order_status == 'processing') {
      $orderStatus = 'Processing';
    } else if ($order->order_status == 'completed') {
      $orderStatus = 'Completed';
    } else {
      $orderStatus = 'Rejected';
    }

    return [
      '#' . $order->order_number,
      $order->name,
      $order->email_address,
      $order->serviceTitle,
      is_null($order->packageName) ? '-' : $order->packageName,
      $packagePrice,
      $allAddons,
      $addonPrice,
      $taxPrice,
      $grandTotal,
      is_null($order->payment_method) ? '-' : $order->payment_method,
      $paymentStatus,
      $orderStatus,
      $order->createdAt
    ];
  }
}
