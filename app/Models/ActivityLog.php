<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'order_id', 'action'];

    public function casts():array
    {
        return [
            'user_id'      => 'integer',
            'order_id'     => 'integer',
            'action'       => 'string',
        ];
    }

    //define relationship with activity log
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
