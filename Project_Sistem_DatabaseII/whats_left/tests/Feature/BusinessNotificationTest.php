<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\User;
use App\Notifications\ReservationUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusinessNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_business_sees_and_marks_notifications_read(): void
    {
        $u = User::factory()->create(['role' => 'business']);
        Business::create(['user_id' => $u->id, 'business_name' => 'Toko Uji', 'business_type' => 'Cafe', 'verification_status' => 'verified']);
        $u->notify(new ReservationUpdated('Reservasi baru', 'Roti 2 porsi menunggu konfirmasi.', 1));

        $this->actingAs($u)->get('/business/dashboard')->assertOk()->assertSee('1 belum dibaca');
        $this->get('/business/notifications')->assertOk()->assertSee('Roti 2 porsi');
        $this->assertSame(0, $u->fresh()->unreadNotifications()->count());
    }

    public function test_customer_cannot_open_business_notifications(): void
    {
        $c = User::factory()->create(['role' => 'customer']);
        $this->actingAs($c)->get('/business/notifications')->assertStatus(403);
    }
}
