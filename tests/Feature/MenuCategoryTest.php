<?php

use App\Models\MenuCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('menu category can be created', function () {
    $category = MenuCategory::create([
        'name' => 'Test Category',
    ]);

    expect(MenuCategory::where('name', 'Test Category')->exists())->toBeTrue();
});

test('menu category can have menus', function () {
    $category = MenuCategory::create(['name' => 'Makanan']);

    $category->menus()->create([
        'name' => 'Nasi Goreng',
        'price' => 25000,
        'is_available' => true,
    ]);

    expect($category->menus)->toHaveCount(1);
});
