<?php

namespace Tests\Feature;

use App\Models\Activity;
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

    public function test_utente_può_cancellare_il_suo_account()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $this->deleteJson('/api/user/delete')->assertStatus(200);

        $this->assertDatabaseMissing('users', $user->only('id'));
    }

    public function test_se_utente_cancella_il_suo_account_la_sua_attività_viene_cancellata()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $this->patchJson('/api/password/change', [
            'oldPassword' => 'password',
            'newPassword_confirmation' => 'password10@',
            'newPassword' => 'password10@',
        ]);

        $this->assertEquals(1, $user->fresh()->activities->count());

        $this->deleteJson('/api/user/delete')->assertStatus(200);

        $this->assertEquals(0, $user->activities->count());
    }
}
