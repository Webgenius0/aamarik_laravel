<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'address',
        'latitude',
        'longitude',
        'subtitle',
        'image',
        'information',
        'map_image',
        'map_url',
        'points',
        'puzzle_image',
        'status',
    ];
}
