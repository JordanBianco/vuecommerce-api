<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_una_review_appartiene_ad_un_prodotto()
    {
        Product::factory()->create();
        $review = Review::factory()->create();

        $this->assertInstanceOf(Product::class, $review->product);
    }

    public function test_un_prodotto_ha_molte_reviews()
    {
        $product = Product::factory()->create();

        $this->assertInstanceOf(Collection::class, $product->reviews);
    }
}
