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
                    ->has('data.0', function($json) {
                        $json
                            ->where('name', 'nike')
                            ->etc();
                    });
            });
    }
}
