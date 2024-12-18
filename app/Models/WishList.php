<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WishList extends Model
{
    // Mass assignable fields
    protected $fillable = [
        'user_id',
        'location_id',
    ];


    // Casts
    protected function casts(): array
    {
        return [
            'user_id'     => 'integer',
            'location_id' => 'integer',
        ];
    }


    /**
     * Define relationship to the Location model.
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
