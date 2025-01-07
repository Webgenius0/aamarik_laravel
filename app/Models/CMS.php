<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CMS extends Model
{
    use HasFactory;

    // Mass assignable attributes
    protected $fillable = [
        'title',
        'sub_title',
        'description',
        'avatar',
        'button_name',
        'button_url',
        'type',
    ];


        /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'title'        => 'string',
            'sub_title'    => 'string',
            'description'  => 'string',
            'avatar'       => 'string',
            'button_name'  => 'string',
            'button_url'   => 'string',
            'type'         => 'string',
        ];
    }
}
