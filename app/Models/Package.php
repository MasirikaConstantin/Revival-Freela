<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'price',
        'term',
        'is_trial',
        'trial_days',
        'status',
        'number_of_service_add',
        'number_of_service_featured',
        'number_of_form_add',
        'number_of_service_order',
        'live_chat_status',
        'qr_builder_status',
        'qr_code_save_limit',
        'custom_features',
        'recommended'
    ];

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }
}
