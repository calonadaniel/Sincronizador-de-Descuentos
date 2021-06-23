<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

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
        // $schedule->command('inspire')->hourly();
           $schedule->command('promoSincronizar:cron --force');
                    //->weekdays()
                    //->hourly()
                    //->between('6:00', '
                    //->wednesdays()
                    //->tuesdays()
                    //->at('22:14')
                    //->withoutOverlapping()
                    //->timezone('America/Tegucigalpa');
    }

    /**
     * Register the commands for the application.php
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
