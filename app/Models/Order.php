<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'treatment_id',
        'tracked',
        'royal_mail_tracked_price',
        'total_price',
        'subscription',
        'prescription',
        'note',
        'status',
    ];



    public function casts():array
    {
        return [
            'user_id'      => 'integer',
            'treatment_id' => 'integer',
            'tracked'      => 'boolean',
            'royal_mail_tracked_price' => 'decimal:2',
            'total_price'  => 'decimal:2',
            'subscription' => 'boolean',
            'prescription' => 'string',
            'note'         => 'string',
            'status'       => 'string',
        ];
    }




    // An order belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // An order may belong to a treatment
    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }

    // An order has many order items
    public function orderItems()
    {
        return $this->hasMany(order_item::class);
    }

    // An order can have many assessment results
    public function assessmentResults()
    {
        return $this->hasMany(AssessmentResult::class);
    }

    public function review()
    {
        $this->hasOne(Review::class);
    }

    // An order has one billing address
    public function billingAddress()
    {
        return $this->hasOne(BilingAddress::class);
    }
}
