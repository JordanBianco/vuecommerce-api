<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SavedProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_utente_può_salvare_per_dopo_un_articolo()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $product = Product::factory()->create();

        $this->postJson('/api/saved/' . $product->id);

        $this->assertDatabaseHas('product_user', [
            'id' => $product->id
        ]);
    }

    public function test_ritorno_tutti_gli_articoli_salvati()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $product = Product::factory()->create();

        $this->postJson('/api/saved/' . $product->id);

        $this->getJson('/api/saved')->assertJson(function($json) {
            $json->has('data', 1);
        });
    }

    public function test_utente_puo_svuotate_lista_articoli_salvati()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $nike = Product::factory()->create();
        $adidas = Product::factory()->create();

        $this->postJson('/api/saved/' . $nike->id);
        $this->postJson('/api/saved/' . $adidas->id);

        $this->deleteJson('/api/saved');

        $this->assertDatabaseMissing('product_user', [
            'id' => $nike->id
        ]);

        $this->assertDatabaseMissing('product_user', [
            'id' => $adidas->id
        ]);
    }

    public function test_utente_puo_rimuovere_un_articolo_dai_salvati()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $nike = Product::factory()->create();

        $this->postJson('/api/saved/' . $nike->id);

        $this->assertDatabaseHas('product_user', [
            'id' => $nike->id
        ]);

        $this->deleteJson('/api/saved/' . $nike->id);

        $this->assertDatabaseMissing('product_user', [
            'id' => $nike->id
        ]);
    }
}
