<?php

namespace App\Models\ClientService;

use App\Models\Admin;
use App\Models\ClientService\ServiceOrder;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceOrderMessage extends Model
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
    return $this->belongsTo(User::class, 'person_id', 'id');
  }

  public function admin()
  {
    return $this->belongsTo(Admin::class, 'person_id', 'id');
  }
  public function seller()
  {
    return $this->belongsTo(Seller::class, 'person_id', 'id');
  }

  public function order()
  {
    return $this->belongsTo(ServiceOrder::class, 'order_id', 'id');
  }
}
