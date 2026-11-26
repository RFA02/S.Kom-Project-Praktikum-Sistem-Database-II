<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('foods') && ! Schema::hasColumn('foods', 'archived_at')) {
            Schema::table('foods', function (Blueprint $table) {
                $table->timestamp('archived_at')->nullable()->index();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('foods', 'archived_at')) {
            Schema::table('foods', function (Blueprint $table) {
                $table->dropColumn('archived_at');
            });
        }
    }
};
