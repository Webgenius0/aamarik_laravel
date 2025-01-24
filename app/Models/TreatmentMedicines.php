<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentMedicines extends Model
{
    use HasFactory;

    protected  $fillable = ['treatment_id','question','answer','medicine_id'];

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


    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }
    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}
