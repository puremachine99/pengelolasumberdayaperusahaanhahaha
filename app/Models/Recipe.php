<?php

namespace App\Models;

use App\Models\Menu;
use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = ['menu_id', 'ingredient_id', 'quantity', 'unit'];

    protected static function booted()
    {
        static::saved(fn($recipe) => $recipe->menu?->updateCogs());
        static::deleted(fn($recipe) => $recipe->menu?->updateCogs());
    }


    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

}
