<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
    protected $fillable = [
        'user_id',
        'social_provider',
        'social_name',
        'social_id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
