<?php

namespace App\Models;

use App\Models\DuaSubcategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DuaCategory extends Model
{
    use HasFactory;
    protected $guarded = [];


    /**
     * Get all of the dua for the DuaCategory
     * One to many relationship
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function duas()
    {
        return $this->hasMany(Dua::class, 'category_id');
    }
    public function subcategories()
    {
        return $this->hasMany(DuaSubcategory::class,'category_id');
    }
}
