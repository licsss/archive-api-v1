<?php

namespace App\Models\Chat;

use App\Models\Common\Common_account;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat_friend extends Model
{
    use HasFactory;
    protected $casts=[
        "name"=>"encrypted",
        "tag"=>"json"
    ];

    static public function createFriendObject(Chat_friend $chat_friend):array
    {
        return [
            "FriendId"=>$chat_friend['FriendId'],
            "tag"=>$chat_friend['tag'],
            "status"=>$chat_friend['status'],
            "profile"=>Common_account::getProfileObject($chat_friend['Friend_AccountId'])
        ];
    }
    
    static public function getFriendObject(array $account):array
    {
        $payloads=[];
        foreach($account as $id){
            $payloads[$id['AccountId']]=[];
            foreach(parent::where('AccountId',$id['id'])->get() as $row){
                $payloads[$id['AccountId']][$row['FriendId']]=static::createFriendObject($row);
            }
        }

        return $payloads;
    }
}
