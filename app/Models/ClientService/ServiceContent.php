<?php

namespace App\Models\ClientService;

use App\Models\ClientService\Service;
use App\Models\ClientService\ServiceCategory;
use App\Models\ClientService\ServiceSubcategory;
use App\Models\Concerns\SyncAcrossLanguages;
use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceContent extends Model
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
    'service_subcategory_id',
    'service_id',
    'form_id',
    'title',
    'slug',
    'description',
    'tags',
    'skills',
    'meta_keywords',
    'meta_description'
  ];

  public function language()
  {
    return $this->belongsTo(Language::class, 'language_id', 'id');
  }

  public function category()
  {
    return $this->belongsTo(ServiceCategory::class, 'service_category_id', 'id');
  }

  public function subcategory()
  {
    return $this->belongsTo(ServiceSubcategory::class, 'service_subcategory_id', 'id');
  }

  public function service()
  {
    return $this->belongsTo(Service::class, 'service_id', 'id');
  }

  public function translationKeyFields(): array
  {
    return ['service_id'];
  }
}
