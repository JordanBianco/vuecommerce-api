<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ProductSeeder::class);
        // $this->call(ReviewSeeder::class);

        Coupon::factory()->create();

        Product::each(function($product) {
            $product
                ->categories()
                ->attach([
                    Category::all()->random()->id,
                    Category::all()->random()->id,
                ]);
                // Dovrebbero essere unici! FIX
        });

        Product::each(function($product) {
            Review::factory(3)->create([
                'product_id' => $product->id
            ]);
        });
    }
}
