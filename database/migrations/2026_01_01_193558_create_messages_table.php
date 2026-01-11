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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            //id,  user_id, msg_cat:enum, content, status:read, unread, archieved,  reported_status: reported, unrepoted, awaiting admin,  acted upon, sender_ip,date sent,  ,
            #user id column
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('msg_cat', ['confession', 'question', 'crush', 'advice', 'compliment',  'other']);
            $table->text('content');
            $table->enum('status', ['read', 'unread', 'archieved', 'deleted'])->default('unread');
            $table->enum('reported_status', ['reported', 'unrepoted', 'awaiting admin', 'acted upon'])->default('unrepoted');
            $table->boolean('is_flagged')->default(false);
            $table->string('sender_ip');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
