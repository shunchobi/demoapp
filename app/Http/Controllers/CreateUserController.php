<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\OutPutFormatYearMonth;
use App\Models\OutPutFromat;
use App\Models\User;
use App\Models\UserTouchHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class CreateUserController extends Controller
{
    
    // 未登録のUserを作るためのviewを返す
    public function showManagementPage()
    {
        $non_registered_cards = Card::where('user_id', null)->get();
        $users = User::get();
        $exist_y_m = OutPutFormatYearMonth::select('year_month')->get();

        return view('management', ['non_registered_cards' => $non_registered_cards, 'users' => $users, 'exist_y_m' => $exist_y_m]);
    }


    // 新しいUserの情報を受け取り新しいUserレコードを作る
    public function store(Request $request)
    {
        // card_id[]  selected_user_id[]

        for ($i=0; $i < count($request->card_id); $i++) { 
            $target_card = Card::where('id', $request->card_id[$i])->first();
            $target_card->user_id = $request->selected_user_id[$i];
            $target_card->save();

            // $non_user_touched_at = UserTouchHistory::where('user_id', null)->where('card_id', $target_card->id)->get();
            // if(count($non_user_touched_at) > 0){
            //     foreach ($non_user_touched_at as $value) { 
            //         $value->user_id = $target_card->user_id;
            //         $value->save();
            //     }
            // }

            UserTouchHistory::where('user_id', null)->where('card_id', $target_card->id)->update([
                'user_id' => $target_card->user_id,
            ]);
        }        

    }
}
