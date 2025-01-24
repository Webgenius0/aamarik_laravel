<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'title'           => 'string',
            'sub_description' => 'string',
            'image'           => 'string',
            'feature1'        => 'string',
            'feature2'        => 'string',
            'feature3'        => 'string',
            'button_name'     => 'string',
            'button_url'      => 'string',
        ];
    }
}
