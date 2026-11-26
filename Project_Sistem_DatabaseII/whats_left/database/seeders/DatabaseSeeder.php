<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Food;
use App\Models\ProductionRecord;
use App\Models\Reservation;
use App\Models\SurplusListing;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed akun dan data demo. Aman dijalankan berulang (idempotent).
     * Akun admin hanya dibuat jika ADMIN_EMAIL dan ADMIN_PASSWORD diisi di .env.
     */
    public function run(): void
    {
        $customer = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'first_name' => 'Demo',
                'last_name' => 'Customer',
                'password' => 'password',
                'role' => 'customer',
                'phone' => '081234567890',
            ]
        );

        $businessOwner = User::firstOrCreate(
            ['email' => 'business@example.com'],
            [
                'first_name' => 'Andi',
                'last_name' => 'Pratama',
                'password' => 'password',
                'role' => 'business',
                'phone' => '081298765432',
            ]
        );

        $business = Business::firstOrCreate(
            ['user_id' => $businessOwner->id],
            [
                'business_name' => 'Sunny Bakery Demo',
                'business_type' => 'Bakery',
                'street' => 'Jl. Merdeka No. 10',
                'city' => 'Bandung',
                'province' => 'Jawa Barat',
                'postal_code' => '40111',
                'phone' => '081298765432',
                'verification_status' => 'verified',
            ]
        );

        $croissant = Food::firstOrCreate(
            ['business_id' => $business->id, 'food_name' => 'Croissant Butter Premium'],
            [
                'description' => 'Croissant mentega renyah panggang hari ini.',
                'normal_price' => 20000,
                'category' => 'Bread & Pastry',
            ]
        );

        $bento = Food::firstOrCreate(
            ['business_id' => $business->id, 'food_name' => 'Bento Chicken Teriyaki'],
            [
                'description' => 'Nasi hangat dengan ayam teriyaki dan salad segar.',
                'normal_price' => 35000,
                'category' => 'Main Course',
            ]
        );

        $tomorrow = today()->addDay();

        $listing = SurplusListing::firstOrCreate(
            [
                'food_id' => $croissant->id,
                'listing_date' => $tomorrow->toDateString(),
            ],
            [
                'available_quantity' => 8,
                'initial_quantity' => 10, // 8 tersisa + 2 porsi yang dipegang reservasi demo di bawah
                'discount_percentage' => 40,
                'discounted_price' => (int) round($croissant->normal_price * 0.60),
                'pickup_start_time' => $tomorrow->copy()->setTime(17, 0),
                'pickup_end_time' => $tomorrow->copy()->setTime(20, 0),
                'listing_status' => 'active',
            ]
        );

        SurplusListing::firstOrCreate(
            [
                'food_id' => $bento->id,
                'listing_date' => $tomorrow->toDateString(),
            ],
            [
                'available_quantity' => 5,
                'initial_quantity' => 5,
                'discount_percentage' => 35,
                'discounted_price' => (int) round($bento->normal_price * 0.65),
                'pickup_start_time' => $tomorrow->copy()->setTime(18, 0),
                'pickup_end_time' => $tomorrow->copy()->setTime(21, 0),
                'listing_status' => 'active',
            ]
        );

        ProductionRecord::firstOrCreate(
            [
                'food_id' => $croissant->id,
                'record_date' => today()->toDateString(),
            ],
            [
                'production_quantity' => 30,
                'regular_sold_quantity' => 20,
                'surplus_quantity' => 8,
                'waste_quantity' => 2,
            ]
        );

        if (! Reservation::where('user_id', $customer->id)->where('surplus_listing_id', $listing->id)->exists()) {
            Reservation::create([
                'user_id' => $customer->id,
                'surplus_listing_id' => $listing->id,
                'reserved_at' => now(),
                'portion_quantity' => 2,
                'price_at_reservation' => $listing->discounted_price,
                'reservation_status' => 'pending',
            ]);
        }

        $adminEmail = env('ADMIN_EMAIL');
        $adminPassword = env('ADMIN_PASSWORD');

        if ($adminEmail && $adminPassword) {
            User::firstOrCreate(
                ['email' => $adminEmail],
                ['first_name' => 'Admin', 'last_name' => '', 'password' => $adminPassword, 'role' => 'admin']
            );
        }
    }
}
