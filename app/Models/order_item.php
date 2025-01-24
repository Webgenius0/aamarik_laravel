<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order_item extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'medicine_id',
        'quantity',
        'unit_price',
        'total_price',
    ];

    public function casts():array
    {
        return [
            'order_id'    => 'integer',
            'medicine_id' => 'integer',
            'quantity'    => 'integer',
            'unit_price'  => 'decimal:2',
            'total_price' => 'decimal:2',
        ];
    }

    // An order item belongs to an order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // An order item belongs to a medicine
    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}
