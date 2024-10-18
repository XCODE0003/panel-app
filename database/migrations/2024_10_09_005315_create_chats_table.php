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
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_group')->default(false);
            $table->string('name')->nullable();
            $table->string('avatar')->nullable();
            $table->boolean('allow_messages')->default(true);
            $table->integer('user_id_sender')->nullable();
            $table->integer('user_id_recipient')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};