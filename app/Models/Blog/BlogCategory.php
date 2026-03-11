<?php

namespace App\Models\Blog;

use App\Models\Blog\PostInformation;
use App\Models\Concerns\SyncAcrossLanguages;
use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
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
    'name', 
    'slug', 
    'status', 
    'serial_number'
  ];

  public function categoryLang()
  {
    return $this->belongsTo(Language::class);
  }

  public function postInfo()
  {
    return $this->hasMany(PostInformation::class, 'blog_category_id', 'id');
  }

  public function translationKeyFields(): array
  {
    return ['slug'];
  }
}
