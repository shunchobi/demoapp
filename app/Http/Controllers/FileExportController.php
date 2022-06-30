<?php

namespace App\Http\Controllers;

use App\Models\OutPutFromat;
use Illuminate\Http\Request;

use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class FileExportController extends Controller
{

    
        // '日付', 
        // '名前', 
        // '出勤時間', 
        // '退勤時間', 
        // '出勤時間（15分丸め）', 
        // '退勤時間（15分丸め）',

    const CSV_EXPORT_HEADER = [
        // "id",
        // 'user_id', 
        'date', 
        'name', 
        'original_start_time', 
        'original_end_time', 
        'round_up_start_time', 
        'round_down_end_time',
        // "created_at",
        // "updated_at",
    ];


    public function download(Request $request)
    {
        $request->validate([
            'selected_y_m' => 'required'
        ]);

        // 指定された取得したい年月をRequestで受け取り、値を定義
        $target_export_y_m = $request->selected_y_m;

        // ファイルヘッダーとなる文字列作成
        $header = collect(self::CSV_EXPORT_HEADER)->implode(",");

        // select句になる文字列作成
        $selectStr = collect(self::CSV_EXPORT_HEADER)->map(function($item) {
            return "ifnull({$item}, '')";
        })->implode(", ',' ,");
        
        // データの取得
        $target_out_put_fromat = OutPutFromat::where('date', 'Like', $target_export_y_m.'%')
        ->select(DB::raw("concat({$selectStr}) record"))
        ->pluck("record");
        
        $csv_data = $target_out_put_fromat->prepend($header)->implode("\r\n");
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename= $target_export_y_m 出退勤表.csv",
        ]; 

        return response()->make($csv_data, 200, $headers);


        // ヘッダーとデータを加えて改行コードでつなげて１つの文字列にする
        // return $users->prepend($header)->implode("\r\n");
    }



    // public function download(Request $request){
    //     $filename = 'sample.csv';

    //     $export_data = OutPutFromat::all();
    //     $csv = "OutPutFromat \n";
    //     foreach ($export_data as $value) {
    //       $csv .= $value->id;
    //       $csv .= "\n";
    //     }

    //     $headers = [
    //         'Content-Type' => 'text/csv',
    //         'Content-Disposition' => 'attachment; filename="time_management.csv"',
    //     ]; 

    //     return response()->make($csv, 200, $headers);
    // }


}
