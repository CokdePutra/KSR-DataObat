<?php

namespace App\Console;

use App\Models\Batch;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected $command = [
        Commands\BatchUpdate::class
    ];

    protected function schedule(Schedule $schedule): void
    {
        // $schedule->call(function () {

        //     DB::table('batches')
        //         ->update([
        //             'is_active' => DB::raw('CASE WHEN expired_date < NOW() THEN 0 ELSE 1 END')
        //         ]);
        // })->everySecond();
        $schedule->command('batch:update')->everySecond();
        // $schedule->command('batch:update')->cron('1 * * * *');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
