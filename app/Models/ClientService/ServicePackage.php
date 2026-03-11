<?php

namespace App\Models\ClientService;

use App\Models\ClientService\Service;
use App\Models\ClientService\ServiceOrder;
use App\Models\Concerns\SyncAcrossLanguages;
use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePackage extends Model
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
    'service_id',
    'name',
    'current_price',
    'previous_price',
    'delivery_time',
    'number_of_revision',
    'features'
  ];

  public function language()
  {
    return $this->belongsTo(Language::class, 'language_id', 'id');
  }

  public function service()
  {
    return $this->belongsTo(Service::class, 'service_id', 'id');
  }

  public function serviceOrder()
  {
    return $this->hasMany(ServiceOrder::class, 'package_id', 'id');
  }

  public function translationKeyFields(): array
  {
    return ['service_id', 'name'];
  }
}
