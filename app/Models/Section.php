<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;
    // Mass assignable attributes
    protected $fillable = [
        'title',
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
            'type'         => 'string',
        ];
    }


    /**
     * Get the section cards for the section.
     */
    public function sectionCards()
    {
        return $this->hasMany(SectionCard::class, 'section_id');
    }
}
