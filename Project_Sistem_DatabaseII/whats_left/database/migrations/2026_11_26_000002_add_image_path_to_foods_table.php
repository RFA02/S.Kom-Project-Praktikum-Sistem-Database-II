<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('foods') && ! Schema::hasColumn('foods', 'image_path')) {
            Schema::table('foods', function (Blueprint $table) {
                $table->string('image_path')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('foods', 'image_path')) {
            Schema::table('foods', function (Blueprint $table) {
                $table->dropColumn('image_path');
            });
        }
    }
};
