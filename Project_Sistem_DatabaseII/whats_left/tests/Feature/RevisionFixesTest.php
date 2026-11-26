<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\Food;
use App\Models\Reservation;
use App\Models\SurplusListing;
use App\Models\User;
use App\Notifications\ReservationUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RevisionFixesTest extends TestCase
{
    use RefreshDatabase;

    public function test_consumer_can_update_profile_with_empty_last_name(): void
    {
        $customer = User::factory()->create([
            'first_name' => 'Budi',
            'last_name' => 'Santoso',
            'role' => 'customer',
        ]);

        $this->actingAs($customer)
            ->put('/consumer/profile', [
                'first_name' => 'Budi',
                'last_name' => '',
                'phone' => '08123456789',
            ])
            ->assertRedirect();

        $this->assertSame('', $customer->fresh()->last_name);
        $this->assertSame('08123456789', $customer->fresh()->phone);
    }

    public function test_rejected_business_listings_are_hidden_and_cannot_be_reserved(): void
    {
        $owner = User::factory()->create(['role' => 'business']);
        $business = Business::create([
            'user_id' => $owner->id,
            'business_name' => 'Toko Ditolak',
            'business_type' => 'Cafe',
            'verification_status' => 'rejected',
        ]);
        $food = Food::create([
            'business_id' => $business->id,
            'food_name' => 'Kopi Susu Ditolak',
            'normal_price' => 25000,
            'category' => 'Beverage',
        ]);
        $listing = SurplusListing::create([
            'food_id' => $food->id,
            'listing_date' => today()->addDay()->toDateString(),
            'available_quantity' => 5,
            'discount_percentage' => 40,
            'discounted_price' => 15000,
            'pickup_start_time' => today()->addDay()->setTime(16, 0),
            'pickup_end_time' => today()->addDay()->setTime(20, 0),
            'listing_status' => 'active',
        ]);

        $this->get('/explore')->assertOk()->assertDontSee('Kopi Susu Ditolak');
        $this->get('/consumer/foods/' . $listing->id)->assertStatus(404);

        $customer = User::factory()->create(['role' => 'customer']);
        $this->actingAs($customer)->postJson('/consumer/reservations', [
            'surplus_listing_id' => $listing->id,
            'portion_quantity' => 1,
        ])->assertStatus(404);
    }

    public function test_cancelling_reservation_on_expired_sold_out_listing_marks_listing_expired(): void
    {
        $owner = User::factory()->create(['role' => 'business']);
        $business = Business::create([
            'user_id' => $owner->id,
            'business_name' => 'Toko Expired',
            'business_type' => 'Bakery',
            'verification_status' => 'verified',
        ]);
        $food = Food::create([
            'business_id' => $business->id,
            'food_name' => 'Roti Lama',
            'normal_price' => 20000,
            'category' => 'Bread & Pastry',
        ]);
        $listing = SurplusListing::create([
            'food_id' => $food->id,
            'listing_date' => today()->subDay()->toDateString(),
            'available_quantity' => 0,
            'discount_percentage' => 50,
            'discounted_price' => 10000,
            'pickup_start_time' => today()->subDay()->setTime(15, 0),
            'pickup_end_time' => today()->subDay()->setTime(18, 0),
            'listing_status' => 'sold_out',
        ]);

        $customer = User::factory()->create(['role' => 'customer']);
        $reservation = Reservation::create([
            'user_id' => $customer->id,
            'surplus_listing_id' => $listing->id,
            'reserved_at' => now()->subDay(),
            'portion_quantity' => 2,
            'price_at_reservation' => 10000,
            'reservation_status' => 'pending',
        ]);

        $this->actingAs($customer)
            ->putJson('/consumer/reservations/' . $reservation->id . '/cancel', [
                'reservation_status' => 'cancelled',
            ])
            ->assertOk();

        $listing->refresh();
        $this->assertSame(2, (int) $listing->available_quantity);
        $this->assertSame('expired', $listing->listing_status);
    }

    public function test_cannot_create_listing_with_past_pickup_end_time(): void
    {
        $owner = User::factory()->create(['role' => 'business']);
        $business = Business::create([
            'user_id' => $owner->id,
            'business_name' => 'Toko Validasi',
            'business_type' => 'Bakery',
            'verification_status' => 'verified',
        ]);
        $food = Food::create([
            'business_id' => $business->id,
            'food_name' => 'Donat',
            'normal_price' => 15000,
            'category' => 'Snack',
        ]);

        $this->actingAs($owner)
            ->postJson('/business/surplus-listings', [
                'food_id' => $food->id,
                'listing_date' => today()->toDateString(),
                'available_quantity' => 5,
                'discount_percentage' => 30,
                'pickup_start_time' => now()->subHours(2)->format('Y-m-d\TH:i'),
                'pickup_end_time' => now()->subHour()->format('Y-m-d\TH:i'),
            ])
            ->assertStatus(422);
    }

    public function test_production_record_requires_positive_quantity(): void
    {
        $owner = User::factory()->create(['role' => 'business']);
        $business = Business::create([
            'user_id' => $owner->id,
            'business_name' => 'Toko Produksi',
            'business_type' => 'Restaurant',
            'verification_status' => 'verified',
        ]);
        $food = Food::create([
            'business_id' => $business->id,
            'food_name' => 'Nasi Goreng',
            'normal_price' => 30000,
            'category' => 'Main Course',
        ]);

        $this->actingAs($owner)
            ->postJson('/business/production-records', [
                'food_id' => $food->id,
                'record_date' => today()->toDateString(),
                'production_quantity' => 0,
                'regular_sold_quantity' => 0,
                'surplus_quantity' => 0,
                'waste_quantity' => 0,
            ])
            ->assertStatus(422);
    }

    public function test_consumer_navbar_shows_notification_bell_and_admin_sees_admin_link(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $customer->notify(new ReservationUpdated('Status diperbarui', 'Pesananmu dikonfirmasi.', 1));

        $this->actingAs($customer)
            ->get('/explore')
            ->assertOk()
            ->assertSee('1 belum dibaca')
            ->assertSee(route('consumer.notifications'));

        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)
            ->get('/')
            ->assertOk()
            ->assertSee('Dashboard Admin');
    }

    public function test_business_pages_and_analytics_render_cleanly(): void
    {
        $owner = User::factory()->create(['role' => 'business']);
        Business::create([
            'user_id' => $owner->id,
            'business_name' => 'Toko Analitik',
            'business_type' => 'Bakery',
            'verification_status' => 'verified',
        ]);

        $this->actingAs($owner)->get('/business/dashboard')->assertOk();
        $this->actingAs($owner)->get('/business/food-management')->assertOk();
        $this->actingAs($owner)->get('/business/surplus-listings')->assertOk();
        $this->actingAs($owner)->get('/business/production-records')->assertOk();
        $this->actingAs($owner)->get('/business/reservations-management')->assertOk();
        $this->actingAs($owner)->get('/business/analytics?period=month_current')->assertOk();
    }

    public function test_admin_dashboard_search_filters_businesses(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $owner1 = User::factory()->create(['role' => 'business']);
        $owner2 = User::factory()->create(['role' => 'business']);

        Business::create(['user_id' => $owner1->id, 'business_name' => 'Roti Bandung', 'city' => 'Bandung', 'verification_status' => 'pending']);
        Business::create(['user_id' => $owner2->id, 'business_name' => 'Kopi Jakarta', 'city' => 'Jakarta', 'verification_status' => 'verified']);

        $this->actingAs($admin)
            ->get('/admin/dashboard?q=Bandung')
            ->assertOk()
            ->assertSee('Roti Bandung')
            ->assertDontSee('Kopi Jakarta');
    }
}
