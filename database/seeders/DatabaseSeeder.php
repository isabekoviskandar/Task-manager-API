<?php

namespace Database\Seeders;

use App\Models\AttChar;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Characteristic;
use App\Models\Element;
use App\Models\Option;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Texnika',
        ]);
        Category::create([
            'name' => 'Ozuq-ovqat',
        ]);
        Category::create([
            'name' => 'Kiyim-kechak',
        ]);
        Category::create([
           'name' => 'Ob havo'
        ]);
        Category::create([
           'name' => 'Sport'
        ]);
        Category::create([
           'name' => 'Jahon yangiliklari'
        ]);

        for ($i = 0; $i < 10; $i++) {
            Product::create([
                'category_id' => rand(1, 6),
                'name' => 'Product ' . $i,
                'description' => 'Description ' . $i,
            ]);
        }
    }
}
