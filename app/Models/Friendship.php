<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    use HasFactory;
    protected $fillable = [
        'follower_id',
        'followed_id',
    ];

    // Casts
    protected function casts(): array
    {
        return [
            'follower_id'        => 'integer',
            'followed_id'        => 'integer',
        ];
    }
}
