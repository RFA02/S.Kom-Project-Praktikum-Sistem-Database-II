<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\Food;
use App\Models\SurplusListing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class CrucialFixesV10Test extends TestCase
{
    use RefreshDatabase;

    private function makeBusinessWithFood(): array
    {
        $owner = User::factory()->create(['role' => 'business']);
        $business = Business::create([
            'user_id' => $owner->id, 'business_name' => 'Toko V10', 'business_type' => 'Bakery',
            'city' => 'Bandung', 'verification_status' => 'verified',
        ]);
        $food = Food::create([
            'business_id' => $business->id, 'food_name' => 'Roti V10',
            'normal_price' => 20000, 'category' => 'Bread & Pastry',
        ]);

        return [$owner, $food];
    }

    private function makeListing(Food $food, array $overrides = []): SurplusListing
    {
        return SurplusListing::create(array_merge([
            'food_id' => $food->id,
            'listing_date' => today()->addDay()->toDateString(),
            'available_quantity' => 20,
            'initial_quantity' => 20,
            'discount_percentage' => 40,
            'discounted_price' => 12000,
            'pickup_start_time' => today()->addDay()->setTime(17, 0),
            'pickup_end_time' => today()->addDay()->setTime(20, 0),
            'listing_status' => 'active',
        ], $overrides));
    }

    public function test_production_allows_partial_allocation_but_rejects_over_allocation(): void
    {
        [$owner, $food] = $this->makeBusinessWithFood();

        $base = ['food_id' => $food->id, 'record_date' => today()->toDateString(), 'production_quantity' => 10];

        $this->actingAs($owner)->postJson('/business/production-records', $base + [
            'regular_sold_quantity' => 5, 'surplus_quantity' => 2, 'waste_quantity' => 1,
        ])->assertCreated();

        $this->actingAs($owner)->postJson('/business/production-records', array_merge($base, [
            'record_date' => today()->subDay()->toDateString(),
            'regular_sold_quantity' => 9, 'surplus_quantity' => 2, 'waste_quantity' => 0,
        ]))->assertStatus(422);
    }

    public function test_closed_listing_does_not_block_a_new_listing_for_same_food_and_date(): void
    {
        [$owner, $food] = $this->makeBusinessWithFood();
        $this->makeListing($food, ['listing_status' => 'closed']);

        $day = today()->addDay();

        $this->actingAs($owner)->postJson('/business/surplus-listings', [
            'food_id' => $food->id,
            'listing_date' => $day->toDateString(),
            'available_quantity' => 4,
            'discount_percentage' => 30,
            'pickup_start_time' => $day->copy()->setTime(9, 0)->format('Y-m-d\TH:i'),
            'pickup_end_time' => $day->copy()->setTime(12, 0)->format('Y-m-d\TH:i'),
        ])->assertCreated();
    }

    public function test_expired_listing_cannot_be_edited(): void
    {
        [$owner, $food] = $this->makeBusinessWithFood();
        $listing = $this->makeListing($food, ['listing_status' => 'expired']);

        $this->actingAs($owner)
            ->putJson('/business/surplus-listings/' . $listing->id, ['available_quantity' => 5])
            ->assertStatus(422);
    }

    public function test_reservation_is_limited_to_ten_portions_and_unknown_listing_is_404(): void
    {
        [, $food] = $this->makeBusinessWithFood();
        $listing = $this->makeListing($food);
        $customer = User::factory()->create(['role' => 'customer']);

        $this->actingAs($customer)->postJson('/consumer/reservations', [
            'surplus_listing_id' => $listing->id, 'portion_quantity' => 11,
        ])->assertStatus(422);

        $this->actingAs($customer)->postJson('/consumer/reservations', [
            'surplus_listing_id' => 999999, 'portion_quantity' => 1,
        ])->assertStatus(404);

        $this->assertSame(20, (int) $listing->fresh()->available_quantity);
    }

    public function test_password_reset_revokes_api_tokens(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $user->createToken('whats-left-api');
        $this->assertSame(1, $user->tokens()->count());

        $token = Password::broker()->createToken($user);

        $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'passwordbaru123',
            'password_confirmation' => 'passwordbaru123',
        ])->assertRedirect(route('login'));

        $this->assertSame(0, $user->tokens()->count());
    }
}
