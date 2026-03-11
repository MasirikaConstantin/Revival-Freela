<?php

namespace App\Models\HomePage;

use App\Models\Concerns\SyncAcrossLanguages;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtaSectionInfo extends Model
{
    use HasFactory;
    use SyncAcrossLanguages;
    protected $fillable = [
        'language_id',
        'image',
        'title',
        'button_text',
        'button_url'
    ];
}
