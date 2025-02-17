<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Meeting extends Model
{
    protected $fillable = [
        'user_id', 'title', 'description', 'link', 'date', 'time', 'status',
    ];



    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'date'    => 'date',
            'time'    => 'string',
        ];
    }

    // Accessor for 'time' (retrieves time as a Carbon instance)
    public function getTimeAttribute($value)
    {
        return Carbon::parse($value); // Return as Carbon instance
    }

    // Mutator for 'time' (save time in HH:MM:SS format)
    public function setTimeAttribute($value)
    {
        $this->attributes['time'] = Carbon::parse($value)->format('H:i:s'); // Store in HH:MM:SS format
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
