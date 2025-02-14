<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'medicine_id',
        'avatar',
        'form',
        'dosage',
        'unit',
        'buying_price',
        'price',
        'expiry_date',
        'quantity',
        'stock_quantity'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'stock_quantity' => 'integer',
    ];

    // Define the inverse relationship with Medicine
    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}
