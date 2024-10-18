<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('login');
            $table->string('telegram_username')->unique();
            $table->string('password');
            $table->string('mnemonic');
            $table->string('address_wallet');
            $table->boolean('is_admin')->default(false);
            $table->string('avatar_url')->nullable();
            $table->boolean('is_banned')->default(false);
            $table->integer('limit_domain')->default(3);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};