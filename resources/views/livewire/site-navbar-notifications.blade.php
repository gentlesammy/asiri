<li class="nav-item position-relative" wire:poll.10s>
    <a class="nav-link" href="{{ route('notifications') }}">
        <i class="ph-bold ph-bell fs-5"></i>
        @if($unreadCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="margin-left: -10px; margin-top: 5px;">
                {{ $unreadCount }}
                <span class="visually-hidden">unread messages</span>
            </span>
        @endif
    </a>
</li>
