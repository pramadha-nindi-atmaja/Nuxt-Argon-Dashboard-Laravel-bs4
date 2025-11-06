<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;

class ResetDefaultUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-default-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset the default admin user and remove other users in demo mode';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if (Config::get('app.demo', false)) {
            $adminId = 1;
            $user = User::find($adminId);

            if ($user) {
                $user->update([
                    'name' => 'Admin',
                    'email' => 'admin@jsonapi.com',
                    'password' => Hash::make('secret')
                ]);

                // Delete all non-admin users
                User::where('id', '!=', $adminId)->delete();
                $this->info('Default admin user has been reset and other users have been removed.');
            } else {
                $this->error('Admin user not found.');
            }
        } else {
            $this->warn('This command can only be executed in demo mode.');
        }
    }
}
