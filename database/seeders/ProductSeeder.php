<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use Database\Factories\ReviewFactory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::factory(30)
            ->has(Review::factory(3), 'reviews')
            ->create();
    }
}
