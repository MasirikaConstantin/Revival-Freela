<?php

namespace App\Models\HomePage;

use App\Models\Concerns\SyncAcrossLanguages;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
  use HasFactory;
  use SyncAcrossLanguages;

  /**
   * The attributes that aren't mass assignable.
   *
   * @var array
   */
  protected $guarded = [];
}
