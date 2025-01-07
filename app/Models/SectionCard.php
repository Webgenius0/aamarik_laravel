<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionCard extends Model
{
    use HasFactory;

    // Mass assignable attributes
    protected $fillable = [
        'section_id',
        'title',
        'sub_title',
        'avatar'
    ];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'section_id' => 'integer',
            'title'      => 'string',
            'sub_title'  => 'string',
            'avatar'     => 'string'
        ];
    }
}
