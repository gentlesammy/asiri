<div>
    <!-- Messages Sections -->
    <section class="container py-5 mt-5">
        <div class="row g-4">
            <!-- Left Column: Message List -->
            <div class="col-lg-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold m-0">Inbox</h4>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                            data-bs-toggle="dropdown">
                            Filter
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item active" href="#" data-filter="all">All Messages</a></li>
                            <li><a class="dropdown-item" href="#" data-filter="unread">Unread</a></li>
                            <li><a class="dropdown-item" href="#" data-filter="read">Read</a></li>
                        </ul>
                    </div>
                </div>

                <div class="message-list-container" id="messageList">
                    <!-- Message Item 1 -->
                    <div class="message-list-item active unread mb-2" onclick="selectMessage(this)" data-id="1"
                        data-content="I really admire how you handle pressure at work. You're an inspiration!"
                        data-time="2 hours ago" data-status="unread">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="badge bg-primary rounded-pill mb-1">Confession</span>
                            <small class="text-muted">2h ago</small>
                        </div>
                        <p class="mb-0 text-truncate text-white small">I really admire how you handle pressure...</p>
                    </div>

                    <!-- Message Item 2 -->
                    <div class="message-list-item mb-2" onclick="selectMessage(this)" data-id="2"
                        data-content="Stop stealing my lunch from the fridge! I know it's you." data-time="1 day ago"
                        data-status="read">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="badge bg-secondary rounded-pill mb-1">Complaint</span>
                            <small class="text-muted">1d ago</small>
                        </div>
                        <p class="mb-0 text-truncate text-muted small">Stop stealing my lunch from the...</p>
                    </div>

                    <!-- Message Item 3 -->
                    <div class="message-list-item unread mb-2" onclick="selectMessage(this)" data-id="3"
                        data-content="You looked really great at the party last night." data-time="5 hours ago"
                        data-status="unread">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="badge bg-danger rounded-pill mb-1">Secret Crush</span>
                            <small class="text-muted">5h ago</small>
                        </div>
                        <p class="mb-0 text-truncate text-white small">You looked really great at the...</p>
                    </div>

                    <!-- Message Item 4 -->
                    <div class="message-list-item mb-2" onclick="selectMessage(this)" data-id="4"
                        data-content="Are we still on for the project meeting tomorrow?" data-time="3 days ago"
                        data-status="read">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="badge bg-info rounded-pill mb-1">Question</span>
                            <small class="text-muted">3d ago</small>
                        </div>
                        <p class="mb-0 text-truncate text-muted small">Are we still on for the project...</p>
                    </div>
                </div>
            </div>

            <!-- Right Column: Reading Pane -->
            <div class="col-lg-8">
                <div id="readPane">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="fw-bold m-0">Reading</h4>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-danger" onclick="reportMessage()">
                                <i class="ph-bold ph-warning"></i> Report
                            </button>
                            <button class="btn btn-sm btn-premium" onclick="shareMessage()">
                                <i class="ph-bold ph-share-network"></i> Share as Image
                            </button>
                        </div>
                    </div>

                    <!-- Capture Area for html2canvas -->
                    <div id="captureArea" class="capture-card p-5 position-relative">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold text-white mb-0" style="font-family: 'Outfit', sans-serif;">Asiri</h3>
                            <small class="text-muted">Anonymous Messages</small>
                        </div>

                        <div class="message-content-large text-center my-4">
                            <i class="ph-duotone ph-quotes text-primary fs-1 mb-3"></i>
                            <h2 class="fw-light text-white fst-italic" id="displayContent">
                                "I really admire how you handle pressure at work. You're an inspiration!"
                            </h2>
                        </div>

                        <div class="d-flex justify-content-center align-items-center mt-5">
                            <div class="d-flex align-items-center gap-2">
                                <img src="default_avatar.png" class="rounded-circle border border-2 border-primary"
                                    width="40">
                                <div>
                                    <span class="d-block fw-bold text-white small">For: @anonymous_user</span>
                                    <span class="d-block text-muted" style="font-size: 0.7rem;" id="displayTime">2 hours
                                        ago</span>
                                </div>
                            </div>
                        </div>

                        <!-- Watermark -->
                        <div class="position-absolute bottom-0 end-0 p-3 opacity-25">
                            <small>asiri.app</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- html2canvas -->
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script>
        function selectMessage(element) {
            // Remove active class from all items
            const items = document.querySelectorAll('.message-list-item');
            items.forEach(item => {
                item.classList.remove('active');
            });

            // Add active class to the clicked item
            element.classList.add('active');

            // Update the reading pane
            const content = element.getAttribute('data-content');
            const time = element.getAttribute('data-time');
            const status = element.getAttribute('data-status');

            document.getElementById('displayContent').textContent = content;
            document.getElementById('displayTime').textContent = time;
            document.getElementById('readPane').classList.remove('d-none');
        }
    </script>
</div>
