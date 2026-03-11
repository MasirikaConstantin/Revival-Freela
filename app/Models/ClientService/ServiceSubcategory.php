<?php

namespace App\Models\ClientService;

use App\Models\ClientService\ServiceCategory;
use App\Models\ClientService\ServiceContent;
use App\Models\Concerns\SyncAcrossLanguages;
use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceSubcategory extends Model
{
  use HasFactory;
  use SyncAcrossLanguages;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'language_id', 
    'service_category_id', 
    'name', 
    'slug', 
    'status', 
    'serial_number'
  ];

  public function language()
  {
    return $this->belongsTo(Language::class, 'language_id', 'id');
  }

  public function category()
  {
    return $this->belongsTo(ServiceCategory::class, 'service_category_id', 'id');
  }

  public function serviceContent()
  {
    return $this->hasMany(ServiceContent::class, 'service_subcategory_id', 'id');
  }

  public function translationKeyFields(): array
  {
    return ['slug'];
  }
}
