<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
   use HasFactory;
    protected $fillable = ['name', 'avatar'];



    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'name'   => 'string',
            'avatar' => 'string',
        ];
    }



    public function categories()
    {
        return $this->hasMany(TreatmentCategory::class);
    }

    public function detail()
    {
        return $this->hasOne(TreatmentDetails::class);
    }

    public function detailItems()
    {
        return $this->hasMany(DetailsItems::class);
    }

    public function about()
    {
        return $this->hasOne(AboutTreatment::class);
    }

    public function faqs()
    {
        return $this->hasMany(TreatmentFaq::class);
    }

    public function medicines()
    {
        return $this->belongsToMany(Medicine::class, 'treatment_medicines');
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }


}
