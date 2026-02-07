<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('room_posts', function (Blueprint $table) {
            $table->foreignId('chat_room_id')->nullable()->after('id')->constrained('chat_rooms')->cascadeOnDelete();
        });

        // Assign existing posts to the default 'General' room (ID 1)
        // We assume ID 1 exists because we seeded it in the previous migration
        DB::table('room_posts')->whereNull('chat_room_id')->update(['chat_room_id' => 1]);

        // After populating, we can make it not nullable if we want strictness, 
        // but for safety in this migration step, let's keep it nullable or modify it.
        // Let's modify it to be not null now that we've backfilled.
        Schema::table('room_posts', function (Blueprint $table) {
             $table->foreignId('chat_room_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_posts', function (Blueprint $table) {
            $table->dropForeign(['chat_room_id']);
            $table->dropColumn('chat_room_id');
        });
    }
};
