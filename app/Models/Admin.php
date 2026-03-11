<?php

namespace App\Models;

use App\Models\ClientService\ServiceOrderMessage;
use App\Models\RolePermission;
use App\Models\SupportTicket;
use App\Models\TicketConversation;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model implements AuthenticatableContract
{
  use HasFactory, Authenticatable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'role_id',
    'first_name',
    'last_name',
    'image',
    'username',
    'email',
    'password',
    'status'
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = ['password'];

  public function role()
  {
    return $this->belongsTo(RolePermission::class, 'role_id', 'id');
  }

  public function message()
  {
    return $this->hasMany(ServiceOrderMessage::class, 'person_id', 'id');
  }

  public function ticket()
  {
    return $this->hasMany(SupportTicket::class, 'admin_id', 'id');
  }

  public function ticketConversation()
  {
    return $this->hasMany(TicketConversation::class, 'person_id', 'id');
  }
}
