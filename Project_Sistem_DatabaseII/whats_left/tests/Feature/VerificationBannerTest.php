<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerificationBannerTest extends TestCase
{
    use RefreshDatabase;

    private function bizPage(string $status)
    {
        $u = User::factory()->create(['role' => 'business']);
        Business::create(['user_id' => $u->id, 'business_name' => 'Toko Uji', 'business_type' => 'Cafe', 'verification_status' => $status]);

        return $this->actingAs($u)->get('/business/dashboard');
    }

    public function test_pending_business_sees_banner(): void
    {
        $this->bizPage('pending')->assertOk()->assertSee('menunggu verifikasi admin');
    }

    public function test_verified_business_has_no_banner(): void
    {
        $this->bizPage('verified')->assertOk()->assertDontSee('menunggu verifikasi admin');
    }
}
