<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ChangePasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_vecchia_password_è_obbligatoria()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $this->patchJson('/api/password/change', [
            'oldPassword' => '',
        ])->assertJsonValidationErrors('oldPassword');
    }

    public function test_la_nuova_password_è_obbligatoria()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $this->patchJson('/api/password/change', [
            'oldPassword' => 'password',
            'newPassword' => '',
        ])->assertJsonValidationErrors('newPassword');
    }

    public function test_la_vecchia_password_deve_essere_corretta()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $this->patchJson('/api/password/change', [
            'oldPassword' => 'passwo',
            'newPassword_confirmation' => 'password10@',
            'newPassword' => 'password10@',
        ])->assertJsonValidationErrors('oldPassword');
    }

    public function test_utente_puo_cambiare_la_propria_password()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $this->patchJson('/api/password/change', [
            'oldPassword' => 'password',
            'newPassword_confirmation' => 'password10@',
            'newPassword' => 'password10@',
        ])->assertStatus(200);

        $this->assertTrue(Hash::check('password10@', $user->password));
    }

}
