<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BilingAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'name',
        'email',
        'address',
        'contact',
        'city',
        'postcode',
        'gp_number',
        'gp_address',
    ];



    public function casts():array
    {
        return [
            'order_id'    => 'integer',
            'name'        => 'string',
            'email'       => 'string',
            'address'     => 'string',
            'contact'     => 'string',
            'city'        => 'string',
            'postcode'    => 'string',
            'gp_number'   => 'string',
            'gp_address'  => 'string',
        ];
    }

    // A BillingAddress belongs to an Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
