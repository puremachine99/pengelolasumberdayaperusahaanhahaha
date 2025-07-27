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
        'cogs', // Cost of Goods Sold
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function updateCogs(): void
    {
        logger("Updating COGS for menu: {$this->id}");

        $cogs = $this->recipes->sum(function ($recipe) {
            $ingredientCost = $recipe->ingredient->cost_per_unit ?? 0;
            return $ingredientCost * $recipe->quantity;
        });

        logger("COGS result: $cogs");

        $this->update(['cogs' => $cogs]);
    }
    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function category()
    {
        return $this->belongsTo(MenuCategory::class);
    }

}
