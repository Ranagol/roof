<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\HaloOglasi;
use App\Console\Commands\Oglasi;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
	 * The command line command classes have to be listed here.
	 * @url https://stackoverflow.com/questions/30700396/laravel-no-scheduled-commands-are-ready-to-run
     *
     * @var array
     */
    protected $commands = [
        HaloOglasi::class,
        Oglasi::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
    	//OGLASI
        $schedule
            ->command('oglasi')
            ->at('09:00')
			->onSuccess(function () {
				echo 'oglasi executed succesfully by the scheduler';
				Log::info('oglasi executed succesfully by the scheduler');
			})
			->onFailure(function () {
				echo 'oglasi failed miserably by the scheduler';
				Log::info('oglasi failed miserably by the scheduler');
			});


        //HALO-OGLASI
        $schedule
            ->command('halo-oglasi')
            ->at('09:00')
            ->onSuccess(function () {
                echo 'halo-oglasi executed succesfully by the scheduler';
				Log::info('halo-oglasi executed succesfully by the scheduler');
            })
            ->onFailure(function () {
                echo 'halo-oglasi failed miserably by the scheduler';
				Log::info('halo-oglasi failed miserably by the scheduler');
            });
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
