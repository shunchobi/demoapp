<?php

namespace App\Console\Commands;

use App\Models\OutPutFormatYearMonth;
use App\Models\OutPutFromat;
use App\Models\User;
use App\Models\UserTouchHistory;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FormatData extends Command
{

    

    ///
    /// crontab -e コマンドでクローン設定ファイルを開いて以下のクローンを追加
    /// * * * * *  cd /home/devuser/develop/demoapp/ && ./vendor/bin/sail artisan schedule:run >> /dev/null 2>&1
    /// * * * * * クローンコマンドの時間指定形式
    /// /dev/null のnullファイルはブラックホール的なもので、データをappendしても吸い込まれてなくなる
    /// 2>&1は、2はエラー、&1は標準出力を示し、エラー出力も標準出力もappendするという意味になる
    /// sail artisan make:command FormatData でこのファイルを生成
    /// sail artisan schedule:list でクローンが実行されるコマンドを確認できるから、まずそのコマンドを実行してみて処理されるか確認
    /// 今回は実行（プロジェクトディレクトリにいる場合）されたが、指定した時間になってもサーバーからは実行されなかった
    /// echo $PATH でコマンド実行ファイルのパスを確認できるが、クローンのエラーログにsailのdocker-composeコマンドが見つからない
    /// とあったから、そのパスを調べ、crontabにパスを指定してコマンドを実行させている。(crontab -e でクローンの中身を確認できる)
    ///
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
    protected $description = 'CommandFormatdata';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // 集計対象日を定義
        $target_date = Carbon::today();

        // user list 取得
        $users = User::get();

        // for each user
        foreach ($users as $user) {      
            //　user の集計対象日のタッチ履歴一覧を取得
            $user_touched_history_at_target_day = UserTouchHistory::where('user_id', $user->id)->whereDate('touched_at', $target_date)->orderBy('touched_at')->get()->toArray();
            // 出勤(開始時間)・退勤時間(終了時間)を選出
            $star_time = null;
            $end_time = null;
            $start_time_round_up = null;
            $end_time_round_down = null;
            if (count($user_touched_history_at_target_day) >= 2){
                $star_time = reset($user_touched_history_at_target_day);
                $end_time = end($user_touched_history_at_target_day);
                 // 切り上げる分数
                 $round_up_time = 15;
                 // Carbonインスタンス作成
                 $start_time_round_up = new Carbon($star_time['touched_at']);
                 // 15分切り上げ
                 $start_time_round_up = $start_time_round_up->addMinutes($round_up_time - $start_time_round_up->minute % $round_up_time)->format('H:i:00');
 
                 // 切り下げる分数
                 $round_down_time = 15;
                 // Carbonインスタンス作成
                 $end_time_round_down = new Carbon($end_time['touched_at']);
                 // 15分切り下げ
                 $end_time_round_down = $end_time_round_down->subMinutes($end_time_round_down->minute % $round_down_time)->format('H:i:00'); 

                 if ($start_time_round_up > $end_time_round_down){
                    $end_time_round_down = $start_time_round_up;
                 }
            }
            else{
                Log::info('日付が '.$target_date->toString().' のuser_id = '.$user->id.'のtouched_atデータが不十分です。データを追加してください');
            }

            // 集計対象日の user の outputformat が登録済みなら更新、なければ作成
            OutPutFromat::updateOrCreate(
                [
                    'user_id' => $user->id, 
                    'date' => $target_date->toDateString(),
                ],
                [
                    // 'user_id' => $user->id, 
                    // 'date' => $target_date->toDateString(), 
                    'name' => User::find($user->id)->name, 
                    'original_start_time' => $star_time['touched_at'] ?? null, 
                    'original_end_time' => $end_time['touched_at'] ?? null, 
                    'round_up_start_time' => $start_time_round_up, 
                    'round_down_end_time' => $end_time_round_down,
                ],
            );

            OutPutFormatYearMonth::updateOrCreate(
                [
                    'year_month' =>  $target_date->format('Y-m'), 
                ],
                [
                    // 'year_month' =>  $target_date->format('Y-m'), 
                ]
            );
        }
        // end for
    }
}
