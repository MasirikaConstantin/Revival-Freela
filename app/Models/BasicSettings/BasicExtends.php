<?php

namespace App\Models\BasicSettings;

use App\Models\Concerns\SyncAcrossLanguages;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasicExtends extends Model
{
    use HasFactory;
    use SyncAcrossLanguages;

    protected $guarded = [];
}
