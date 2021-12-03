<?php

namespace Tests\Feature\Admin;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_può_vedere_la_lista_di_tutti_gli_utenti_iscritti()
    {
        $user = User::factory()->create(['is_admin' => true])->first();
        $this->actingAs($user);

        User::factory(3)->create();

        $this->getJson('/api/admin/users')
            ->assertStatus(200)
            ->assertJson(function($json) {
                $json
                    ->has('data', 4)
                    ->has('links')
                    ->has('meta');
            });
    }

    public function test_admin_può_visitare_la_pagina_di_un_singolo_utente()
    {
        $admin = User::factory()->create(['is_admin' => true])->first();
        $this->actingAs($admin);

        $user = User::factory()->create();

        $this->getJson('/api/admin/users/' . $user->id)
            ->assertStatus(200)
            ->assertJson(function($json) use($user) {
                $json
                    ->has('data', function($json) use($user) {
                        $json
                            ->where('first_name', $user->first_name)
                            ->etc();
                    });
            });
    }

    public function test_admin_può_aggiornare_un_utente()
    {
        $admin = User::factory()->create(['is_admin' => true])->first();
        $this->actingAs($admin);

        $user = User::factory()->create();

        $this->patchJson('/api/admin/users/' . $user->id . '/update', [
            'first_name' => 'updated',
            'last_name' => $user->last_name,
            'email' => $user->email,
        ])
            ->assertStatus(200);

        $this->assertEquals('updated', $user->fresh()->first_name);
    }

    public function test_admin_può_modificare_indirizzo_utente()
    {
        $admin = User::factory()->create(['is_admin' => true])->first();
        $this->actingAs($admin);
        
        $user = User::factory()->create();

        $this->patchJson('/api/admin/users/' . $user->id . '/update-address', [
            'country' => 'updated',
            'city' => 'updated',
            'province' => 'updated',
            'address' => 'updated',
            'zipcode' => '00012',
            'phone' => '333333',
        ])->assertStatus(200);

        $this->assertEquals('updated', $user->fresh()->address);
        $this->assertEquals('updated', $user->fresh()->city);
    }

    public function test_admin_può_cancellare_un_utente()
    {
        $admin = User::factory()->create(['is_admin' => true])->first();
        $this->actingAs($admin);

        $user = User::factory()->create();

        $this->deleteJson('/api/admin/users/' . $user->id . '/delete')
            ->assertStatus(200);

        $this->assertDatabaseMissing('users', $user->only('id'));
    }

    public function test_admin_può_vedere_gli_ordini_effettuati_dall_utente()
    {
        $admin = User::factory()->create(['is_admin' => true])->first();
        $this->actingAs($admin);

        $user = User::factory()->create();

        $order = Order::factory()->create([
            'user_id' => $user->id
        ]);

        $this->getJson('/api/admin/users/' . $user->id . '/orders')
            ->assertStatus(200)
            ->assertJson(function($json) {
                $json
                    ->has('data', 1)
                    ->has('links')
                    ->has('meta');
            });
    }
}
