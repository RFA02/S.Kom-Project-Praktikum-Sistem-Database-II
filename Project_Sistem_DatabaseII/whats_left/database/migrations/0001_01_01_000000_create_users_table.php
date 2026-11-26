<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Semua blok dilindungi Schema::hasTable() agar aman dijalankan pada
     * database MySQL "whats_left" yang tabelnya sudah dibuat manual.
     */
    public function up(): void
    {
        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('first_name', 80);
                $table->string('last_name', 80)->default('');
                $table->string('email', 120)->unique();
                $table->string('password');
                $table->string('role', 20)->default('customer')->index();
                $table->string('phone', 20)->nullable();
                $table->rememberToken();
            });
        }

        if (! Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        if (! Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Sengaja kosong: tabel users/sessions bisa berisi data live. Rollback tidak boleh menghapusnya.
    }
};
