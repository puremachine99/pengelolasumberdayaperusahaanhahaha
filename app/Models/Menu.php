<?php

namespace App\Models;

use App\Models\Recipe;
use App\Models\MenuCategory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'is_available',
        'images',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(MenuCategory::class);
    }
    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

}
