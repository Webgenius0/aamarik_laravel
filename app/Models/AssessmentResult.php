<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentResult extends Model
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
            'order_id'       => 'integer',
            'treatment_id'   => 'integer',
            'assessment_id'  => 'integer',
            'selected_option'=> 'string',
            'result'         => 'string',
            'notes'          => 'string',
        ];
    }
}
