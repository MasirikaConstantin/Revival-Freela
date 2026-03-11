<?php

namespace App\Models\BasicSettings;

use App\Models\Concerns\SyncAcrossLanguages;
use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SEO extends Model
{
  use HasFactory;
  use SyncAcrossLanguages;

  protected $table = 'seos';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'language_id',
    'language_id',
    'meta_keyword_home',
    'meta_description_home',
    'meta_keyword_services',
    'meta_description_services',
    'meta_keyword_products',
    'meta_description_products',
    'meta_keyword_cart',
    'meta_description_cart',
    'meta_keyword_blog',
    'meta_description_blog',
    'meta_keyword_faq',
    'meta_description_faq',
    'meta_keyword_contact',
    'meta_description_contact',
    'meta_keyword_customer_login',
    'meta_description_customer_login',
    'meta_keyword_customer_signup',
    'meta_description_customer_signup',
    'meta_keyword_customer_forget_password',
    'meta_description_customer_forget_password',
    'meta_keyword_checkout',
    'meta_description_checkout',
    'meta_keyword_aboutus',
    'meta_description_aboutus',
    'meta_keyword_service_order',
    'meta_description_service_order',
    'meta_keyword_invoice_payment',
    'meta_description_invoice_payment',
    'seller_page_meta_keywords',
    'seller_page_meta_description',
    'meta_keyword_seller_login',
    'meta_description_seller_login',
    'meta_keyword_seller_signup',
    'meta_description_seller_signup',
    'meta_keyword_seller_forget_password',
    'meta_description_seller_forget_password',
    'pricing_page_meta_keywords',
    'pricing_page_meta_description',
  ];

  public function seoLang()
  {
    return $this->belongsTo(Language::class);
  }
}
