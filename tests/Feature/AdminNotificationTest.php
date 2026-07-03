<?php

use App\Models\User;
use App\Models\ChatRoom;
use App\Models\Message;
use App\Notifications\Admin\AdminNewUserJoinedNotification;
use App\Notifications\Admin\AdminNewRoomPostNotification;
use App\Notifications\Admin\AdminMessageReportedNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use App\Livewire\Room\RoomFeed;
use App\Livewire\Message as MessageComponent;

test('admin receives notification when a new user joins', function () {
    Notification::fake();
    $admin = User::factory()->create([
        'role' => 'admin',
        'username' => 'admin',
        'rep' => 'ADM123456'
    ]);

    $this->post(route('site.register'), [
        'name' => 'New User',
        'email' => 'newuser@example.com',
        'username' => 'newuser',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    Notification::assertSentTo($admin, AdminNewUserJoinedNotification::class);
});

test('admin receives notification when a new post is created in a room', function () {
    Notification::fake();
    $admin = User::factory()->create([
        'role' => 'admin',
        'username' => 'admin2',
        'rep' => 'ADM654321'
    ]);
    ChatRoom::firstOrCreate(['slug' => 'general'], ['name' => 'General']);

    Livewire::test(RoomFeed::class, ['room' => 'general'])
        ->set('content', 'This is a secret post!')
        ->call('post');

    Notification::assertSentTo($admin, AdminNewRoomPostNotification::class);
});

test('admin receives notification when a message is reported', function () {
    Notification::fake();
    $admin = User::factory()->create([
        'role' => 'admin',
        'username' => 'admin3',
        'rep' => 'ADM987654'
    ]);
    $user = User::factory()->create([
        'username' => 'user1',
        'rep' => 'USR123456'
    ]);
    $message = Message::create([
        'user_id' => $user->id,
        'msg_cat' => 'confession',
        'content' => 'Hello!',
        'status' => 'unread',
        'sender_ip' => '127.0.0.1'
    ]);

    $this->actingAs($user);

    Livewire::test(MessageComponent::class)
        ->set('selectedMessage', $message)
        ->call('reportMessage');

    Notification::assertSentTo($admin, AdminMessageReportedNotification::class);
});
