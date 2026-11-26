<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Memperketat skema untuk database baru MAUPUN database MySQL yang sudah ada.
     *
     * - surplus_listings.initial_quantity : jumlah awal listing (patokan stok), di-backfill dari data lama.
     * - unique (food_id, listing_date)    : mencegah listing ganda. Dilewati jika data lama sudah punya duplikat.
     * - unique (food_id, record_date)     : mencegah catatan produksi ganda. Dilewati jika data lama sudah duplikat.
     * - indeks listing_date & pickup_end_time untuk query ketersediaan.
     *
     * Jika unique dilewati karena duplikat, validasi di aplikasi tetap berlaku untuk data baru.
     * Rapikan duplikat lalu jalankan: php artisan migrate:refresh --path=database/migrations/2026_11_27_000001_harden_listing_and_production_schema.php
     */
    public function up(): void
    {
        if (Schema::hasTable('surplus_listings')) {
            if (! Schema::hasColumn('surplus_listings', 'initial_quantity')) {
                Schema::table('surplus_listings', function (Blueprint $table) {
                    $table->unsignedInteger('initial_quantity')->nullable();
                });
            }

            // Backfill: awal = sisa + yang sudah terpakai (pending, confirmed, completed, no_show).
            DB::table('surplus_listings')
                ->whereNull('initial_quantity')
                ->orderBy('id')
                ->chunkById(200, function ($listings) {
                    foreach ($listings as $listing) {
                        $consumed = (int) DB::table('reservations')
                            ->where('surplus_listing_id', $listing->id)
                            ->whereIn('reservation_status', ['pending', 'confirmed', 'completed', 'no_show'])
                            ->sum('portion_quantity');

                        DB::table('surplus_listings')
                            ->where('id', $listing->id)
                            ->update(['initial_quantity' => (int) $listing->available_quantity + $consumed]);
                    }
                });

            if (! Schema::hasIndex('surplus_listings', ['listing_date'])) {
                Schema::table('surplus_listings', fn (Blueprint $table) => $table->index('listing_date'));
            }

            if (! Schema::hasIndex('surplus_listings', ['pickup_end_time'])) {
                Schema::table('surplus_listings', fn (Blueprint $table) => $table->index('pickup_end_time'));
            }

            $hasDuplicateListings = DB::table('surplus_listings')
                ->select('food_id', 'listing_date')
                ->groupBy('food_id', 'listing_date')
                ->havingRaw('COUNT(*) > 1')
                ->exists();

            if (! $hasDuplicateListings && ! Schema::hasIndex('surplus_listings', 'surplus_listings_food_date_unique')) {
                Schema::table('surplus_listings', function (Blueprint $table) {
                    $table->unique(['food_id', 'listing_date'], 'surplus_listings_food_date_unique');
                });
            }
        }

        if (Schema::hasTable('production_records')) {
            $hasDuplicateRecords = DB::table('production_records')
                ->select('food_id', 'record_date')
                ->groupBy('food_id', 'record_date')
                ->havingRaw('COUNT(*) > 1')
                ->exists();

            if (! $hasDuplicateRecords && ! Schema::hasIndex('production_records', 'production_records_food_date_unique')) {
                Schema::table('production_records', function (Blueprint $table) {
                    $table->unique(['food_id', 'record_date'], 'production_records_food_date_unique');
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('production_records') && Schema::hasIndex('production_records', 'production_records_food_date_unique')) {
            Schema::table('production_records', fn (Blueprint $table) => $table->dropUnique('production_records_food_date_unique'));
        }

        if (Schema::hasTable('surplus_listings')) {
            if (Schema::hasIndex('surplus_listings', 'surplus_listings_food_date_unique')) {
                Schema::table('surplus_listings', fn (Blueprint $table) => $table->dropUnique('surplus_listings_food_date_unique'));
            }

            if (Schema::hasColumn('surplus_listings', 'initial_quantity')) {
                Schema::table('surplus_listings', fn (Blueprint $table) => $table->dropColumn('initial_quantity'));
            }
        }
    }
};
