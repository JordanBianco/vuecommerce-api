<?php

namespace Tests\Feature\admin;

use App\Models\Coupon;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CouponTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_puo_creare_coupon()
    {
        $user = User::factory()->create(['is_admin' => true])->first();
        $this->actingAs($user);

        $this->postJson('/api/admin/coupons', $coupon = [
            'code' => 'Test',
            'description' => 'test',
            'discount' => 10
        ]);

        $this->assertDatabaseHas('coupons', $coupon);
    }

    public function test_admin_puo_vedere_lista_coupon()
    {
        $user = User::factory()->create(['is_admin' => true])->first();
        $this->actingAs($user);

        Coupon::factory()->create();

        $this->getJson('/api/admin/coupons')
            ->assertJson(function($json) {
                $json
                    ->has('data', 1)
                    ->has('links')
                    ->has('meta');
            });
    }

    public function test_admin_puo_vedere_un_singolo_coupon()
    {
        $user = User::factory()->create(['is_admin' => true])->first();
        $this->actingAs($user);

        $coupon = Coupon::factory()->create();

        $this->getJson('/api/admin/coupons/' . $coupon->id)
            ->assertJson(function($json) {
                $json
                    ->has('data');
            });
    }

    public function test_admin_puo_modificare_un_coupon()
    {
        $user = User::factory()->create(['is_admin' => true])->first();
        $this->actingAs($user);

        $coupon = Coupon::factory()->create();

        $this->patchJson('/api/admin/coupons/' . $coupon->id . '/update', [
            'code' => 'updated CODE',
            'description' => $coupon->description,
            'discount' => $coupon->discount,
        ])->assertStatus(200);

        $this->assertEquals('updated CODE', $coupon->fresh()->code);
    }

    public function test_admin_puo_eliminare_un_coupon()
    {
        $user = User::factory()->create(['is_admin' => true])->first();
        $this->actingAs($user);
        
        $coupon = Coupon::factory()->create();

        $this->deleteJson('/api/admin/coupons/' . $coupon->id . '/delete')
            ->assertStatus(200);

        $this->assertDatabaseMissing('coupons', $coupon->only('id'));
    }
}
