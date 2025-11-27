<?php

namespace Panacea\Console;

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
        \Panacea\Console\Commands\GenerateCode::class,
        \Panacea\Console\Commands\DeleteCode::class,
        //   \Panacea\Console\Commands\Inspire::class,

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //schedule panacea:delete command
        $schedule->command('panacea:delete')
            ->hourly();
         //schedule panacea:generate command
        $schedule->command('panacea:generate')
            ->hourly();
//        $schedule->command('backup:mysql-dump check_history')
//            ->twiceDaily(2, 14);
//        $schedule->command('backup:mysql-dump code')
//            ->weekly();
//        $schedule->command('backup:mysql-dump')
//            ->monthly();
//        $schedule->command('backup:mysql-dump company_user')
//            ->weekly();
//        $schedule->call('\Panacea\Http\Controllers\ProbabilisticModelController@index')
//            ->weekly();
    }
}
