<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\Food;
use App\Models\Reservation;
use App\Models\SurplusListing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StabilityFixesTest extends TestCase
{
    use RefreshDatabase;

    private function makeBusiness(string $status = 'verified'): array
    {
        $owner = User::factory()->create(['role' => 'business']);
        $business = Business::create([
            'user_id' => $owner->id,
            'business_name' => 'Toko ' . $owner->id,
            'business_type' => 'Bakery',
            'city' => 'Bandung',
            'verification_status' => $status,
        ]);

        return [$owner, $business];
    }

    private function makeFood(Business $business, string $name = 'Roti Cokelat'): Food
    {
        return Food::create([
            'business_id' => $business->id,
            'food_name' => $name,
            'normal_price' => 20000,
            'category' => 'Bread & Pastry',
        ]);
    }

    private function makeListing(Food $food, array $overrides = []): SurplusListing
    {
        return SurplusListing::create(array_merge([
            'food_id' => $food->id,
            'listing_date' => today()->addDay()->toDateString(),
            'available_quantity' => 5,
            'initial_quantity' => 5,
            'discount_percentage' => 40,
            'discounted_price' => 12000,
            'pickup_start_time' => today()->addDay()->setTime(17, 0),
            'pickup_end_time' => today()->addDay()->setTime(20, 0),
            'listing_status' => 'active',
        ], $overrides));
    }

    private function makeReservation(User $customer, SurplusListing $listing, string $status, int $qty = 1): Reservation
    {
        return Reservation::create([
            'user_id' => $customer->id,
            'surplus_listing_id' => $listing->id,
            'reserved_at' => now()->subHours(5),
            'portion_quantity' => $qty,
            'price_at_reservation' => $listing->discounted_price,
            'reservation_status' => $status,
        ]);
    }

    private function listingPayload(Food $food, array $overrides = []): array
    {
        $day = today()->addDay();

        return array_merge([
            'food_id' => $food->id,
            'listing_date' => $day->toDateString(),
            'available_quantity' => 6,
            'discount_percentage' => 40,
            'pickup_start_time' => $day->copy()->setTime(17, 0)->format('Y-m-d\TH:i'),
            'pickup_end_time' => $day->copy()->setTime(20, 0)->format('Y-m-d\TH:i'),
        ], $overrides);
    }

    // ---------- 1. Siklus hidup stok & reservasi ----------

    public function test_sweeper_closes_stale_reservations_and_expires_listing(): void
    {
        [, $business] = $this->makeBusiness();
        $food = $this->makeFood($business);
        $customer = User::factory()->create(['role' => 'customer']);

        $listing = $this->makeListing($food, [
            'listing_date' => today()->toDateString(),
            'available_quantity' => 3,
            'initial_quantity' => 5,
            'pickup_start_time' => now()->subHours(3),
            'pickup_end_time' => now()->subHour(),
        ]);

        $pending = $this->makeReservation($customer, $listing, 'pending');
        $confirmed = $this->makeReservation($customer, $listing, 'confirmed');

        $this->artisan('whatsleft:sweep-stale')->assertSuccessful();

        $this->assertSame('cancelled', $pending->fresh()->reservation_status);
        $this->assertSame('no_show', $confirmed->fresh()->reservation_status);

        $listing->refresh();
        $this->assertSame('expired', $listing->listing_status);
        $this->assertSame(4, (int) $listing->available_quantity);
        $this->assertSame(2, $customer->notifications()->count());
    }

    public function test_sweeper_does_not_touch_open_listings(): void
    {
        [, $business] = $this->makeBusiness();
        $food = $this->makeFood($business);
        $customer = User::factory()->create(['role' => 'customer']);
        $listing = $this->makeListing($food);
        $reservation = $this->makeReservation($customer, $listing, 'pending');

        $this->artisan('whatsleft:sweep-stale')->assertSuccessful();

        $this->assertSame('pending', $reservation->fresh()->reservation_status);
        $this->assertSame('active', $listing->fresh()->listing_status);
    }

    public function test_no_show_within_pickup_window_restores_stock(): void
    {
        [$owner, $business] = $this->makeBusiness();
        $food = $this->makeFood($business);
        $customer = User::factory()->create(['role' => 'customer']);
        $listing = $this->makeListing($food, ['available_quantity' => 3, 'initial_quantity' => 5]);
        $reservation = $this->makeReservation($customer, $listing, 'confirmed', 2);

        $this->actingAs($owner)
            ->putJson('/business/reservations-management/' . $reservation->id . '/status', [
                'reservation_status' => 'no_show',
            ])
            ->assertOk();

        $this->assertSame(5, (int) $listing->fresh()->available_quantity);
    }

    public function test_editing_stock_keeps_initial_quantity_in_sync(): void
    {
        [$owner, $business] = $this->makeBusiness();
        $food = $this->makeFood($business);
        $customer = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($owner)
            ->postJson('/business/surplus-listings', $this->listingPayload($food))
            ->assertCreated();

        $listing = SurplusListing::findOrFail($response->json('data.id'));
        $this->assertSame(6, (int) $listing->initial_quantity);

        $this->makeReservation($customer, $listing, 'pending', 2);
        $listing->update(['available_quantity' => 4]);

        $this->actingAs($owner)
            ->putJson('/business/surplus-listings/' . $listing->id, ['available_quantity' => 10])
            ->assertOk();

        $listing->refresh();
        $this->assertSame(10, (int) $listing->available_quantity);
        $this->assertSame(12, (int) $listing->initial_quantity);
    }

    // ---------- 2. Aturan listing ----------

    public function test_duplicate_listing_for_same_food_and_date_is_rejected(): void
    {
        [$owner, $business] = $this->makeBusiness();
        $food = $this->makeFood($business);

        $this->actingAs($owner)->postJson('/business/surplus-listings', $this->listingPayload($food))->assertCreated();
        $this->actingAs($owner)->postJson('/business/surplus-listings', $this->listingPayload($food))->assertStatus(422);
    }

    public function test_pickup_must_be_on_listing_date(): void
    {
        [$owner, $business] = $this->makeBusiness();
        $food = $this->makeFood($business);
        $day = today()->addDay();

        $this->actingAs($owner)
            ->postJson('/business/surplus-listings', $this->listingPayload($food, [
                'pickup_end_time' => $day->copy()->addDay()->setTime(10, 0)->format('Y-m-d\TH:i'),
            ]))
            ->assertStatus(422);
    }

    public function test_discount_must_be_between_1_and_90(): void
    {
        [$owner, $business] = $this->makeBusiness();
        $food = $this->makeFood($business);

        $this->actingAs($owner)
            ->postJson('/business/surplus-listings', $this->listingPayload($food, ['discount_percentage' => 0]))
            ->assertStatus(422);

        $this->actingAs($owner)
            ->postJson('/business/surplus-listings', $this->listingPayload($food, ['discount_percentage' => 100]))
            ->assertStatus(422);
    }

    // ---------- 3. Produksi ----------

    public function test_duplicate_and_future_production_records_are_rejected(): void
    {
        [$owner, $business] = $this->makeBusiness();
        $food = $this->makeFood($business);

        $payload = [
            'food_id' => $food->id,
            'record_date' => today()->toDateString(),
            'production_quantity' => 10,
            'regular_sold_quantity' => 6,
            'surplus_quantity' => 3,
            'waste_quantity' => 1,
        ];

        $this->actingAs($owner)->postJson('/business/production-records', $payload)->assertCreated();
        $this->actingAs($owner)->postJson('/business/production-records', $payload)->assertStatus(422);

        $this->actingAs($owner)
            ->postJson('/business/production-records', array_merge($payload, [
                'record_date' => today()->addDay()->toDateString(),
            ]))
            ->assertStatus(422);
    }

    // ---------- 5. Verifikasi bisnis ----------

    public function test_pending_business_is_hidden_when_verification_is_required(): void
    {
        [, $business] = $this->makeBusiness('pending');
        $food = $this->makeFood($business, 'Kue Pending Unik');
        $this->makeListing($food);

        config(['whatsleft.require_verified_business' => false]);
        $this->get('/explore')->assertOk()->assertSee('Kue Pending Unik');

        config(['whatsleft.require_verified_business' => true]);
        $this->get('/explore')->assertOk()->assertDontSee('Kue Pending Unik');

        $customer = User::factory()->create(['role' => 'customer']);
        $listing = SurplusListing::first();
        $this->actingAs($customer)
            ->postJson('/consumer/reservations', ['surplus_listing_id' => $listing->id, 'portion_quantity' => 1])
            ->assertStatus(404);
    }

    public function test_rejecting_a_business_closes_its_active_listings(): void
    {
        [, $business] = $this->makeBusiness('verified');
        $food = $this->makeFood($business);
        $listing = $this->makeListing($food);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->put('/admin/businesses/' . $business->id . '/verification', ['verification_status' => 'rejected'])
            ->assertRedirect();

        $this->assertSame('closed', $listing->fresh()->listing_status);
    }

    // ---------- Arsip makanan ----------

    public function test_food_without_history_is_deleted_permanently(): void
    {
        [$owner, $business] = $this->makeBusiness();
        $food = $this->makeFood($business);

        $this->actingAs($owner)->deleteJson('/business/food-management/' . $food->id)->assertOk();

        $this->assertDatabaseMissing('foods', ['id' => $food->id]);
    }

    public function test_food_with_finished_history_is_archived_and_can_be_restored(): void
    {
        [$owner, $business] = $this->makeBusiness();
        $food = $this->makeFood($business);
        $customer = User::factory()->create(['role' => 'customer']);
        $listing = $this->makeListing($food);
        $reservation = $this->makeReservation($customer, $listing, 'completed');

        $this->actingAs($owner)
            ->deleteJson('/business/food-management/' . $food->id)
            ->assertOk()
            ->assertJsonPath('archived', true);

        $food->refresh();
        $this->assertNotNull($food->archived_at);
        $this->assertSame('closed', $listing->fresh()->listing_status);
        $this->assertNotNull(Reservation::find($reservation->id));

        // riwayat reservasi bisnis tetap bisa dibuka
        $this->actingAs($owner)->get('/business/reservations-management')->assertOk();

        $this->actingAs($owner)
            ->post('/business/food-management/' . $food->id . '/restore')
            ->assertRedirect();

        $this->assertNull($food->fresh()->archived_at);
    }

    public function test_food_with_running_reservation_cannot_be_archived(): void
    {
        [$owner, $business] = $this->makeBusiness();
        $food = $this->makeFood($business);
        $customer = User::factory()->create(['role' => 'customer']);
        $listing = $this->makeListing($food);
        $this->makeReservation($customer, $listing, 'pending');

        $this->actingAs($owner)->deleteJson('/business/food-management/' . $food->id)->assertStatus(409);

        $this->assertNull($food->fresh()->archived_at);
    }

    public function test_duplicate_food_name_is_rejected(): void
    {
        [$owner, $business] = $this->makeBusiness();
        $this->makeFood($business, 'Donat Gula');

        $this->actingAs($owner)
            ->postJson('/business/food-management', [
                'food_name' => 'Donat Gula',
                'normal_price' => 10000,
                'category' => 'Snack',
            ])
            ->assertStatus(422);
    }

    // ---------- API ----------

    public function test_api_login_issues_expiring_token_and_enforces_roles(): void
    {
        User::factory()->create(['email' => 'cust@example.com', 'role' => 'customer']);
        User::factory()->create(['email' => 'adm@example.com', 'role' => 'admin']);

        $login = $this->postJson('/api/login', ['email' => 'cust@example.com', 'password' => 'password'])
            ->assertOk();

        $token = $login->json('token');
        $this->assertNotEmpty($token);

        $this->withToken($token)->getJson('/api/foods')->assertStatus(403);
        $this->withToken($token)->getJson('/api/reservations')->assertOk();

        $this->postJson('/api/login', ['email' => 'adm@example.com', 'password' => 'password'])
            ->assertStatus(403);

        $this->postJson('/api/login', ['email' => 'nobody@example.com', 'password' => 'password'])
            ->assertStatus(401);
    }
}
