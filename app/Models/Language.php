<?php

namespace App\Models;

use App\Models\BasicSettings\BasicExtends;
use App\Models\BasicSettings\CookieAlert;
use App\Models\BasicSettings\PageHeading;
use App\Models\BasicSettings\SEO;
use App\Models\Blog\BlogCategory;
use App\Models\Blog\PostInformation;
use App\Models\ClientService\Form;
use App\Models\ClientService\ServiceAddon;
use App\Models\ClientService\ServiceCategory;
use App\Models\ClientService\ServiceContent;
use App\Models\ClientService\ServiceFaq;
use App\Models\ClientService\ServicePackage;
use App\Models\ClientService\ServiceSubcategory;
use App\Models\CustomPage\PageContent;
use App\Models\FAQ;
use App\Models\Footer\FooterContent;
use App\Models\Footer\QuickLink;
use App\Models\HomePage\AboutSection;
use App\Models\HomePage\Feature;
use App\Models\HomePage\HeroSlider;
use App\Models\HomePage\HeroStatic;
use App\Models\HomePage\SectionTitle;
use App\Models\HomePage\Testimonial;
use App\Models\MenuBuilder;
use App\Models\Popup;
use App\Models\Shop\ShippingCharge;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['name', 'code', 'direction', 'is_default'];

  public function pageName()
  {
    return $this->hasOne(PageHeading::class);
  }

  public function seoInfo()
  {
    return $this->hasOne(SEO::class);
  }

  public function cookieAlertInfo()
  {
    return $this->hasOne(CookieAlert::class);
  }

  public function faq()
  {
    return $this->hasMany(FAQ::class);
  }

  public function customPageInfo()
  {
    return $this->hasMany(PageContent::class);
  }

  public function footerContent()
  {
    return $this->hasOne(FooterContent::class);
  }

  public function footerQuickLink()
  {
    return $this->hasMany(QuickLink::class);
  }

  public function announcementPopup()
  {
    return $this->hasMany(Popup::class);
  }

  public function blogCategory()
  {
    return $this->hasMany(BlogCategory::class);
  }

  public function postInformation()
  {
    return $this->hasMany(PostInformation::class, 'language_id', 'id');
  }

  public function menuInfo()
  {
    return $this->hasOne(MenuBuilder::class, 'language_id', 'id');
  }

  public function testimonial()
  {
    return $this->hasMany(Testimonial::class, 'language_id', 'id');
  }

  public function shippingCharge()
  {
    return $this->hasMany(ShippingCharge::class);
  }

  public function serviceCategory()
  {
    return $this->hasMany(ServiceCategory::class, 'language_id', 'id');
  }

  public function skill()
  {
    return $this->hasMany(Skill::class, 'language_id', 'id');
  }

  public function serviceSubcategory()
  {
    return $this->hasMany(ServiceSubcategory::class, 'language_id', 'id');
  }

  public function serviceContent()
  {
    return $this->hasMany(ServiceContent::class, 'language_id', 'id');
  }

  public function servicePackage()
  {
    return $this->hasMany(ServicePackage::class, 'language_id', 'id');
  }

  public function serviceAddon()
  {
    return $this->hasMany(ServiceAddon::class, 'language_id', 'id');
  }

  public function serviceFaq()
  {
    return $this->hasMany(ServiceFaq::class, 'language_id', 'id');
  }

  public function form()
  {
    return $this->hasMany(Form::class, 'language_id', 'id');
  }

  public function heroSlider()
  {
    return $this->hasMany(HeroSlider::class, 'language_id', 'id');
  }

  public function sectionTitle()
  {
    return $this->hasOne(SectionTitle::class, 'language_id', 'id');
  }

  public function aboutSection()
  {
    return $this->hasOne(AboutSection::class, 'language_id', 'id');
  }

  public function feature()
  {
    return $this->hasMany(Feature::class, 'language_id', 'id');
  }

  public function heroStatic()
  {
    return $this->hasOne(HeroStatic::class, 'language_id', 'id');
  }

  public function basicExtend()
  {
    return $this->hasOne(BasicExtends::class, 'language_id', 'id');
  }
}
