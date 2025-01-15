<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutTreatment extends Model
{
    use HasFactory;

    protected  $fillable = ['treatment_id','title','avatar','short_description'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'treatment_id'  => 'integer',
            'avatar'        => 'string',
            'title'         => 'string',
            'short_description' => 'string',
        ];
    }
}
