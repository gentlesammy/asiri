@extends('layouts.site')

@section('content')
<section class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-12 text-center mb-4">
            <span class="badge bg-danger px-3 py-2 mb-2 animate-pulse" id="call-status">
                <i class="ph-bold ph-video-camera me-1"></i> Waiting for Peer...
            </span>
            <h4 class="fw-bold m-0" id="room-title">
                @if($role === 'caller')
                    Hosting Video Call
                @else
                    Joined Video Call
                @endif
            </h4>
        </div>
    </div>

    <!-- Video Grid -->
    <div class="row justify-content-center">
        <div class="col-lg-10 position-relative">
            <!-- Video Containers -->
            <div class="video-container-wrapper shadow-lg position-relative rounded-4 overflow-hidden border border-secondary" style="height: 60vh; background: #080810;">
                <!-- Remote Video (Large / Background) -->
                <video id="remoteVideo" autoplay playsinline class="w-100 h-100 object-fit-cover d-none"></video>

                <!-- Remote Placeholder (Waiting Screen) -->
                <div id="remotePlaceholder" class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-center p-4">
                    <div class="avatar-pulsing mb-4">
                        <i class="ph-bold ph-user-circle text-accent" style="font-size: 80px;"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Waiting for connection...</h5>
                    <p class="text-white-50 max-w-400 small">
                        @if($role === 'caller')
                            Share your link to let friends join this video chat.
                        @else
                            Connecting to host...
                        @endif
                    </p>
                    @if($role === 'caller')
                        <div class="d-inline-flex gap-2 align-items-center bg-dark p-2 rounded-3 border border-secondary mt-2">
                            <span class="text-accent small font-monospace">{{ route('video-chat.join', Auth::user()->username) }}</span>
                            <button class="btn btn-sm btn-premium py-1 px-3" onclick="copyInviteUrl()">Copy Link</button>
                        </div>
                    @endif
                </div>

                <!-- Local Video (Picture-in-Picture) -->
                <div class="local-video-pip shadow">
                    <video id="localVideo" autoplay playsinline muted class="w-100 h-100 object-fit-cover rounded-3"></video>
                    <div class="local-video-tag small text-white bg-dark bg-opacity-75 px-2 py-1 rounded-2">
                        You
                    </div>
                </div>
            </div>

            <!-- Floating Control Bar -->
            <div class="d-flex justify-content-center mt-4">
                <div class="controls-bar d-flex align-items-center gap-3 px-4 py-3 rounded-pill shadow" style="background: rgba(10, 10, 20, 0.85); border: 1px solid var(--glass-border); backdrop-filter: blur(15px);">
                    <!-- Mute Mic -->
                    <button class="btn-control rounded-circle border-0 text-white" id="btn-toggle-mic" onclick="toggleMic()" title="Mute/Unmute Mic">
                        <i class="ph-bold ph-microphone fs-4"></i>
                    </button>

                    <!-- Toggle Video -->
                    <button class="btn-control rounded-circle border-0 text-white" id="btn-toggle-cam" onclick="toggleCam()" title="Camera On/Off">
                        <i class="ph-bold ph-video-camera fs-4"></i>
                    </button>

                    <!-- Copy Invitation Link -->
                    <button class="btn-control rounded-circle border-0 text-white" id="btn-copy-link" onclick="copyInviteUrl()" title="Copy Invitation Link">
                        <i class="ph-bold ph-link fs-4"></i>
                    </button>

                    <div class="vr bg-secondary mx-1" style="height: 30px;"></div>

                    <!-- End Call -->
                    <button class="btn-control btn-end-call rounded-circle border-0 text-white bg-danger" onclick="endCall()" title="End Call">
                        <i class="ph-bold ph-phone-disconnect fs-4"></i>
                    </button>
                </div>
            </div>
            
            <div class="text-center mt-3 text-white-50 small" id="copy-toast" style="min-height: 20px;"></div>
        </div>
    </div>
</section>

