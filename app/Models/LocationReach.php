<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationReach extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'group_id',
        'image_id'
    ];


    // Casts
    protected function casts(): array
    {
        return [
            'user_id'   => 'integer',
            'group_id'  => 'integer',
            'image_id'  => 'integer'
        ];
    }



    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function group()
    {
        return $this->belongsTo(LocationGroup::class, 'group_id');
    }

    public function image()
    {
        return $this->belongsTo(LocationGroupImage::class, 'image_id');
    }
}
