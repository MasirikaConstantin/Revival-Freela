<?php

namespace App\Models\Blog;

use App\Models\Blog\PostInformation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
  use HasFactory;

  protected $table = 'posts';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['image', 'serial_number'];

  public function information()
  {
    return $this->hasMany(PostInformation::class, 'post_id', 'id');
  }
}
