<?php

namespace App\Models\ClientService;

use App\Models\ClientService\Form;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormInput extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'form_id',
    'type',
    'label',
    'placeholder',
    'name',
    'is_required',
    'options',
    'file_size',
    'order_no'
  ];

  public function form()
  {
    return $this->belongsTo(Form::class, 'form_id', 'id');
  }
}
