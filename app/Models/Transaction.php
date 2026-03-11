<?php

namespace App\Models;

use App\Models\ClientService\ServiceOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transcation_id',
        'order_id',
        'transcation_type',
        'user_id',
        'seller_id',
        'payment_status',
        'payment_method',
        'grand_total',
        'tax',
        'pre_balance',
        'after_balance',
        'gateway_type',
        'currency_symbol',
        'currency_symbol_position'
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id', 'id');
    }
    public function order()
    {
        return $this->belongsTo(ServiceOrder::class, 'order_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function method()
    {
        return $this->belongsTo(WithdrawPaymentMethod::class, 'payment_method', 'id');
    }
}
