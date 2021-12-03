<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Database\Factories\Helper\FactoryHelper;
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
        $this->call(CouponSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ProductSeeder::class);

        Product::each(function($product) {
            $product
                ->categories()
                ->sync([
                    FactoryHelper::getRandomModelId(Category::class),
                    FactoryHelper::getRandomModelId(Category::class)
                ]);
        });
    }
}
