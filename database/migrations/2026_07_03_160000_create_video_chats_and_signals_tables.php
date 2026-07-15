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
        Schema::create('video_chats', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('host_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('guest_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('status')->default('waiting'); // 'waiting', 'active', 'ended'
            $table->timestamps();
        });

        Schema::create('video_chat_signals', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('video_chat_id')->constrained('video_chats')->cascadeOnDelete();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->string('type'); // 'offer', 'answer', 'ice_candidate'
            $table->longText('payload');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_chat_signals');
        Schema::dropIfExists('video_chats');
    }
};
