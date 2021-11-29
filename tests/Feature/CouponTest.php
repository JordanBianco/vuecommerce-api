<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CouponTest extends TestCase
{
    use RefreshDatabase;

    public function test_il_coupon_è_obbligatorio()
    {
        $this->actingAs(User::factory()->create()->first());

        $this->getJson('/api/coupons/verify', [
            'coupon' => ''
        ])->assertJsonValidationErrors('coupon');
    }
}
