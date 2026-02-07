<div wire:poll.10s class="relative me-3">
    <a href="{{ route('notifications') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
        <div class="relative">
            <i class="ph-bold ph-bell text-2xl" style="font-size: 1.5rem;"></i>
            @if($unreadCount > 0)
                <span class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white">
                    {{ $unreadCount }}
                </span>
            @endif
        </div>
    </a>
</div>