<!-- WebRTC & Signaling Scripts -->
<script>
    const roomId = "{{ $chat->id }}";
    const userRole = "{{ $role }}";
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    let localStream = null;
    let remoteStream = null;
    let peerConnection = null;
    let lastSignalId = 0;
    let pollInterval = null;
    
    let micEnabled = true;
    let camEnabled = true;
    let offerSent = false;
    
    // Remote candidates queue if description is not set yet
    let remoteCandidatesQueue = [];

    // WebRTC STUN configurations
    const rtcConfig = {
        iceServers: [
            { urls: 'stun:stun.l.google.com:19302' },
            { urls: 'stun:stun1.l.google.com:19302' },
            { urls: 'stun:stun2.l.google.com:19302' }
        ]
    };

    // Initialize Media (Camera & Mic)
    async function initMedia() {
        try {
            localStream = await navigator.mediaDevices.getUserMedia({
                video: true,
                audio: true
            });
            document.getElementById('localVideo').srcObject = localStream;
            initWebRTC();
        } catch (error) {
            console.warn('Camera+mic failed, attempting audio-only:', error);
            try {
                // Fallback: audio-only mode
                localStream = await navigator.mediaDevices.getUserMedia({
                    video: false,
                    audio: true
                });
                document.getElementById('localVideo').srcObject = localStream;
                camEnabled = false;
                const camBtn = document.getElementById('btn-toggle-cam');
                camBtn.classList.add('bg-danger');
                camBtn.innerHTML = '<i class="ph-bold ph-video-camera-slash fs-4"></i>';
                document.getElementById('call-status').innerHTML = '<i class="ph-bold ph-warning me-1"></i> Audio Only Mode';
                initWebRTC();
            } catch (fallbackError) {
                console.error('All media access failed:', fallbackError);
                // Show inline error instead of alert()
                document.getElementById('remotePlaceholder').innerHTML = `
                    <i class="ph-bold ph-warning-circle text-danger" style="font-size: 60px;"></i>
                    <h5 class="fw-bold mb-2 mt-3">Camera & Microphone Access Denied</h5>
                    <p class="text-white-50 small">Please grant browser permissions and reload the page.</p>
                    <a href="{{ route('video-chat.dashboard') }}" class="btn btn-premium mt-2">Back to Dashboard</a>
                `;
            }
        }
    }

    // Configure WebRTC Connection
    function initWebRTC() {
        peerConnection = new RTCPeerConnection(rtcConfig);

        // Add local tracks to peer connection
        localStream.getTracks().forEach(track => {
            peerConnection.addTrack(track, localStream);
        });

        // Remote track received
        peerConnection.ontrack = (event) => {
            console.log('Remote track received!');
            remoteStream = event.streams[0];
            const remoteVideo = document.getElementById('remoteVideo');
            remoteVideo.srcObject = remoteStream;
            
            // Show video element, hide placeholder
            remoteVideo.classList.remove('d-none');
            document.getElementById('remotePlaceholder').classList.add('d-none');
            document.getElementById('call-status').className = "badge bg-success px-3 py-2 mb-2";
            document.getElementById('call-status').innerHTML = '<i class="ph-bold ph-activity me-1"></i> Connected';
        };

        // ICE Candidate generated
        peerConnection.onicecandidate = (event) => {
            if (event.candidate) {
                sendSignal('ice_candidate', JSON.stringify(event.candidate));
            }
        };

        peerConnection.onconnectionstatechange = () => {
            console.log('Connection state change:', peerConnection.connectionState);
            if (peerConnection.connectionState === 'disconnected' || peerConnection.connectionState === 'failed') {
                document.getElementById('call-status').className = "badge bg-danger px-3 py-2 mb-2 animate-pulse";
                document.getElementById('call-status').innerHTML = '<i class="ph-bold ph-warning me-1"></i> Peer Disconnected';
            }
        };

        // Start Signaling Polling
        pollInterval = setInterval(pollSignaling, 1500);
        pollSignaling(); // Poll immediately
    }

    // Send WebRTC Signals to Backend
    async function sendSignal(type, payload) {
        try {
            await fetch(`/video-chat/room/${roomId}/signal`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ type, payload })
            });
        } catch (error) {
            console.error('Error sending signal:', error);
        }
    }

    // Poll Backend for Signals & Room Status
    async function pollSignaling() {
        try {
            const response = await fetch(`/video-chat/room/${roomId}/poll?last_signal_id=${lastSignalId}`);
            const data = await response.json();

            // 1. Check if room is ended
            if (data.status === 'ended') {
                clearInterval(pollInterval);
                alert('The video call session has ended.');
                window.location.href = "{{ route('video-chat.dashboard') }}";
                return;
            }

            // 2. Caller-specific logic (Initiate offer if guest joins)
            if (userRole === 'caller') {
                if (data.guest && !offerSent) {
                    offerSent = true;
                    document.getElementById('call-status').className = "badge bg-warning px-3 py-2 mb-2";
                    document.getElementById('call-status').innerHTML = '<i class="ph-bold ph-spinner spinner me-1"></i> Guest Joined, Dialing...';
                    
                    try {
                        const offer = await peerConnection.createOffer();
                        await peerConnection.setLocalDescription(offer);
                        sendSignal('offer', JSON.stringify(offer));
                    } catch (err) {
                        console.error('Failed to create offer:', err);
                        document.getElementById('call-status').className = "badge bg-danger px-3 py-2 mb-2";
                        document.getElementById('call-status').innerHTML = '<i class="ph-bold ph-warning me-1"></i> Connection Failed';
                    }
                }
            }

            // 3. Process incoming signals from peer
            if (data.signals && data.signals.length > 0) {
                for (const signal of data.signals) {
                    lastSignalId = Math.max(lastSignalId, signal.id);
                    await handleIncomingSignal(signal);
                }
            }
        } catch (error) {
            console.error('Signaling poll error:', error);
        }
    }

    // Process SDP & ICE Candidates
    async function handleIncomingSignal(signal) {
        const payload = JSON.parse(signal.payload);
        
        if (signal.type === 'offer') {
            console.log('SDP Offer received');
            try {
                await peerConnection.setRemoteDescription(new RTCSessionDescription(payload));
                await processQueuedCandidates();
                
                const answer = await peerConnection.createAnswer();
                await peerConnection.setLocalDescription(answer);
                sendSignal('answer', JSON.stringify(answer));
            } catch (err) {
                console.error('Failed to handle offer:', err);
                document.getElementById('call-status').className = "badge bg-danger px-3 py-2 mb-2";
                document.getElementById('call-status').innerHTML = '<i class="ph-bold ph-warning me-1"></i> Connection Failed';
            }

        } else if (signal.type === 'answer') {
            console.log('SDP Answer received');
            await peerConnection.setRemoteDescription(new RTCSessionDescription(payload));
            
            // Process any queued remote ICE candidates
            await processQueuedCandidates();

        } else if (signal.type === 'ice_candidate') {
            console.log('Remote ICE candidate received');
            const candidate = new RTCIceCandidate(payload);
            
            if (peerConnection.remoteDescription && peerConnection.remoteDescription.type) {
                await peerConnection.addIceCandidate(candidate);
            } else {
                // Queue the candidate until description is set
                remoteCandidatesQueue.push(candidate);
            }
        }
    }

    // Add queued ICE candidates once description is verified
    async function processQueuedCandidates() {
        if (remoteCandidatesQueue.length > 0) {
            console.log(`Processing ${remoteCandidatesQueue.length} queued remote candidates`);
            for (const candidate of remoteCandidatesQueue) {
                await peerConnection.addIceCandidate(candidate);
            }
            remoteCandidatesQueue = [];
        }
    }

    // Toggle Camera State
    function toggleCam() {
        camEnabled = !camEnabled;
        const videoTrack = localStream.getVideoTracks()[0];
        if (videoTrack) {
            videoTrack.enabled = camEnabled;
        }

        const btn = document.getElementById('btn-toggle-cam');
        if (camEnabled) {
            btn.classList.remove('bg-danger');
            btn.innerHTML = '<i class="ph-bold ph-video-camera fs-4"></i>';
        } else {
            btn.classList.add('bg-danger');
            btn.innerHTML = '<i class="ph-bold ph-video-camera-slash fs-4"></i>';
        }
    }

    // Toggle Microphone State
    function toggleMic() {
        micEnabled = !micEnabled;
        const audioTrack = localStream.getAudioTracks()[0];
        if (audioTrack) {
            audioTrack.enabled = micEnabled;
        }

        const btn = document.getElementById('btn-toggle-mic');
        if (micEnabled) {
            btn.classList.remove('bg-danger');
            btn.innerHTML = '<i class="ph-bold ph-microphone fs-4"></i>';
        } else {
            btn.classList.add('bg-danger');
            btn.innerHTML = '<i class="ph-bold ph-microphone-slash fs-4"></i>';
        }
    }

    // End / Leave Call
    // Clean up WebRTC resources
    function cleanupConnection() {
        clearInterval(pollInterval);
        if (localStream) {
            localStream.getTracks().forEach(track => track.stop());
        }
        if (peerConnection) {
            peerConnection.close();
            peerConnection = null;
        }
    }

    async function endCall() {
        if (confirm('Are you sure you want to end this video call?')) {
            cleanupConnection();

            try {
                await fetch(`/video-chat/room/${roomId}/end`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
            } catch (error) {
                console.error('Error ending room call:', error);
            }

            window.location.href = "{{ route('video-chat.dashboard') }}";
        }
    }

    // Clean up when user closes tab or navigates away
    window.addEventListener('beforeunload', () => {
        cleanupConnection();
        // Use sendBeacon for reliable delivery during page unload
        const formData = new FormData();
        formData.append('_token', csrfToken);
        navigator.sendBeacon(`/video-chat/room/${roomId}/end`, formData);
    });

    // Helper: Copy Invitation Link
    function copyInviteUrl() {
        const url = "{{ route('video-chat.join', $chat->host->username) }}";
        navigator.clipboard.writeText(url).then(() => {
            const toast = document.getElementById('copy-toast');
            toast.innerHTML = '<i class="ph-bold ph-check-circle text-accent"></i> Invitation link copied!';
            setTimeout(() => { toast.innerHTML = ''; }, 3000);
        }).catch(err => {
            console.error('Error copying link:', err);
        });
    }

    // Start everything on page load
    window.addEventListener('load', initMedia);
</script>

<style>
    .animate-pulse {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    .spinner {
        animation: spin 2s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Video Room Layout Custom Styles */
    .local-video-pip {
        position: absolute;
        bottom: 20px;
        right: 20px;
        width: 180px;
        height: 120px;
        border-radius: 10px;
        border: 2px solid var(--accent);
        background: #000;
        z-index: 100;
        overflow: hidden;
    }

    .local-video-tag {
        position: absolute;
        bottom: 5px;
        left: 5px;
        font-size: 10px;
    }

    /* Control Buttons */
    .btn-control {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid var(--glass-border);
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .btn-control:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
    }

    .btn-end-call {
        width: 55px;
        height: 55px;
        background: #DC3545 !important;
        box-shadow: 0 0 15px rgba(220, 53, 69, 0.4);
    }

    .btn-end-call:hover {
        background: #BB2D3B !important;
        box-shadow: 0 0 25px rgba(220, 53, 69, 0.7);
    }

    .avatar-pulsing {
        position: relative;
        display: inline-block;
    }

    .avatar-pulsing::after {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        border-radius: 50%;
        box-shadow: 0 0 20px var(--primary-glow);
        animation: avatarPulse 2.5s infinite;
        z-index: -1;
    }

    @keyframes avatarPulse {
        0% { transform: scale(0.9); opacity: 0.8; }
        50% { transform: scale(1.3); opacity: 0; }
        100% { transform: scale(0.9); opacity: 0; }
    }

    @media (max-width: 768px) {
        .local-video-pip {
            width: 120px;
            height: 90px;
            bottom: 10px;
            right: 10px;
        }
    }
</style>
@endsection
