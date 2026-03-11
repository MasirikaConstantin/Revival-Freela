<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductOrdersExport implements FromCollection, WithHeadings, WithMapping
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
      'Billing Name',
      'Billing Email Address',
      'Billing Phone Number',
      'Billing Address',
      'Billing City',
      'Billing State',
      'Billing Country',
      'Total',
      'Discount',
      'Tax',
      'Grand Total',
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
    // total price
    $total = ($order->currency_symbol_position == 'left' ? $order->currency_symbol : '') . $order->total . ($order->currency_symbol_position == 'right' ? $order->currency_symbol : '');

    // discount price
    $discount = ($order->currency_symbol_position == 'left' ? $order->currency_symbol : '') . $order->discount . ($order->currency_symbol_position == 'right' ? $order->currency_symbol : '');

    // tax price
    $tax = ($order->currency_symbol_position == 'left' ? $order->currency_symbol : '') . $order->tax . ($order->currency_symbol_position == 'right' ? $order->currency_symbol : '');

    // grand total price
    $grandTotal = ($order->currency_symbol_position == 'left' ? $order->currency_symbol : '') . $order->grand_total . ($order->currency_symbol_position == 'right' ? $order->currency_symbol : '');

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
      $order->billing_first_name . ' ' . $order->billing_last_name,
      $order->billing_email_address,
      $order->billing_phone_number,
      $order->billing_address,
      $order->billing_city,
      is_null($order->billing_state) ? '-' : $order->billing_state,
      $order->billing_country,
      $total,
      $discount,
      $tax,
      $grandTotal,
      $order->payment_method,
      $paymentStatus,
      $orderStatus,
      $order->createdAt
    ];
  }
}
