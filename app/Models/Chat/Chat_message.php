<?php

namespace App\Models\Chat;

use App\Models\Common\Common_account;
use App\Models\File\File_file;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat_message extends Model
{
    use HasFactory;
    protected $casts=[
        "message"=>"encrypted",
        "not_message"=>"json",
        //"created_at"=>"immutable_datetime:Y-m-d H:i:s"
    ];

    static public function createMessageObject(Chat_message $chat_message,string|null $account=null){
        if($account==null){
            $account=Common_account::find($chat_message['AccountId'])->AccountId;
        }
        $content="";
        switch((int)$chat_message['type']){
            case 0:
            default:
                $content=$chat_message['message'];
                break;
            case 1:
                $content=File_file::getFileObject($chat_message['not_message']);
                break;
        }
        $payloads=[
            'MessageId'=>$chat_message['MessageId'],
            'message'=>[
                "type"=>$chat_message['type'],
                "content"=>$content
            ],
            'created'=>$chat_message['created_at']->format('Y-m-d H:i:s'),
            "status"=>$chat_message['status'],
            "SendUser"=>$account
        ];
        return $payloads;
    }

    static public function getMessageListObject(int $roomId,int $page=1):array
    {
        $payloads=[];
        $offset=($page-1)*100;
        $limit=$page*100;
        $accounts=[];
        foreach(parent::where([['RoomId',$roomId],['status','<>',2]])->orderBy('created_at','desc')->offset($offset)->limit($limit)->get() as $row){
            if(!isset($accounts[$row['AccountId']])){
                $accounts[$row['AccountId']]=Common_account::find($row['AccountId'])->AccountId;
            }
            $payloads[$row['MessageId']]=static::createMessageObject($row,$accounts[$row['AccountId']]);
        }

        return $payloads;
    }
}
