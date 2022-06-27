<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\User;
use App\Models\UserTouchHistory;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class ApiController extends Controller
{


    public function store(Request $request)
    {
        // 登録済み -> 1 件レコードが返る
        // 未登録 -> NULL
        $card = Card::with('user')->where('idm', $request->idm)->first();

        if(empty($card)){
            $card = Card::create([
                'idm' => $request->idm,
                'user_id' => null,
            ]);
        }
        
        $history = UserTouchHistory::create([
            'user_id' => $card->user_id,
            'card_id' => $card->id,
            'touched_at' => now(),
        ]);

        return response()->json($history);
    }




}

