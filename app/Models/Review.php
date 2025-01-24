<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'order_id' => 'integer',
            'user_id'  => 'integer',
            'rating'   => 'integer',
            'review'   => 'string',
        ];
    }


    // Relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
