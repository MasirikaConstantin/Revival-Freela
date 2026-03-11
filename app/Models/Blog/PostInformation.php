<?php

namespace App\Models\Blog;

use App\Models\Blog\BlogCategory;
use App\Models\Blog\Post;
use App\Models\Concerns\SyncAcrossLanguages;
use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostInformation extends Model
{
  use HasFactory;
  use SyncAcrossLanguages;

  protected $table = 'post_informations';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'language_id',
    'blog_category_id',
    'post_id',
    'title',
    'slug',
    'author',
    'content',
    'meta_keywords',
    'meta_description'
  ];

  public function language()
  {
    return $this->belongsTo(Language::class, 'language_id', 'id');
  }

  public function postCategory()
  {
    return $this->belongsTo(BlogCategory::class, 'blog_category_id', 'id');
  }

  public function post()
  {
    return $this->belongsTo(Post::class, 'post_id', 'id');
  }

  public function translationKeyFields(): array
  {
    return ['post_id'];
  }
}
