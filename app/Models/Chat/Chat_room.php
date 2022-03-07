<?php

namespace App\Models\Chat;

use App\Models\File\File_file;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat_room extends Model
{
    use HasFactory;
    protected $casts=[
        "name"=>"encrypted",
        "icon"=>"json",
        "message"=>"encrypted"
    ];

    static public function getRoomObject(array|int $roomId):array
    {
        $payloads=[];
        if(is_array($roomId)){
            foreach($roomId as $id){
                $room=static::find($id);
                $payloads[$room['RoomId']]=[
                    "RoomId"=>$room['RoomId'],
                    "RoomName"=>$room['name'],
                    "icon"=>File_file::getFileObject($room['icon']),
                    "message"=>$room['message'],
                    "type"=>(int)$room['type'],
                    "members"=>Chat_member::getRoomMemberObject($room['id'])
                ];
            }
        }else{
            $room=static::find($roomId);
            $payloads=[
                "RoomId"=>$room['RoomId'],
                "RoomName"=>$room['name'],
                "icon"=>File_file::getFileObject($room['icon']),
                "message"=>$room['message'],
                "type"=>(int)$room['type'],
                "members"=>[
                    "member"=>Chat_member::getMemberObject([['status',0],['RoomId',$room['id']]]),
                    "invited"=>Chat_member::getMemberObject([['status',1],['RoomId',$room['id']]])
                ]
            ];   
        }
        return $payloads;
    }
}
