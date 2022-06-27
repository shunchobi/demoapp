<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTouchHistory extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'card_id', 'touched_at'];



    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function cards()
    {
        return $this->belongsTo(Card::class);
    }
}
