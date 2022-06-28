<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{

    protected $command = [
        'App\Console\Commands\FormatData' // コマンドのパスを書く
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('command:formatdata') // 実行するコマンドの名前
        ->hourly() //->daily();	毎日深夜１２時に実行
        ->onFailure(function(){
            Log::info('データフォーマット失敗');
        })
        ->onSuccess(function () {
            Log::info('データフォーマット成功');
        }); 

        // $schedule->call(function () {
        //     DB::table('recent_users')->delete();
        // })->daily();

        // $schedule->command(SendEmailsCommand::class, ['Taylor', '--force'])->daily();
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
