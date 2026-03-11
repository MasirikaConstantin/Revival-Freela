<?php

namespace App\Models\ClientService;

use App\Models\ClientService\ServiceContent;
use App\Models\ClientService\ServiceSubcategory;
use App\Models\Concerns\SyncAcrossLanguages;
use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
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
    'image',
    'name',
    'slug',
    'status',
    'serial_number',
    'is_featured',
    'add_to_menu'
  ];

  public function language()
  {
    return $this->belongsTo(Language::class, 'language_id', 'id');
  }

  public function subcategory()
  {
    return $this->hasMany(ServiceSubcategory::class, 'service_category_id', 'id');
  }

  public function serviceContent()
  {
    return $this->hasMany(ServiceContent::class, 'service_category_id', 'id');
  }

  public function translationKeyFields(): array
  {
    return ['slug'];
  }
}
