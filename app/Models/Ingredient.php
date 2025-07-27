<?php

namespace App\Models;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = ['name', 'unit', 'cost_per_unit', 'stock'];
    protected static function booted()
    {
        static::updated(function ($ingredient) {
            foreach ($ingredient->recipes as $recipe) {
                $recipe->menu?->updateCogs();
            }
        });
    }
    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }
}

