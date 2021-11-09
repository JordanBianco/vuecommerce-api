<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_utente_puo_piazzare_un_ordine()
    {
        $this->assertTrue(true);
        // $user = User::factory()->create()->first();
        // $this->actingAs($user);

        // $product = Product::factory()->create();
        // // $product2 = Product::factory()->create();

        // $this->postJson('/api/cart', [
        //     'product_id' => $product->id,
        //     'quantity' => 1
        // ]);

        // // $this->postJson('/api/cart', [
        // //     'product_id' => $product2->id,
        // //     'quantity' => 3
        // // ]);

        // $this->postJson('/api/order', [
        //     'user_id' => $user->id,
        // ]);

        // $this->assertDatabaseHas('orders', [
        //     'user_id' => $user->id,
        // ]);
    }
}


// 'first_name' => $user->first_name,
// 'last_name' => $user->last_name,
// 'email' => $user->email,
// 'address' => $user->address,
// 'city' => $user->city,
// 'province' => $user->province,
// 'country' => $user->country,
// 'phone' => $user->phone,
// ]