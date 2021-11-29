<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_utente_ha_molti_record_nell_activity_feed()
    {
        $user = User::factory()->create()->first();
        
        $this->assertInstanceOf(Collection::class, $user->activities);
    }

    public function test_scrivere_una_recensione_genera_una_attività()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $product = Product::factory()->create();

        $this->postJson('api/' . $product->id . '/review', [
            'user_id' => $user->id,
            'rating' => '4',
        ]);

        $this->assertEquals(1, $user->activities->count());
        $this->assertEquals('App\Models\Review', $user->activities()->first()->subject_type);
    }

    public function test_effettuare_un_acquisto_genera_una_attività()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);

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
                'qauntity' => 1
            ]
        ]);

        $this->assertEquals(1, $user->activities->count());
        $this->assertEquals('App\Models\Order', $user->activities()->first()->subject_type);
    }

    public function test_modificare_il_proprio_profilo_genera_una_attività()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $this->patchJson('/api/user/personal-info', [
            'first_name' => 'user',
            'last_name' => $user->last_name,
            'email' => $user->email,
        ])->assertStatus(200);

        $this->assertEquals(1, $user->activities->count());
        $this->assertEquals('App\Models\User', $user->activities()->first()->subject_type);
    }

    public function test_modificare_il_proprio_indirizzo_genera_una_attività()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $this->patchJson('/api/user/address', [
            'country' => 'test country',
            'city' => 'test city',
            'province' => 'test province',
            'address' => 'test address',
            'zipcode' => '000012',
            'phone' => '3333333',
        ])->assertStatus(200);

        $this->assertEquals(1, $user->activities->count());
        $this->assertEquals('App\Models\User', $user->activities()->first()->subject_type);
    }

    public function test_modificare_la_propria_password_genera_una_attività()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $this->patchJson('/api/password/change', [
            'oldPassword' => 'password',
            'newPassword_confirmation' => 'password10@',
            'newPassword' => 'password10@',
        ]);

        $this->assertEquals(1, $user->activities->count());
        $this->assertEquals('App\Models\User', $user->activities()->first()->subject_type);
    }
}
