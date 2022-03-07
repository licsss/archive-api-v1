<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Common_api extends Model
{
    use HasFactory;
    protected $casts=[
        'head'=>'json',
        'params'=>'json',
        "posts"=>"json",
        "result"=>"json",
        "error_message"=>"json",
    ];
}
