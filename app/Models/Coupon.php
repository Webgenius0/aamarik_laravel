<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_type',
        'discount_amount',
        'usage_limit',
        'used_count',
        'start_date',
        'end_date',
        'status',
    ];

    public function casts():array
    {
        return [
            'code'            => 'string', // Cast as string
            'discount_type'   => 'string', // Fixed or percentage
            'discount_amount' => 'decimal:2',
            'usage_limit'     => 'integer', // Maximum usage count
            'used_count'      => 'integer', // How many times the coupon has been used
            'start_date'      => 'datetime', // Start date as Carbon instance
            'end_date'        => 'datetime', // End date as Carbon instance
            'status'          => 'boolean', // Active/inactive status
        ];
    }


    /**
     * Check if the coupon is valid.
     */
    public function isValid()
    {
        $now = Carbon::now();

        // Check if the coupon is active
        if (!$this->status) {
            return false;
        }

        // Check if the coupon has expired
        if ($this->start_date && $now->lt($this->start_date)) {
            return false;
        }
        if ($this->end_date && $now->gt($this->end_date)) {
            return false;
        }

        // Check if the usage limit has been reached
        if ($this->usage_limit > 0 && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }


    /**
     * Apply the discount to a given amount.
     */
    public function applyDiscount($amount)
    {
        if ($this->discount_type === 'fixed') {
            return max($amount - $this->discount_amount, 0);
        } elseif ($this->discount_type === 'percentage') {
            return max($amount - ($amount * ($this->discount_amount / 100)), 0);
        }

        return $amount;
    }
}
