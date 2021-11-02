<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_lista_di_categorie_nella_homepage()
    {
        $this->withoutExceptionHandling();
        
        Category::factory(4)->create();

        $this->getJson('/api/categories')->assertJson(function($json) {
            $json->has('data', 4);
        });
    }
}
