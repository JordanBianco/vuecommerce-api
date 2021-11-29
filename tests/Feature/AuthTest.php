<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_utente_si_può_registrare()
    {
        $user = [
            'first_name' => 'user',
            'last_name' => 'user',
            'email' => 'user@user.com',
            'password' => 'password1!',
        ];

        $this->postJson('/api/register', $user)->assertStatus(201);

        $this->assertEquals(1, User::all()->count());
    }

    public function test_la_password_deve_essere_lunga_almeno_8_caratteri()
    {
        $user = [
            'first_name' => 'user',
            'last_name' => 'user',
            'email' => 'user@user.com',
            'password' => 'pass1!',
        ];

        $this->postJson('/api/register', $user)
            ->assertJsonValidationErrors('password');
    }

    public function test_la_password_deve_essere_contenere_almeno_un_numero()
    {
        $user = [
            'first_name' => 'user',
            'last_name' => 'user',
            'email' => 'user@user.com',
            'password' => 'password!',
        ];

        $this->postJson('/api/register', $user)
            ->assertJsonValidationErrors('password');
    }

    public function test_la_password_deve_essere_contenere_almeno_un_carattere_speciale()
    {
        $user = [
            'first_name' => 'user',
            'last_name' => 'user',
            'email' => 'user@user.com',
            'password' => 'password10',
        ];

        $this->postJson('/api/register', $user)
            ->assertJsonValidationErrors('password');
    }

    public function test_un_utente_può_fare_il_login()
    {
        $user = [
            'first_name' => 'user',
            'last_name' => 'user',
            'email' => 'user@user.com',
            'password' => 'password1!',
        ];

        $this->postJson('/api/register', $user)->assertStatus(201);

        $this->postJson('/api/login', [
            'email' => 'user@user.com',
            'password' => 'password1!'
        ])
        ->assertStatus(200);
    }
}
