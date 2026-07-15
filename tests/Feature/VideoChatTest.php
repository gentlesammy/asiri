<?php

use App\Models\User;
use App\Models\VideoChat;
use App\Models\VideoChatSignal;

it('redirects unauthenticated users to login', function () {
    $response = $this->get(route('video-chat.dashboard'));
    $response->assertRedirect('/login');
});

it('allows authenticated users to view the dashboard', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('video-chat.dashboard'));
    $response->assertStatus(200);
    $response->assertSee('Video Chat Hub');
});

it('allows authenticated users to launch a new room', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('video-chat.launch'));

    // Should redirect to the room page
    $chat = VideoChat::latest()->first();
    expect($chat)->not->toBeNull();
    expect($chat->host_id)->toBe($user->id);
    expect($chat->status)->toBe('waiting');

    $response->assertRedirect(route('video-chat.room', $chat->id));
});

it('allows authenticated users to visit their own room', function () {
    $user = User::factory()->create();
    $chat = VideoChat::create([
        'host_id' => $user->id,
        'status' => 'waiting',
    ]);

    $response = $this->actingAs($user)->get(route('video-chat.room', $chat->id));
    $response->assertStatus(200);
    $response->assertSee('Hosting Video Call');
});

it('allows a guest to join a room via invitation link', function () {
    $host = User::factory()->create(['username' => 'hostuser']);
    $guest = User::factory()->create(['username' => 'guestuser']);

    $chat = VideoChat::create([
        'host_id' => $host->id,
        'status' => 'waiting',
    ]);

    // Guest visits the join link
    $response = $this->actingAs($guest)->get(route('video-chat.join', 'hostuser'));

    // Should set guest_id, set status to active, and redirect to room
    $chat->refresh();
    expect($chat->guest_id)->toBe($guest->id);
    expect($chat->status)->toBe('active');

    $response->assertRedirect(route('video-chat.room', $chat->id));
});

it('prevents a second guest from overwriting the first guest (race condition)', function () {
    $host = User::factory()->create(['username' => 'racehost']);
    $guest1 = User::factory()->create(['username' => 'guest1']);
    $guest2 = User::factory()->create(['username' => 'guest2']);

    $chat = VideoChat::create([
        'host_id' => $host->id,
        'status' => 'waiting',
    ]);

    // First guest joins successfully
    $this->actingAs($guest1)->get(route('video-chat.join', 'racehost'));
    $chat->refresh();
    expect($chat->guest_id)->toBe($guest1->id);

    // Second guest tries to join — should be rejected
    $response = $this->actingAs($guest2)->get(route('video-chat.join', 'racehost'));
    $response->assertRedirect(route('video-chat.dashboard'));
    $response->assertSessionHas('error', 'This room already has a guest connected.');

    // Verify guest_id was NOT overwritten
    $chat->refresh();
    expect($chat->guest_id)->toBe($guest1->id);
});

it('does not allow joining via numeric user ID (prevents enumeration)', function () {
    $host = User::factory()->create();
    VideoChat::create([
        'host_id' => $host->id,
        'status' => 'waiting',
    ]);

    $guest = User::factory()->create();

    // Attempting to join using numeric ID should fail
    $response = $this->actingAs($guest)->get(route('video-chat.join', $host->id));
    $response->assertRedirect(route('video-chat.dashboard'));
    $response->assertSessionHas('error', 'No active video chat session found.');
});

it('allows exchange of WebRTC signals and database polling', function () {
    $host = User::factory()->create();
    $guest = User::factory()->create();

    $chat = VideoChat::create([
        'host_id' => $host->id,
        'guest_id' => $guest->id,
        'status' => 'active',
    ]);

    // Host sends an offer signal
    $response = $this->actingAs($host)->postJson(route('video-chat.signal', $chat->id), [
        'type' => 'offer',
        'payload' => '{"sdp":"offer-data"}'
    ]);

    $response->assertJson(['success' => true]);

    $signal = VideoChatSignal::latest()->first();
    expect($signal)->not->toBeNull();
    expect($signal->type)->toBe('offer');
    expect($signal->payload)->toBe('{"sdp":"offer-data"}');

    // Guest polls for signals
    $pollResponse = $this->actingAs($guest)->getJson(route('video-chat.poll', $chat->id) . '?last_signal_id=0');
    $pollResponse->assertStatus(200);
    $pollResponse->assertJsonFragment([
        'type' => 'offer',
        'payload' => '{"sdp":"offer-data"}'
    ]);
});

it('blocks non-participants from sending signals', function () {
    $host = User::factory()->create();
    $guest = User::factory()->create();
    $outsider = User::factory()->create();

    $chat = VideoChat::create([
        'host_id' => $host->id,
        'guest_id' => $guest->id,
        'status' => 'active',
    ]);

    // Outsider attempts to send a signal
    $response = $this->actingAs($outsider)->postJson(route('video-chat.signal', $chat->id), [
        'type' => 'offer',
        'payload' => '{"sdp":"malicious-offer"}'
    ]);

    $response->assertStatus(403);
});

it('blocks non-participants from polling signals', function () {
    $host = User::factory()->create();
    $guest = User::factory()->create();
    $outsider = User::factory()->create();

    $chat = VideoChat::create([
        'host_id' => $host->id,
        'guest_id' => $guest->id,
        'status' => 'active',
    ]);

    // Outsider attempts to poll
    $response = $this->actingAs($outsider)->getJson(route('video-chat.poll', $chat->id) . '?last_signal_id=0');
    $response->assertStatus(403);
});

it('blocks non-participants from ending a call', function () {
    $host = User::factory()->create();
    $guest = User::factory()->create();
    $outsider = User::factory()->create();

    $chat = VideoChat::create([
        'host_id' => $host->id,
        'guest_id' => $guest->id,
        'status' => 'active',
    ]);

    // Outsider attempts to end the call
    $response = $this->actingAs($outsider)->postJson(route('video-chat.end', $chat->id));
    $response->assertStatus(403);

    // Verify the room is still active
    $chat->refresh();
    expect($chat->status)->toBe('active');
});

it('allows ending a video chat room by a participant', function () {
    $user = User::factory()->create();
    $chat = VideoChat::create([
        'host_id' => $user->id,
        'status' => 'active',
    ]);

    $response = $this->actingAs($user)->postJson(route('video-chat.end', $chat->id));
    $response->assertJson(['success' => true]);

    $chat->refresh();
    expect($chat->status)->toBe('ended');
});

it('does not leak user information in error messages', function () {
    $host = User::factory()->create(['username' => 'secretuser', 'name' => 'Secret Name']);
    $guest = User::factory()->create();

    // No active room — error should NOT contain host name
    $response = $this->actingAs($guest)->get(route('video-chat.join', 'secretuser'));
    $response->assertRedirect(route('video-chat.dashboard'));
    $response->assertSessionHas('error', 'No active video chat session found.');
    $response->assertSessionMissing('error', 'Secret Name');
});
