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
         'App\Console\Commands\CheckTransactions',
         'App\Console\Commands\UpdateFiatRates',
//         'App\Console\Commands\FillCountries',
        'App\Console\Commands\dealTimer',
        'App\Console\Commands\addPrices',
        'App\Console\Commands\CreateDeals',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         $schedule->command('transactions:check-pending')
                  ->everyMinute();
 $schedule->command('fiats:update')
                  ->everyMinute();
        $schedule->command('deal:timer')
                 ->everyMinute();
        $schedule->command('add:prices')
                ->everyMinute();
//$schedule->command('countries:fill')
  //              ->everyMinute();
        $schedule->command('create:deals')
                ->everyMinute();
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
