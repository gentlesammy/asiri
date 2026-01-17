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
        Schema::table('polls', function (Blueprint $table) {
            $table->timestamp('closes_at')->nullable()->after('status');
            // Modifying enum is tricky in some DBs, let's just use string or raw statement if valid
            // Or we just rely on the fact that we can insert 'canceled' if we drop the constraint or change type
            // Laravel 'change()' requires dbal. 
            // Simplest: `status` was created as ENUM. 
            // We can change it to string to allow more values.
            $table->string('status')->change(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('polls', function (Blueprint $table) {
            $table->dropColumn('closes_at');
            // Revert status to enum if possible, or leave as string
        });
    }
};
