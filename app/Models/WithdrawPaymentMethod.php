<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawPaymentMethod extends Model
{
    use HasFactory;
    protected $fillable = [
        'min_limit',
        'max_limit',
        'name',
        'status',
        'fixed_charge',
        'percentage_charge',
    ];
}
