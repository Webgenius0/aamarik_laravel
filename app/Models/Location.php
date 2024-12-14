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



    // Casts
    protected function casts(): array
    {
        return [
            'title'       => 'string',
            'address'     => 'string',
            'latitude'    => 'string',
            'longitude'   => 'string',
            'subtitle'    => 'string',
            'image'       => 'string',
            'information' => 'string',
            'map_image'   => 'string',
            'map_url'     => 'string',
            'points'      => 'integer',
            'puzzle_image'=> 'string',
            'status'      => 'string',
        ];
    }

    // A location has one location group
    public function locationGroup()
    {
        return $this->hasOne(LocationGroup::class);
    }
}
