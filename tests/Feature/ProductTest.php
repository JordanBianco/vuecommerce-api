<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_lista_di_tutti_i_prodotti()
    {
        Product::factory(4)->create();
        Product::first()->update(['name' => 'nike']);

        $this->getJson('/api/products')
            ->assertStatus(200)
            ->assertJson(function($json) {
                $json
                    ->has('data')
                    ->has('links')
                    ->has('meta')
                    ->has('data.0', function($json) {
                        $json
                            ->where('name', 'nike')
                            ->etc();
                    });
            });
    }

    public function test_singolo_prodotto()
    {
        $product = Product::factory()->create(['name' => 'nike'])->first();

        $this->getJson('/api/products/' . $product->slug)
            ->assertStatus(200)
            ->assertJson(function($json) {
                $json
                    ->has('data', function($json) {
                        $json
                            ->where('name', 'nike')
                            ->etc();
                    });
            });
    }

    public function test_un_utente_puo_cercare_un_articolo()
    {
        Product::factory()->create([
            'name' => 'nike',
            'description' => 'nike desc'
        ]);

        Product::factory()->create([
            'name' => 'adidas',
            'description' => 'adidas desc'
        ]);

        $this->getJson('/api/products?search=nike')
            ->assertStatus(200)
            ->assertJson(function($json) {
                $json
                    ->has('data', 1)
                    ->has('links')
                    ->has('meta')
                    ->has('data.0', function($json) {
                        $json
                            ->where('name', 'nike')
                            ->etc();
                    });
            });
    }
}
