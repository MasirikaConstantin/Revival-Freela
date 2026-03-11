<?php

namespace App\Models;

use App\Models\Admin;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketConversation extends Model
{
  use HasFactory;

  /**
   * The attributes that aren't mass assignable.
   *
   * @var array
   */
  protected $guarded = [];

  public function ticket()
  {
    return $this->belongsTo(SupportTicket::class, 'ticket_id', 'id');
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'person_id', 'id');
  }
  public function seller()
  {
    return $this->belongsTo(Seller::class, 'person_id', 'id');
  }

  public function admin()
  {
    return $this->belongsTo(Admin::class, 'person_id', 'id');
  }
}
