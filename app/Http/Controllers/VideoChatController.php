<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VideoChat;
use App\Models\VideoChatSignal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VideoChatController extends Controller
{
    /**
     * Display the video chat dashboard.
     */
    public function index()
    {
        $userId = Auth::id();
        
        // Find any active chat rooms where the user is either the host or the guest
        $activeRooms = VideoChat::with(['host', 'guest'])
            ->where(function ($query) use ($userId) {
                $query->where('host_id', $userId)
                      ->orWhere('guest_id', $userId);
            })
            ->whereIn('status', ['waiting', 'active'])
            ->latest()
            ->get();

        return view('video-chat.dashboard', compact('activeRooms'));
    }

    /**
     * Launch a new video chat room.
     */
    public function launch()
    {
        // First, mark any of the host's existing active/waiting rooms as ended to avoid conflicts
        VideoChat::where('host_id', Auth::id())
            ->whereIn('status', ['waiting', 'active'])
            ->update(['status' => 'ended']);

        // Create a new room
        $chat = VideoChat::create([
            'host_id' => Auth::id(),
            'status' => 'waiting',
        ]);

        return redirect()->route('video-chat.room', $chat->id);
    }

    /**
     * Join a room via the host's invitation link (username or ID).
     */
    public function join($username)
    {
        // Find the host by username only — never by numeric ID to prevent enumeration
        $host = User::where('username', $username)->first();

        if (!$host) {
            return redirect()->route('video-chat.dashboard')
                ->with('error', 'No active video chat session found.');
        }

        // Find the host's latest active or waiting room
        $chat = VideoChat::where('host_id', $host->id)
            ->whereIn('status', ['waiting', 'active'])
            ->latest()
            ->first();

        if (!$chat) {
            return redirect()->route('video-chat.dashboard')
                ->with('error', 'No active video chat session found.');
        }

        // If current user is not the host, attempt to register them as the guest
        if ($chat->host_id !== Auth::id()) {
            // Use a conditional update to prevent race conditions — only succeeds if guest_id is still null
            $affected = VideoChat::where('id', $chat->id)
                ->whereNull('guest_id')
                ->update([
                    'guest_id' => Auth::id(),
                    'status' => 'active',
                ]);

            if (!$affected) {
                return redirect()->route('video-chat.dashboard')
                    ->with('error', 'This room already has a guest connected.');
            }
        }

        return redirect()->route('video-chat.room', $chat->id);
    }

    /**
     * Render the video chat room.
     */
    public function room($id)
    {
        $chat = VideoChat::with(['host', 'guest'])->findOrFail($id);

        // Ensure the current user is authorized to be in this room
        if (Auth::id() !== $chat->host_id && Auth::id() !== $chat->guest_id) {
            // If the room is waiting, attempt to join as guest (race-safe)
            if ($chat->status === 'waiting' && !$chat->guest_id) {
                $affected = VideoChat::where('id', $chat->id)
                    ->whereNull('guest_id')
                    ->update([
                        'guest_id' => Auth::id(),
                        'status' => 'active',
                    ]);

                if (!$affected) {
                    return redirect()->route('video-chat.dashboard')
                        ->with('error', 'This room already has a guest connected.');
                }

                $chat->refresh();
            } else {
                return redirect()->route('video-chat.dashboard')
                    ->with('error', 'You are not authorized to join this room.');
            }
        }

        if ($chat->status === 'ended') {
            return redirect()->route('video-chat.dashboard')
                ->with('error', 'This video chat session has already ended.');
        }

        $role = (Auth::id() === $chat->host_id) ? 'caller' : 'receiver';

        return view('video-chat.room', compact('chat', 'role'));
    }

    /**
     * Save signaling messages.
     */
    public function signal(Request $request, $id)
    {
        $chat = VideoChat::findOrFail($id);

        // Only participants can send signals
        if (Auth::id() !== $chat->host_id && Auth::id() !== $chat->guest_id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'type' => 'required|string|in:offer,answer,ice_candidate',
            'payload' => 'required|string',
        ]);

        VideoChatSignal::create([
            'video_chat_id' => $id,
            'sender_id' => Auth::id(),
            'type' => $request->type,
            'payload' => $request->payload,
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Poll the current status of the room and fetch new signals from the peer.
     */
    public function poll(Request $request, $id)
    {
        $chat = VideoChat::with(['host', 'guest'])->findOrFail($id);

        // Only participants can poll for signals
        if (Auth::id() !== $chat->host_id && Auth::id() !== $chat->guest_id) {
            abort(403, 'Unauthorized');
        }

        $lastSignalId = (int) $request->get('last_signal_id', 0);

        // Get new signals sent by the other party
        $signals = VideoChatSignal::where('video_chat_id', $id)
            ->where('sender_id', '!=', Auth::id())
            ->where('id', '>', $lastSignalId)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'status' => $chat->status,
            'host' => [
                'id' => $chat->host->id,
                'name' => $chat->host->name,
                'username' => $chat->host->username,
            ],
            'guest' => $chat->guest ? [
                'id' => $chat->guest->id,
                'name' => $chat->guest->name,
                'username' => $chat->guest->username,
            ] : null,
            'signals' => $signals,
        ]);
    }

    /**
     * End the video chat session.
     */
    public function end($id)
    {
        $chat = VideoChat::findOrFail($id);

        // Only participants can end the call
        if (Auth::id() !== $chat->host_id && Auth::id() !== $chat->guest_id) {
            abort(403, 'Unauthorized');
        }

        $chat->update(['status' => 'ended']);

        return response()->json(['success' => true]);
    }
}
