<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Storage;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\boxqrcode::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        //每天午夜执行删除无效的压缩包
        $schedule->call(function () {
            $files = Storage::allFiles('public/zip/');
            Storage::delete($files);
        })->daily();

        $schedule->command('boxqrcode:generate')->everyMinute()->withoutOverlapping();

        //每月第15天删除上个月 1 至 28/29/30/31号的记录
        $schedule->command('delete:box')->monthlyOn(27, '00:00');
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
