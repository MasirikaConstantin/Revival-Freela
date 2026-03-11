<?php

namespace App\Models\ClientService;

use App\Models\ClientService\Service;
use App\Models\Concerns\SyncAcrossLanguages;
use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceAddon extends Model
{
  use HasFactory;
  use SyncAcrossLanguages;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['language_id', 'service_id', 'name', 'price'];

  public function language()
  {
    return $this->belongsTo(Language::class, 'language_id', 'id');
  }

  public function service()
  {
    return $this->belongsTo(Service::class, 'service_id', 'id');
  }

  public function translationKeyFields(): array
  {
    return ['service_id', 'name'];
  }
}
