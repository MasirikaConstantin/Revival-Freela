<?php

namespace App\Models;

use App\Models\Concerns\SyncAcrossLanguages;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;
    use SyncAcrossLanguages;
    protected $fillable = [
        'language_id',
        'name',
        'slug',
        'status'
    ];

    public function translationKeyFields(): array
    {
        return ['slug'];
    }
}
