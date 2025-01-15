<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentCategory extends Model
{
    use HasFactory;

    protected  $fillable = ['treatment_id','icon','title'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'treatment_id'  => 'integer',
            'icon'          => 'string',
            'title'         => 'string',
        ];
    }
}
