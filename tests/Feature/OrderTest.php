<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_utente_puo_effettuare_un_ordine()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);
        
        $this->placeOrder($user);

        $this->assertEquals(1, $user->orders->count());
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id, 
            'total' => $user->orders->first()->total,         
        ]);
    }

    public function test_un_utente_puo_vedere_i_suoi_ordini()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);
        
        $this->placeOrder($user);

        $this->getJson('/api/orders')->assertJson(function($json) {
            $json
                ->has('data')
                ->has('links')
                ->has('meta');
        });
    }
    
    public function test_un_utente_puo_archiviare_un_ordine()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);
        
        $this->placeOrder($user);

        $order = Order::first();

        $this->assertNull($order->archived_at);

        $this->patchJson('/api/orders/' . $order->id . '/archive')->assertStatus(200);

        $this->assertNotNull($order->fresh()->archived_at);
    }

    public function test_un_utente_puo_ripristinare_un_ordine_archiviato()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);
        
        $this->placeOrder($user);

        $order = Order::first();

        $this->patchJson('/api/orders/' . $order->id . '/archive')->assertStatus(200);
        $this->assertNotNull($order->fresh()->archived_at);

        $this->patchJson('/api/orders/' . $order->id . '/restore')->assertStatus(200);
        $this->assertNull($order->fresh()->archived_at);
    }

    public function test_un_utente_puo_vedere_gli_ordini_che_ha_archiviato()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);
        
        $this->placeOrder($user);

        $this->getJson('/api/orders/archived')->assertJson(function($json) {
            $json
                ->has('data')
                ->has('links')
                ->has('meta');
        });
    }

    protected function placeOrder($user)
    {
        $this->postJson('/api/orders', [
            'total' => 100,
            'user_id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'country' => $user->country,
            'city' => $user->city,
            'province' => $user->province,
            'address' => $user->address,
            'zipcode' => $user->zipcode,
            'phone' => 333333,
            'items' => [
                'product' => Product::factory()->create(),
                'quantity' => 1
            ]
        ]);
    }
}