<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Unique (food_id, listing_date) diganti indeks biasa.
     * URUTAN PENTING: indeks pengganti dibuat DULU, baru unique di-drop.
     * Di MySQL, foreign key food_id membutuhkan minimal satu indeks yang diawali food_id,
     * sehingga unique tidak bisa dihapus sebelum ada pengganti (error 1553).
     */
    public function up(): void
    {
        if (! Schema::hasTable('surplus_listings')) {
            return;
        }

        // 1. Buat indeks biasa terlebih dahulu
        if (! Schema::hasIndex('surplus_listings', 'surplus_listings_food_date_index')) {
            Schema::table('surplus_listings', function (Blueprint $table) {
                $table->index(['food_id', 'listing_date'], 'surplus_listings_food_date_index');
            });
        }

        // 2. Setelah itu baru drop unique
        if (Schema::hasIndex('surplus_listings', 'surplus_listings_food_date_unique')) {
            Schema::table('surplus_listings', function (Blueprint $table) {
                $table->dropUnique('surplus_listings_food_date_unique');
            });
        }
    }

    public function down(): void
    {
        // Tidak mengembalikan unique: data baru yang sah dapat melanggarnya.
    }
};