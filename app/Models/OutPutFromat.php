<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutPutFromat extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id', 
        'date', 
        'name', 
        'original_start_time', 
        'original_end_time', 
        'round_up_start_time', 
        'round_down_end_time', 
        
    ];



}
