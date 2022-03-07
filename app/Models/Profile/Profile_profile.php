<?php

namespace App\Models\Profile;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile_profile extends Model
{
    use HasFactory;
    protected $casts=[
        "display_name"=>"encrypted",
        "icons"=>"json",
        "birthday"=>"immutable_date",
        "message"=>"encrypted",
    ];
}
