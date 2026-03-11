<?php

namespace App\Models\BasicSettings;

use App\Models\Concerns\SyncAcrossLanguages;
use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageHeading extends Model
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
    'blog_page_title',
    'pricing_page_title',
    'post_details_page_title',
    'contact_page_title',
    'error_page_title',
    'faq_page_title',
    'forget_password_page_title',
    'login_page_title',
    'signup_page_title',
    'services_page_title',
    'service_details_page_title',
    'about_us_page_title',
    'seller_page_title',
    'seller_login_page_title',
    'seller_signup_page_title',
    'seller_forget_password_page_title',
  ];

  public function headingLang()
  {
    return $this->belongsTo(Language::class);
  }
}
