<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\ManualTime;
use App\Models\OutPutFormatYearMonth;
use App\Models\OutPutFromat;
use App\Models\User;
use App\Models\UserTouchHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class CreateUserController extends Controller
{

  
    
    public function showManagementPage()
    {
        $non_registered_cards = Card::where('user_id', null)->get();
        $users = User::get();
        $exist_y_m = OutPutFormatYearMonth::select('year_month')->get();
        $start_end = [1 => "出勤", 2 => "退勤"];

        return view('management', 
        [
            'non_registered_cards' => $non_registered_cards, 
            'users' => $users, 
            'exist_y_m' => $exist_y_m,
            'start_end'=> $start_end,
        ]);
    }



    public function createManualStartEndTime(Request $request)
    {
        $user_id = $request->user_id;
        // $date = mb_substr($request->datetime, 0, 10); // 2022-06-29T15:41
        $date = Carbon::parse($request->datetime)->format('Y-m-d');
        $time =  Carbon::parse($request->datetime)->format('H:i:00');
        $start_or_end = $request->start_or_end;
        $start_or_end = $start_or_end == 1 ? 'start' : 'end';

        ManualTime::create([
            'user_id' => $user_id,
            'date' => $date,
            'time' => $time,
            'start_or_end' => $start_or_end,
        ]);

        return redirect()->route('management');
    }



    public function createUser(Request $request)
    {
        User::create([
            'name' => $request->new_user_name,
        ]);

        return redirect()->route('management');
    }
    

    
    public function updateUserId(Request $request)
    {
        for ($i=0; $i < count($request->card_id); $i++) { 
            
            $target_card = Card::where('id', $request->card_id[$i])->first();
            $target_card->user_id = $request->selected_user_id[$i];
            $target_card->save();

            UserTouchHistory::where('user_id', null)->where('card_id', $target_card->id)->update([
                'user_id' => $target_card->user_id,
            ]);
        }   
        
        return redirect()->route('management');
    }
}
