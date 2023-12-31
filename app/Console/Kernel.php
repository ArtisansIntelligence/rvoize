<?php

namespace App\Console;

use App\Console\Commands\AutoCompleteGigOrder;
use App\Console\Commands\ScanExpiredUserPlan;
use App\Console\Commands\UserPlanExpired;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Modules\Payout\Commands\CreatePayoutsCommand;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(new ScanExpiredUserPlan())->everyTwoHours()->withoutOverlapping();
        $schedule->call(new AutoCompleteGigOrder())->hourly()->withoutOverlapping();
        $schedule->command(CreatePayoutsCommand::class)->monthlyOn(15);
        // $schedule->command(UserPlanExpired::class)->daily()->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
