<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FormatData extends Command
{

    

    ///
    /// crontab -e コマンドでクローン設定ファイルを開いて以下のクローンを追加
    /// * * * * *  cd /home/devuser/develop/demoapp/ && ./vendor/bin/sail artisan schedule:run >> /dev/null 2>&1
    /// * * * * * クローンコマンドの時間指定形式
    /// sail artisan make:command FormatData でこのファイルを生成
    ///



    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:formatdata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // 指定した時間になると、ここが処理される


    }
}
