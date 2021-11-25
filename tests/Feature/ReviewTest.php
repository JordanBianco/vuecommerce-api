<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_una_recensione_appartiene_ad_un_prodotto()
    {
        User::factory()->create();
        $product = Product::factory()->create();
        $review = Review::factory()->create(['product_id' => $product->id]);

        $this->assertInstanceOf(Product::class, $review->product);
    }

    public function test_un_prodotto_ha_molte_recensioni()
    {
        $product = Product::factory()->create();

        $this->assertInstanceOf(Collection::class, $product->reviews);
    }

    public function test_un_utente_puo_vedere_le_sue_recensioni()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();
        
        $review1 = Review::factory()->create([
            'user_id' => auth()->id(),
            'product_id' => $product1->id
        ]);
        $review2 = Review::factory()->create([
            'user_id' => auth()->id(),
            'product_id' => $product2->id
        ]);

        $this->getJson('api/reviews')
            ->assertJson(function($json) {
                $json->has('data', 2);
            });
    }

    public function test_un_utente_puo_lasciare_una_recensione()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $product = Product::factory()->create();

        $this->postJson('api/' . $product->id . '/review', $review = [
            'title' => 'test',
            'content' => 'test',
            'rating' => '4',
        ]);

        $this->assertDatabaseHas('reviews', $review);
    }

    public function test_un_utente_puo_modificare_una_recensione()
    {
        User::factory()->create();
        $this->actingAs(User::first());

        $product = Product::factory()->create();

        $review = Review::factory()->create([
            'user_id' => 1,
            'product_id' => $product->id
        ]);

        $this->patchJson('api/reviews/' . $review->id, [
            'title' => 'updated',
            'content' => $review->content,
            'rating' => $review->rating,
        ]);

        $this->assertEquals('updated', $review->fresh()->title);
    }

    public function test_un_utente_non_puo_modificare_una_recensione_altrui()
    {
        User::factory(2)->create();
        $this->actingAs(User::first());

        $product = Product::factory()->create();

        $review = Review::factory()->create([
            'user_id' => 2,
            'product_id' => $product->id
        ]);

        $this->patchJson('api/reviews/' . $review->id, [
            'rating' => 5
        ])->assertForbidden();
    }

    public function test_un_utente_puo_eliminare_una_sua_recensione()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $product = Product::factory()->create();
        $review = Review::factory()->create([
            'user_id' => auth()->id(),
            'product_id' => $product->id
        ]);

        $this->assertEquals(1, auth()->user()->reviews->count());

        $this->deleteJson('api/reviews/' . $review->id);

        $this->assertEquals(0, auth()->user()->fresh()->reviews->count());
    }

    public function test_un_utente_non_puo_eliminare_una_recensione_altrui()
    {
        User::factory(2)->create();
        $this->actingAs(User::first());

        $product = Product::factory()->create();

        $review = Review::factory()->create([
            'user_id' => 2,
            'product_id' => $product->id
        ]);

        $this->assertDatabaseHas('reviews', $review->only('id'));

        $this->deleteJson('api/reviews/' . $review->id)->assertForbidden();

        $this->assertDatabaseHas('reviews', $review->only('id'));
    }
}
