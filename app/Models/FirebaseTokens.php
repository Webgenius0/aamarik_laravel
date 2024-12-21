<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirebaseTokens extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'token',
        'device_id',
        'is_active',
    ];

    // Type casting for attributes
    protected $casts = [
        'user_id'           => 'integer',
        'device_id'         => 'string',
        'token'             => 'string',
        'is_active'         => 'boolean',

    ];
}
