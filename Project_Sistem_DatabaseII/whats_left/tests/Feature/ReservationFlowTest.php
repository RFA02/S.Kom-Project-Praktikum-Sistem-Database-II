<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\Food;
use App\Models\SurplusListing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationFlowTest extends TestCase
{
    use RefreshDatabase;

    private function makeListing(int $qty = 3): array
    {
        $owner = User::factory()->create(['role' => 'business']);
        $business = Business::create([
            'user_id' => $owner->id, 'business_name' => 'Toko Uji', 'business_type' => 'Bakery',
            'city' => 'Bandung', 'verification_status' => 'verified',
        ]);
        $food = Food::create(['business_id' => $business->id, 'food_name' => 'Roti', 'normal_price' => 20000, 'category' => 'Bread & Pastry']);
        $listing = SurplusListing::create([
            'food_id' => $food->id, 'listing_date' => today()->addDay()->toDateString(),
            'available_quantity' => $qty, 'discount_percentage' => 50, 'discounted_price' => 10000,
            'pickup_start_time' => today()->addDay()->setTime(17, 0), 'pickup_end_time' => today()->addDay()->setTime(20, 0),
            'listing_status' => 'active',
        ]);

        return [$owner, $listing];
    }

    public function test_reserve_reduces_stock_and_sold_out_then_cancel_restores(): void
    {
        [, $listing] = $this->makeListing(3);
        $customer = User::factory()->create(['role' => 'customer']);

        $res = $this->actingAs($customer)->postJson('/consumer/reservations', [
            'surplus_listing_id' => $listing->id, 'portion_quantity' => 3,
        ])->assertCreated();

        $listing->refresh();
        $this->assertSame(0, (int) $listing->available_quantity);
        $this->assertSame('sold_out', $listing->listing_status);

        $this->actingAs($customer)->putJson('/consumer/reservations/' . $res->json('data.id') . '/cancel', [
            'reservation_status' => 'cancelled',
        ])->assertOk();

        $listing->refresh();
        $this->assertSame(3, (int) $listing->available_quantity);
        $this->assertSame('active', $listing->listing_status);
    }

    public function test_cannot_reserve_more_than_stock(): void
    {
        [, $listing] = $this->makeListing(2);
        $customer = User::factory()->create(['role' => 'customer']);

        $this->actingAs($customer)->postJson('/consumer/reservations', [
            'surplus_listing_id' => $listing->id, 'portion_quantity' => 5,
        ])->assertStatus(422);

        $this->assertSame(2, (int) $listing->fresh()->available_quantity);
    }

    public function test_business_cannot_reserve_and_guest_is_blocked(): void
    {
        [$owner, $listing] = $this->makeListing();

        $this->actingAs($owner)->postJson('/consumer/reservations', [
            'surplus_listing_id' => $listing->id, 'portion_quantity' => 1,
        ])->assertStatus(403);

        auth()->logout();
        $this->postJson('/consumer/reservations', ['surplus_listing_id' => $listing->id, 'portion_quantity' => 1])
            ->assertStatus(401);
    }

    public function test_other_business_cannot_change_reservation(): void
    {
        [, $listing] = $this->makeListing();
        $customer = User::factory()->create(['role' => 'customer']);
        $id = $this->actingAs($customer)->postJson('/consumer/reservations', [
            'surplus_listing_id' => $listing->id, 'portion_quantity' => 1,
        ])->json('data.id');

        $other = User::factory()->create(['role' => 'business']);
        Business::create(['user_id' => $other->id, 'business_name' => 'Lain', 'business_type' => 'Cafe', 'verification_status' => 'verified']);

        $this->actingAs($other)->putJson('/business/reservations-management/' . $id . '/status', ['reservation_status' => 'confirmed'])
            ->assertStatus(404);
    }
}
