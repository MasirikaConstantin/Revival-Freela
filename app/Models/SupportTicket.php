<?php

namespace App\Models;

use App\Models\Admin;
use App\Models\TicketConversation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
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
    return $this->belongsTo(Seller::class, 'user_id', 'id');
  }

  public function admin()
  {
    return $this->belongsTo(Admin::class, 'admin_id', 'id');
  }

  public function conversation()
  {
    return $this->hasMany(TicketConversation::class, 'ticket_id', 'id');
  }
}
