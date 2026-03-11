<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawMethodOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'withdraw_method_input_id',
        'name',
    ];
}
