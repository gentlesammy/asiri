<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RoomCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'room:cleanup';

    protected $description = 'Deletes all anonymous room posts';

    public function handle()
    {
        \App\Models\RoomPost::truncate();
        $this->info('All room posts have been deleted.');
    }
}
