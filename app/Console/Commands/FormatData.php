<?php

namespace App\Console\Commands;

use App\Models\ManualTime;
use App\Models\OutPutFormatYearMonth;
use App\Models\OutPutFromat;
use App\Models\User;
use App\Models\UserTouchHistory;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Nette\NotImplementedException;


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


class StartEnd {
    public Carbon|null $start = null;
    public Carbon|null $end = null;

    public function __construct(Carbon|null $s, Carbon|null $e)
    {
        $this->start = $s;
        $this->end = $e;
    }

    public function merge(StartEnd $other): StartEnd
    {
        return new StartEnd(
            $other->start ?? $this->start,
            $other->end ?? $this->end
        );
    }
}


class FormatData extends Command
{
    protected $signature = 'command:formatdata';
    protected $description = 'CommandFormatdata';


    private function getStartEndFromTouchHistory(Carbon $target_date, int $userid): StartEnd 
    {
        // 出勤(開始時間)・退勤時間(終了時間)を選出
        $start_time = null;
        $end_time = null;
        $user_touched_history_at_target_day = UserTouchHistory::where('user_id', $userid)->whereDate('touched_at', $target_date)->orderBy('touched_at')->get()->toArray();
        
        if (count($user_touched_history_at_target_day) >= 2){
            $start_time = Carbon::parse(reset($user_touched_history_at_target_day)['touched_at']);
            $end_time = carbon::parse(end($user_touched_history_at_target_day)['touched_at']);
        }

        return new StartEnd($start_time, $end_time);
    }

    private function getStartEndFromManualTime(Carbon $targetDate, int $userid): StartEnd 
    {
        $start_time = null;
        $end_time = null;
        $manual_time_start = ManualTime::where('user_id', 3)->where('date', $targetDate->toDateString())->where('start_or_end', 'start')->orderBy('created_at', 'desc')->first();

        if ($manual_time_start != null){
            $start_time = Carbon::parse($manual_time_start->date.' '.$manual_time_start->time);//$manual_time_start->time;
        }
        $manual_time_end = ManualTime::where('user_id', $userid)->where('date', $targetDate->toDateString())->where('start_or_end', 'end')->orderBy('created_at', 'desc')->first();
        if ($manual_time_end != null){
            $end_time = Carbon::parse($manual_time_end->date.' '.$manual_time_end->time);//$manual_time_end->time;
        }
            
        return new StartEnd($start_time, $end_time);
    }


    private function roundUp($start_time, $round_up_time = 15)
    {
        if ($start_time == null){
            return null;
        }
         // Carbonインスタンス作成
         $start_time_round_up = new Carbon($start_time);
         // 15分切り上げ
         $start_time_round_up = $start_time_round_up->addMinutes($round_up_time - $start_time_round_up->minute % $round_up_time)->format('H:i:00');
         return $start_time_round_up;
    }

    private function roundDown($end_time, $round_down_time = 15)
    {
        if ($end_time == null){
            return null;
        }
        // Carbonインスタンス作成
        $end_time_round_down = new Carbon($end_time);
        // 15分切り下げ
        $end_time_round_down = $end_time_round_down->subMinutes($end_time_round_down->minute % $round_down_time)->format('H:i:00'); 
        return $end_time_round_down;
    }



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
            $userTouchedHistory = $this->getStartEndFromTouchHistory($target_date, $user->id);
            $manualTime = $this->getStartEndFromManualTime($target_date, $user->id);
            $resultTime = $userTouchedHistory->merge($manualTime);

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
                    'original_start_time' => $resultTime->start, //$star_time, 
                    'original_end_time' => $resultTime->end, //$end_time, 
                    'round_up_start_time' => $this->roundUp($resultTime->start), 
                    'round_down_end_time' => $this->roundDown($resultTime->end),
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
