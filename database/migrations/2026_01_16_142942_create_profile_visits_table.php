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
        Schema::create('profile_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Profile owner
            $table->string('visitor_ip', 45); // IPv4 or IPv6
            $table->foreignId('visitor_user_id')->nullable()->constrained('users')->onDelete('set null'); // Logged-in visitor
            $table->text('user_agent')->nullable(); // Browser/device info
            $table->timestamp('visited_at')->useCurrent(); // Visit timestamp
            
            // Indexes for efficient querying
            $table->index('user_id');
            $table->index('visited_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_visits');
    }
};
