<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $casts=[
        "email_verified_at"=>"immutable_datetime",
        "name"=>"encrypted",
        "UserName"=>"encrypted"
    ];

    static public function getAccountObject(int $id){
        return parent::select(['UserId','name as UserName','email','tel'])->find($id)->toArray();
    }
}
