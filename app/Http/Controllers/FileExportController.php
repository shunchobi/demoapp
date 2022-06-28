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

    const CSV_EXPORT_HEADER = [
        "id",
        'user_id', 
        'date', 
        'name', 
        'original_start_time', 
        'original_end_time', 
        'round_up_start_time', 
        'round_down_end_time',
        "created_at",
        "updated_at",
    ];

    public function download()
    {
        // ファイルヘッダーとなる文字列作成
        $header = collect(self::CSV_EXPORT_HEADER)->implode(",");
        // select句になる文字列作成
        $selectStr = collect(self::CSV_EXPORT_HEADER)->map(function($item) {
            return "ifnull({$item}, '')";
        })->implode(", ',' ,");
        // データの取得
        $users = DB::table('out_put_fromats')
        ->select(DB::raw("concat({$selectStr}) record"))
        ->pluck("record");
        $csv_data = $users->prepend($header)->implode("\r\n");
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="time_management.csv"',
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
