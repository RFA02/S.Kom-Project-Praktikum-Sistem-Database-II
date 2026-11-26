<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_verifies_business_and_owner_is_notified(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $owner = User::factory()->create(['role' => 'business']);
        $biz = Business::create(['user_id' => $owner->id, 'business_name' => 'Toko Uji', 'business_type' => 'Cafe', 'verification_status' => 'pending']);

        $this->actingAs($admin)->get('/admin/dashboard')->assertOk()->assertSee('Verifikasi Mitra')->assertSee('Toko Uji');
        $this->put('/admin/businesses/' . $biz->id . '/verification', ['verification_status' => 'verified'])->assertRedirect();

        $this->assertSame('verified', $biz->fresh()->verification_status);
        $this->assertSame(1, $owner->unreadNotifications()->count());
    }

    public function test_non_admin_is_blocked(): void
    {
        $c = User::factory()->create(['role' => 'customer']);
        $this->actingAs($c)->get('/admin/dashboard')->assertStatus(403);
    }
}
