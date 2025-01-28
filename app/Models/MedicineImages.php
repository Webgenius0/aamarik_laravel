<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineImages extends Model
{
    use HasFactory;
    protected $fillable = [
        'medicine_id',
        'image',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'medicine_id' => 'integer',
            'image'    => 'string',
        ];
    }
}
