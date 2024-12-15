<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LocationGroupImage extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'avatar',
        'location_id',
        'location_group_id',
    ];

    // Casts
    protected function casts(): array
    {
        return [
            'avatar'            => 'string',
            'location_id'       => 'integer',
            'location_group_id' => 'integer'
        ];
    }


    // A location group image belongs to a location group
    public function locationGroup()
    {
        return $this->belongsTo(LocationGroup::class);
    }
}
