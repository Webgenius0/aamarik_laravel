<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentDetails extends Model
{
    use HasFactory;

    protected  $fillable = ['treatment_id','title','avatar'];

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
        ];
    }
}
