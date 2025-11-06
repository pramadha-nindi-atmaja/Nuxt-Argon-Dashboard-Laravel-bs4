<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Config;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        $this->scheduleResetUsersCommand($schedule);
    }

    /**
     * Schedule the reset users command in demo mode
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function scheduleResetUsersCommand(Schedule $schedule): void
    {
        if (!Config::get('app.demo', false)) {
            return;
        }

        $hour = Config::get('app.hour', '');
        $min = Config::get('app.min', '');
        
        $scheduledInterval = $this->buildCronExpression($hour, $min);
        $schedule->command('app:reset-default-users')
            ->cron($scheduledInterval)
            ->withoutOverlapping()
            ->runInBackground();
    }

    /**
     * Build cron expression based on configured hour and minute
     *
     * @param  string  $hour
     * @param  string  $min
     * @return string
     */
    protected function buildCronExpression(string $hour, string $min): string
    {
        if ($hour !== '') {
            return $min !== '' && $min != '0' 
                ? "{$min} */{$hour} * * *" 
                : "0 */{$hour} * * *";
        }
        
        return "*/{$min} * * * *";
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
