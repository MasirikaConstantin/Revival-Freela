<?php

namespace App\Models\ClientService;

use App\Models\ClientService\Service;
use App\Models\ClientService\ServiceOrderMessage;
use App\Models\ClientService\ServicePackage;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceOrder extends Model
{
  use HasFactory;

  /**
   * The attributes that aren't mass assignable.
   *
   * @var array
   */
  protected $guarded = [];

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }
  public function seller()
  {
    return $this->belongsTo(Seller::class, 'seller_id', 'id');
  }

  public function service()
  {
    return $this->belongsTo(Service::class, 'service_id', 'id');
  }

  public function package()
  {
    return $this->belongsTo(ServicePackage::class, 'package_id', 'id');
  }

  public function message()
  {
    return $this->hasMany(ServiceOrderMessage::class, 'order_id', 'id');
  }
}
