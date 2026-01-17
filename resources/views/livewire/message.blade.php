<div>
    <!-- Messages Sections -->
    <section class="container py-5 mt-5">
        <div class="row g-4">
            <!-- Left Column: Message List -->
            <div class="col-lg-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold m-0">Inbox 
                        <span wire:loading wire:target="setFilter" class="spinner-border spinner-border-sm ms-2 text-primary" role="status"></span>
                    </h4>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                            data-bs-toggle="dropdown" wire:loading.attr="disabled" wire:target="setFilter">
                            Filter: {{ ucfirst($filter) }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item {{ $filter == 'all' ? 'active' : '' }}" href="#" wire:click.prevent="setFilter('all')">All Messages</a></li>
                            <li><a class="dropdown-item {{ $filter == 'unread' ? 'active' : '' }}" href="#" wire:click.prevent="setFilter('unread')">Unread</a></li>
                            <li><a class="dropdown-item {{ $filter == 'read' ? 'active' : '' }}" href="#" wire:click.prevent="setFilter('read')">Read</a></li>
                        </ul>
                    </div>
                </div>

                <div class="message-list-container" id="messageList">
                    @forelse($messages as $message)
                        <div class="message-list-item mb-2 {{ $selectedMessage && $selectedMessage->id === $message->id ? 'active' : '' }} {{ $message->status === 'unread' ? 'unread' : '' }}" 
                             wire:click="selectMessage({{ $message->id }})"
                             role="button">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="badge {{ $message->msg_cat === 'confession' ? 'bg-primary' : ($message->msg_cat === 'complaint' ? 'bg-secondary' : ($message->msg_cat === 'crush' ? 'bg-danger' : 'bg-info')) }} rounded-pill mb-1">
                                    {{ ucfirst($message->msg_cat) }}
                                </span>
                                <small class="text-muted">{{ $message->created_at->diffForHumans(null, true, true) }}</small>
                            </div>
                            <p class="mb-0 text-truncate {{ $message->status === 'unread' ? 'text-white' : 'text-muted' }} small">
                                {{ $message->content }}
                            </p>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            No messages found.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Right Column: Reading Pane -->
            <div class="col-lg-8">
                <div id="readPane" class="{{ !$selectedMessage ? 'd-none' : '' }} position-relative">
                    
                    <!-- Loading Overlay -->
                    <div wire:loading.flex wire:target="selectMessage" class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-75 justify-content-center align-items-center" style="z-index: 10; border-radius: 1rem;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    @if($selectedMessage)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="fw-bold m-0">Reading</h4>
                        <div class="d-flex gap-2">
                             <button class="btn btn-sm {{ $selectedMessage->reported_status === 'reported' ? 'btn-danger' : 'btn-outline-danger' }}" 
                                    wire:click="reportMessage" 
                                    wire:confirm="Are you sure you want to report this message? This action cannot be undone."
                                    {{ $selectedMessage->reported_status === 'reported' ? 'disabled' : '' }}>
                                <span wire:loading.remove wire:target="reportMessage"><i class="ph-bold ph-warning"></i> {{ $selectedMessage->reported_status === 'reported' ? 'Reported' : 'Report' }}</span>
                                <span wire:loading wire:target="reportMessage" class="spinner-border spinner-border-sm" role="status"></span>
                            </button>
                            <button id="downloadBtn" class="btn btn-sm btn-premium" onclick="captureAndDownload()">
                                <i class="ph-bold ph-share-network"></i> <span id="downloadText">Share as Image</span>
                                <span id="downloadLoader" class="spinner-border spinner-border-sm d-none" role="status"></span>
                            </button>
                        </div>
                    </div>

                    <!-- Capture Area for html2canvas -->
                    <div id="captureArea" class="capture-card p-5 position-relative">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold text-white mb-0 text-accent" style="font-family: 'Outfit', sans-serif;">Asiri.com.ng</h3>
                            <small class="text-mine">Anonymous Messages</small>
                        </div>
                
                        <div class="message-content-large text-center my-4">
                            <i class="ph-duotone ph-quotes text-primary fs-1 mb-3"></i>
                            <h2 class="fw-light text-white fst-italic" id="displayContent">
                                "{{ $selectedMessage->content }}"
                            </h2>
                        </div>

                        <div class="d-flex justify-content-center align-items-center mt-5">
                            <div class="d-flex align-items-center gap-2">
                                <img src="images/users/{{ auth()->user()->dp ?? 'default_avatar.png' }}" class="rounded-circle border border-2 border-primary"
                                    width="60">
                                <div>
                                    <span class="d-block fw-bold text-accent small">For: {{ '@' . auth()->user()->username }}</span>
                                    <span class="d-block text-muted" style="font-size: 0.7rem;" id="displayTime">
                                        {{ $selectedMessage->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Watermark -->
                        <div class="position-absolute bottom-0 end-0 p-3 opacity-25">
                            <small>asiri.com.ng</small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- html2canvas -->
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script>
        // Dynamic font sizing based on message length
        function adjustMessageFontSize() {
            const messageElement = document.getElementById('displayContent');
            if (!messageElement) return;
            
            const textLength = messageElement.textContent.trim().length;
            let fontSize;
            
            // Adjust font size based on text length
            if (textLength < 50) {
                fontSize = '2.5rem'; // Short messages - largest
            } else if (textLength < 100) {
                fontSize = '2rem'; // Medium-short messages
            } else if (textLength < 150) {
                fontSize = '1.7rem'; // Medium messages
            } else if (textLength < 200) {
                fontSize = '1.5rem'; // Medium-long messages
            } else if (textLength < 300) {
                fontSize = '1.3rem'; // Long messages
            } else {
                fontSize = '1.2rem'; // Very long messages - smallest
            }
            
            messageElement.style.fontSize = fontSize;
        }

        function captureAndDownload() {
            const btn = document.getElementById('downloadBtn');
            const text = document.getElementById('downloadText');
            const loader = document.getElementById('downloadLoader');

            // Set loading state
            btn.disabled = true;
            text.classList.add('d-none');
            loader.classList.remove('d-none');

            // Adjust font size before capturing
            adjustMessageFontSize();

            const element = document.getElementById('captureArea');
            html2canvas(element, {
                backgroundColor: '#1a1a1a', // Ensure background is captured correctly
                scale: 2 // Higher resolution,
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = 'asiri-message-{{ $selectedMessage->id ?? "share" }}.png';
                link.href = canvas.toDataURL();
                link.click();
            }).finally(() => {
                 // Reset loading state
                 btn.disabled = false;
                 text.classList.remove('d-none');
                 loader.classList.add('d-none');
            });
        }

        // Auto-scroll to reading pane on mobile when message is selected
        document.addEventListener('livewire:initialized', () => {
             Livewire.on('messageSelected', () => { // We'll need to dispatch this event from the component
                // Adjust font size when message is selected
                adjustMessageFontSize();
                
                if (window.innerWidth < 992) {
                    const readPane = document.getElementById('readPane');
                    if (readPane) {
                        readPane.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
            });
        });

        // Initial font size adjustment on page load
        document.addEventListener('DOMContentLoaded', adjustMessageFontSize);
    </script>
</div>
