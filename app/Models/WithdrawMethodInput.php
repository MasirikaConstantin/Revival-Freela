<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawMethodInput extends Model
{
    use HasFactory;

    protected $fillable = [
        'language_id',
        'withdraw_payment_method_id',
        'type',
        'label',
        'name',
        'placeholder',
        'required',
        'order_number',
    ];
    public function options()
    {
        return $this->hasMany(WithdrawMethodOption::class);
    }
}
