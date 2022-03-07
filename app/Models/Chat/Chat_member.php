<?php

namespace App\Models\Chat;

use App\Models\Common\Common_account;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat_member extends Model
{
    use HasFactory;
    protected $casts=[];

    static public function getRoomMemberObject(array|int $roomId):array
    {
        $payloads=[];
        if(is_array($roomId)){
            foreach($roomId as $key=>$val){
                $payloads[$key]=[
                    'member'=>[],
                    'invited'=>[]
                ];
                foreach(Chat_member::where([['RoomId',$val],['status',0]])->get() as $row){
                    $payloads[$key]['member'][$row['MemberId']]=Common_account::getProfileObject($row['AccountId']);
                }
                foreach(Chat_member::where([['RoomId',$val],['status',1]])->get() as $row){
                    $payloads[$key]['invited'][$row['MemberId']]=Common_account::getProfileObject($row['AccountId']);
                }
            }
        }else{
            $payloads=[
                'member'=>[],
                'invited'=>[]
            ];
            foreach(Chat_member::where([['RoomId',$roomId],['status',0]])->get() as $row){
                $payloads['member'][$row['MemberId']]=Common_account::getProfileObject($row['AccountId']);
            }
            foreach(Chat_member::where([['RoomId',$roomId],['status',1]])->get() as $row){
                $payloads['invited'][$row['MemberId']]=Common_account::getProfileObject($row['AccountId']);
            }
        }
        return $payloads;
    }

    static public function getMemberObject(array $where=[],int|array $id=null):array
    {
        $payloads=[];
        if(!empty($id)){
            if(is_array($id)){
                foreach($id as $val){
                    $member=static::find($val);
                    $payloads[$member['MemberId']]=Common_account::getProfileObject($member['AccountId']);
                }
            }else{
                $payloads=Common_account::getProfileObject(static::find($id)->AccountId);
            }
        }else{
            foreach(Chat_member::where($where)->get() as $row){
                $payloads[]=Common_account::getProfileObject($row['AccountId']);
            }
        }
        return $payloads;
    }
}
