<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UnblockUsers extends Command
{
    protected $signature = 'users:unblock';
    protected $description = 'Unblock users after 3 minutes lockout';
    public function handle()
    {
        $users = User::where('status', 'blocked')->where('last_failed_at', '<', Carbon::now()->subMinutes(3))->get();
        foreach ($users as $user) {
            $user->status = 'active';
            $user->failed_attempts = 0;
            $user->save();
        }
        $this->info('Blocked users have been reset to active.');
    }
}