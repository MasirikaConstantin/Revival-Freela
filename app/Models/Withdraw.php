<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'withdraw_id',
        'method_id',
        'amount',
        'payable_amount',
        'total_charge',
        'additional_reference',
        'feilds',
        'status',
    ];
    public function method()
    {
        return $this->belongsTo(WithdrawPaymentMethod::class, 'method_id', 'id');
    }
    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id', 'id');
    }
}
