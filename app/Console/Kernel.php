<?php

namespace App\Console;
// use App\Config;
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

        // $config = Config::first();

        // $sendAt =  date('H:i', strtotime($config['time_send'])) ;
        // $schedule->command('inspire')->hourly();
        $schedule->call('App\Http\Controllers\HomeController@message')
                ->daily()
                // ->everyMinute()
                ->sendOutputTo(public_path().'/logs/daily.log');
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
