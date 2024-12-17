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
    ];

    // Casts
    protected function casts(): array
    {
        return [
            'name'        => 'string',
        ];
    }

    // A location group has many images
    public function images()
    {
        return $this->hasMany(LocationGroupImage::class);
    }

}
