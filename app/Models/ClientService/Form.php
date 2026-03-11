<?php

namespace App\Models\ClientService;

use App\Models\ClientService\FormInput;
use App\Models\Concerns\SyncAcrossLanguages;
use App\Models\Language;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
  use HasFactory;
  use SyncAcrossLanguages;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['language_id', 'name', 'seller_id'];

  public function language()
  {
    return $this->belongsTo(Language::class, 'language_id', 'id');
  }
  public function seller()
  {
    return $this->belongsTo(Seller::class, 'seller_id', 'id');
  }

  public function input()
  {
    return $this->hasMany(FormInput::class, 'form_id', 'id');
  }

  public function translationKeyFields(): array
  {
    return ['seller_id', 'name'];
  }
}
