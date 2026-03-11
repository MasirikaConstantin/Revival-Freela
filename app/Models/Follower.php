<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    use HasFactory;
    protected $fillable = [
        'follower_id',
        'following_id',
        'type'
    ];

    public function follower_user()
    {
        return $this->belongsTo(User::class, 'follower_id', 'id');
    }
    public function follower_seller()
    {
        return $this->belongsTo(Seller::class, 'follower_id', 'id');
    }
    public function following_seller()
    {
        return $this->belongsTo(Seller::class, 'following_id', 'id');
    }
}
