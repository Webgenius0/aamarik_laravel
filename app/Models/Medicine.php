<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'brand',
        'generic_name',
        'description',
        'status',
    ];

    protected $casts = [
        'title' => 'string',
        'brand' => 'string',
        'generic_name' => 'string',
        'description' => 'string',
        'status' => 'string',
    ];

    // Define the relationship with MedicineDetail
    public function details()
    {
        return $this->hasOne(MedicineDetails::class);
    }
    public function features()
    {
        return $this->hasMany(MedicineFeature::class);
    }

    public function images()
    {
        return $this->hasMany(MedicineImages::class);
    }
}
