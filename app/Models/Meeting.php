<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = [
        'user_id', 'title', 'description', 'link', 'date', 'time', 'status',
    ];

    // Typecasting
    protected $casts = [
        'user_id' => 'integer',
        'date'    => 'date',
        'time'    => 'time',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
