<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'treatment_id',
        'coupon_id',
        'tracked',
        'royal_mail_tracked_price',
        'sub_total',
        'discount',
        'total_price',
        'pay_amount',
        'due_amount',
        'stripe_payment_id',
        'subscription',
        'prescription',
        'note',
        'status',
    ];



    public function casts():array
    {
        return [
            'uuid'         => 'string',
            'user_id'      => 'integer',
            'treatment_id' => 'integer',
            'coupon_id'    => 'integer',
            'tracked'      => 'boolean',
            'royal_mail_tracked_price' => 'decimal:2',
            'sub_total'    => 'decimal:2',  //total amount
            'discount'     => 'decimal:2', //discount amount
            'total_price'  => 'decimal:2', //after discount
            'pay_amount'   => 'decimal:2',
            'due_amount'   => 'decimal:2',
            'stripe_payment_id' => 'string',
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
        return $this->hasOne(Review::class);
    }

    // An order has one billing address
    public function billingAddress()
    {
        return $this->hasOne(BilingAddress::class);
    }


    // Define the relationship with the Coupon model
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function assessmentsResults()
    {
        return $this->hasMany(AssessmentResult::class);
    }
}
