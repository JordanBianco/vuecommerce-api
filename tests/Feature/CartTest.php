<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_ad_ogni_utente_appartiene_un_carrello()
    {
        $user = User::factory()->create();
        $this->assertInstanceOf(Cart::class, $user->cart);
    }

    public function test_un_carrello_appartiene_ad_un_utente()
    {
        $cart = Cart::factory()->create();
        $this->assertInstanceOf(User::class, $cart->user);
    }

    public function test_un_utente_loggato_puo_aggiungere_elementi_nel_carrello()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $product = Product::factory()->create()->first();

        $this->postJson('/api/cart', [
            'product_id' => $product->id,
            'quantity' => 1
        ]);

        $this->assertDatabaseHas('cart_product', [
            'product_id' => $product->id,
            'quantity' => 1
        ]);
    }

    public function test_se_un_utente_aggiunge_un_elemento_gia_presente_nel_carrello_aumenta_la_sua_quantita()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $product = Product::factory()->create()->first();

        $this->postJson('/api/cart', [
            'product_id' => $product->id,
            'quantity' => 1
        ]);

        $this->assertDatabaseHas('cart_product', [
            'id' => $product->id,
            'quantity' => 1
        ]);

        $this->postJson('/api/cart', [
            'product_id' => $product->id,
            'quantity' => 3
        ]);

        $this->assertDatabaseHas('cart_product', [
            'id' => $product->id,
            'quantity' => 4
        ]);
    }

    public function test_carrello_ritorna_tutti_gli_elementi()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $product = Product::factory()->create(['name' => 'nike'])->first();

        $this->postJson('/api/cart', [
            'product_id' => $product->id,
            'quantity' => 1
        ]);

        // , ['user_id' => $user->id]
        $this->json('GET', '/api/cart')
            ->assertJson(function($json) {
                $json->has('data')
                    ->has('data.0.product', function($json) {
                        $json
                            ->where('name', 'nike')
                            ->etc();
                    });
            });
    }

    public function test_un_elemento_puo_essere_rimosso_dal_carrello()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $nike = Product::factory()->create(['name' => 'nike'])->first();
        $adidas = Product::factory()->create(['name' => 'adidas'])->first();

        $this->postJson('/api/cart', [
            'product_id' => $nike->id,
            'quantity' => 1
        ]);

        $this->postJson('/api/cart', [
            'product_id' => $adidas->id,
            'quantity' => 1
        ]);

        $this->assertDatabaseHas('cart_product', [
            'id' => $adidas->id
        ]);

        $this->deleteJson('/api/cart/' . $adidas->id);

        $this->assertDatabaseMissing('cart_product', [
            'id' => $adidas->id
        ]);
    }

    public function test_utente_puo_svuotare_il_carrello()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $nike = Product::factory()->create(['name' => 'nike'])->first();
        $adidas = Product::factory()->create(['name' => 'adidas'])->first();

        $this->postJson('/api/cart', [
            'product_id' => $nike->id,
            'quantity' => 1
        ]);

        $this->postJson('/api/cart', [
            'product_id' => $adidas->id,
            'quantity' => 1
        ]);

        $this->deleteJson('/api/cart');

        $this->assertDatabaseMissing('cart_product', [
            'id' => $nike->id,
        ]);

        $this->assertDatabaseMissing('cart_product', [
            'id' => $adidas->id,
        ]);
    }

    public function test_un_utente_puo_aumentare_la_quantita_di_un_articolo_nel_carrello()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $product = Product::factory()->create()->first();

        $this->postJson('/api/cart', [
            'product_id' => $product->id,
            'quantity' => 1
        ]);

        $this->patchJson('/api/cart/' . $product->id . '/increment');

        $this->assertDatabaseHas('cart_product', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
    }

    public function test_un_utente_puo_diminuire_la_quantita_di_un_articolo_nel_carrello()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $product = Product::factory()->create()->first();

        $this->postJson('/api/cart', [
            'product_id' => $product->id,
            'quantity' => 3
        ]);

        $this->patchJson('/api/cart/' . $product->id . '/decrement');

        $this->assertDatabaseHas('cart_product', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
    }
}
