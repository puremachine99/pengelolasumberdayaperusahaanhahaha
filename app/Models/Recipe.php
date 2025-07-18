<?php

namespace App\Models;

use App\Models\Menu;
use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = ['menu_id', 'ingredient_id', 'quantity', 'unit'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}
