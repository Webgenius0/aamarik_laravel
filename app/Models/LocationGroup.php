<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LocationGroup extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'images',
        'location_id'
    ];

    // Casts
    protected function casts(): array
    {
        return [
            'name'        => 'string',
            'images'      => 'array',
            'location_id' => 'integer'
        ];
    }

    // A location group has many images
    public function images()
    {
        return $this->hasMany(LocationGroupImage::class);
    }


    // A location group belongs to a location
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
