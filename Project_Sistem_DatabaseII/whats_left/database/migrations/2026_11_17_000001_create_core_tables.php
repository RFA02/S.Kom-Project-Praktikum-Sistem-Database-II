<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel inti What's Left. Tiap tabel dilindungi Schema::hasTable()
     * sehingga tidak menimpa tabel yang sudah ada di MySQL.
     */
    public function up(): void
    {
        if (! Schema::hasTable('businesses')) {
            Schema::create('businesses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
                $table->string('business_name', 120);
                $table->string('business_type', 40)->nullable();
                $table->string('street', 180)->nullable();
                $table->string('city', 80)->nullable();
                $table->string('province', 80)->nullable();
                $table->string('postal_code', 10)->nullable();
                $table->string('phone', 20)->nullable();
                $table->string('verification_status', 20)->default('pending');
            });
        }

        if (! Schema::hasTable('foods')) {
            Schema::create('foods', function (Blueprint $table) {
                $table->id();
                $table->foreignId('business_id')->constrained()->cascadeOnDelete();
                $table->string('food_name', 120);
                $table->text('description')->nullable();
                $table->decimal('normal_price', 12, 2);
                $table->string('category', 40)->default('Other');
            });
        }

        if (! Schema::hasTable('production_records')) {
            Schema::create('production_records', function (Blueprint $table) {
                $table->id();
                $table->foreignId('food_id')->constrained('foods')->restrictOnDelete();
                $table->date('record_date');
                $table->unsignedInteger('production_quantity');
                $table->unsignedInteger('regular_sold_quantity')->default(0);
                $table->unsignedInteger('surplus_quantity')->default(0);
                $table->unsignedInteger('waste_quantity')->default(0);
            });
        }

        if (! Schema::hasTable('surplus_listings')) {
            Schema::create('surplus_listings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('food_id')->constrained('foods')->restrictOnDelete();
                $table->date('listing_date');
                $table->unsignedInteger('available_quantity');
                $table->decimal('discount_percentage', 5, 2);
                $table->decimal('discounted_price', 12, 2);
                $table->dateTime('pickup_start_time');
                $table->dateTime('pickup_end_time');
                $table->string('listing_status', 20)->default('active')->index();
            });
        }

        if (! Schema::hasTable('reservations')) {
            Schema::create('reservations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->restrictOnDelete();
                $table->foreignId('surplus_listing_id')->constrained('surplus_listings')->restrictOnDelete();
                $table->dateTime('reserved_at');
                $table->unsignedInteger('portion_quantity');
                $table->decimal('price_at_reservation', 12, 2);
                $table->string('reservation_status', 20)->default('pending')->index();
            });
        }
    }

    public function down(): void
    {
        // Sengaja kosong: up() hanya membuat tabel yang belum ada, dan tabel ini bisa jadi
        // adalah tabel LIVE berisi data. Rollback tidak boleh menghapusnya.
    }
};
