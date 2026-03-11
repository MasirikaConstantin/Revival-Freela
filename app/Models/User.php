<?php

namespace App\Models;

use App\Models\ClientService\ServiceOrder;
use App\Models\ClientService\ServiceOrderMessage;
use App\Models\ClientService\ServiceReview;
use App\Models\ClientService\WishlistService;
use App\Models\Shop\ProductOrder;
use App\Models\Shop\ProductReview;
use App\Models\Shop\WishlistProduct;
use App\Models\SupportTicket;
use App\Models\TicketConversation;;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
  use HasFactory, Notifiable;

  /**
   * The attributes that aren't mass assignable.
   *
   * @var array
   */
  protected $guarded = [];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  public function productReview()
  {
    return $this->hasMany(ProductReview::class, 'user_id', 'id');
  }

  public function serviceOrder()
  {
    return $this->hasMany(ServiceOrder::class, 'user_id', 'id');
  }

  public function serviceReview()
  {
    return $this->hasMany(ServiceReview::class, 'user_id', 'id');
  }

  public function wishlistedService()
  {
    return $this->hasMany(WishlistService::class, 'user_id', 'id');
  }

  public function wishlistedProduct()
  {
    return $this->hasMany(WishlistProduct::class, 'user_id', 'id');
  }

  public function message()
  {
    return $this->hasMany(ServiceOrderMessage::class, 'person_id', 'id');
  }

  public function ticket()
  {
    return $this->hasMany(SupportTicket::class, 'user_id', 'id');
  }

  public function ticketConversation()
  {
    return $this->hasMany(TicketConversation::class, 'person_id', 'id');
  }

  public function wishlistedProducts()
  {
    return $this->hasMany(WishlistProduct::class, 'user_id', 'id');
  }

  public function supportTickets()
  {
    return $this->hasMany(SupportTicket::class, 'user_id', 'id');
  }
}
