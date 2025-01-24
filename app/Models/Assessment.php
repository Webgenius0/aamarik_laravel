<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected  $guarded = ['id'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'treatment_id'  => 'integer',
            'question'      => 'string',
            'answer'        => 'string',
        ];
    }

    /**
     * Relationship: An assessment can have many assessment results.
     */
    public function assessmentResults()
    {
        return $this->hasMany(AssessmentResult::class);
    }
}
