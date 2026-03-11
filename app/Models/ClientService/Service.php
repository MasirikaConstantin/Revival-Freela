<?php

namespace App\Models\ClientService;

use App\Models\ClientService\ServiceAddon;
use App\Models\ClientService\ServiceContent;
use App\Models\ClientService\ServiceFaq;
use App\Models\ClientService\ServiceOrder;
use App\Models\ClientService\ServicePackage;
use App\Models\ClientService\ServiceReview;
use App\Models\ClientService\WishlistService;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'seller_id',
    'thumbnail_image',
    'slider_images',
    'video_preview_link',
    'live_demo_link',
    'quote_btn_status',
    'service_status',
    'is_featured',
    'average_rating',
    'package_lowest_price',
    'skills',
  ];

  public function content()
  {
    return $this->hasMany(ServiceContent::class, 'service_id', 'id');
  }

  public function package()
  {
    return $this->hasMany(ServicePackage::class, 'service_id', 'id');
  }

  public function addon()
  {
    return $this->hasMany(ServiceAddon::class, 'service_id', 'id');
  }

  public function faq()
  {
    return $this->hasMany(ServiceFaq::class, 'service_id', 'id');
  }

  public function order()
  {
    return $this->hasMany(ServiceOrder::class, 'service_id', 'id');
  }

  public function review()
  {
    return $this->hasMany(ServiceReview::class, 'service_id', 'id');
  }

  public function wishlist()
  {
    return $this->hasMany(WishlistService::class, 'service_id', 'id');
  }
  public function seller()
  {
    return $this->belongsTo(Seller::class, 'seller_id', 'id');
  }
}
