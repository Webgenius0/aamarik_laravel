<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryInfo extends Model
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
            'description'   => 'string',
            'note'          => 'string',
            'option_name'   => 'string',
            'option_sub_description' => 'string',
            'option_value'   => 'decimal:2',
        ];
    }
}
