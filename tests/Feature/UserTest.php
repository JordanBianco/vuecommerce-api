<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_utente_può_modificare_i_suoi_dati()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $this->patchJson('/api/user/personal-info', [
            'first_name' => 'user',
            'last_name' => $user->last_name,
            'email' => $user->email,
        ])->assertStatus(200);

        $this->assertEquals('user', $user->fresh()->first_name);
    }

    public function test_utente_può_modificare_il_suo_indirizzo()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $this->patchJson('/api/user/address', [
            'first_name' => 'user',
            'last_name' => $user->last_name,
            'email' => $user->email,
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
}
