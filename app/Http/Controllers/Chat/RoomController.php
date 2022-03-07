<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Common\Response;
use App\Http\Controllers\Controller;
use App\Models\Chat\Chat_member;
use App\Models\Chat\Chat_room;
use App\Models\Common\Common_account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $payloads=[];
        foreach(Common_account::where('UserId',$request->user()->id)->get() as $row){
            $payloads[$row['AccountId']]['participated']=Chat_room::getRoomObject(array_column(
                Chat_member::select(['RoomId'])->where([['AccountId',$row['id']],['status',0]])->get()->toArray(),
                'RoomId'
            ));
            $payloads[$row['AccountId']]['invited']=Chat_room::getRoomObject(array_column(
                Chat_member::select(['RoomId'])->where([['AccountId',$row['id']],['status',1]])->get()->toArray(),
                'RoomId'
            ));
        }
        return Response::generate(200,$payloads);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //受け取るパラメータ
        $receiveInput=["RoomName","icon","message","RoomType","members"];
        //過剰パラメータの確認
        if($request->except($receiveInput)){
            return Response::error(406002);
        }
        //バリデーション
        $validator=Validator::make($request->input(),[
            "RoomName"=>"string|required|max:50",
            "icon"=>"array",
            "message"=>"string|nullable|max:100",
            "RoomType"=>"numeric|required|min:0|max:2",
            "members"=>"array|required",
            "members.member"=>"array|required",
            "members.invited"=>"array|required"
        ]);
        if($validator->fails()){
            //バリデーションエラー
            return Response::error(406003,null,$validator->errors()->toArray());
        }else{
            //処理
            $room=$this->create($request->input());
            if($room){
                return Response::generate(201,Chat_room::getRoomObject($room['id']));
            }else{
                return Response::error(500002);
            }
        }
    }
    public function create($input){
        //処理
        $room=new Chat_room();
        $room->RoomId=Str::uuid();
        $room->name=$input['RoomName'];
        $room->icon=$input['icon'];
        $room->message=$input['message'];
        $room->type=$input['RoomType'];
        if($room->save()){
            //メンバー登録
            foreach($input['members']['member'] as $val){
                if($account=Common_account::where('AccountId',$val)->first()){
                    $member=new Chat_member();
                    $member->MemberId=Str::uuid();
                    $member->RoomId=$room['id'];
                    $member->AccountId=$account['id'];
                    $member->status=0;
                    $member->save();
                }
            }
            foreach($input['members']['invited'] as $val){
                if($account=Common_account::where('AccountId',$val)->first()){
                    $member=new Chat_member();
                    $member->MemberId=Str::uuid();
                    $member->RoomId=$room['id'];
                    $member->AccountId=$account['id'];
                    $member->status=1;
                    $member->save();
                }
            }
            return $room;
        }else{
            return false;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        //
        if($room=Chat_room::where('RoomId',$id)->first()){
            foreach(Common_account::where('UserId',$request->user()->id) as $account){
                if(Chat_member::where([['AccountId',$account['id']],['status','<',2]])->count()){
                    return Response::generate(200,Chat_room::getRoomObject($room['id']));
                    break;
                }
            }
        }
        return Response::error(404001);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        if($room=Chat_room::where('RoomId',$id)->first()){
            foreach(Common_account::where('UserId',$request->user()->id)->get() as $row){
                if($member=Chat_member::where([['RoomId',$room['id']],['AccountId',$row['id']]])->first()){
                    //受け取るパラメータ
                    $receiveInput=["RoomName","icon","message","RoomType","members"];
                    //過剰パラメータの確認
                    if($request->except($receiveInput)){
                        return Response::error(406002);
                    }
                    //バリデーション
                    $validator=Validator::make($request->input(),[
                        "RoomName"=>"string|required|max:50",
                        "icon"=>"array",
                        "message"=>"string|nullable|max:100",
                        "RoomType"=>"numeric|required|min:0|max:2",
                        "members"=>"array|required",
                        "members.member"=>"array|required",
                        "members.invited"=>"array|required"
                    ]);
                    if($validator->fails()){
                        //バリデーションエラー
                        return Response::error(406003,null,$validator->errors()->toArray());
                    }else{
                        //処理
                        $room->name=$request->input('RoomName');
                        $room->icon=$request->input('icon');
                        $room->message=$request->input('message');
                        $room->type=$request->input('RoomType');
                        if($room->save()){
                            //メンバー登録
                            foreach($request->input('members')['member'] as $val){
                                if($account=Common_account::where('AccountId',$val)->first()){
                                    if(Chat_member::where([['RoomId',$room['id']],['AccountId',$account['id']]])->count()==0){
                                        $member=new Chat_member();
                                        $member->MemberId=Str::uuid();
                                        $member->RoomId=$room['id'];
                                        $member->AccountId=$account['id'];
                                        $member->status=0;
                                        $member->save();
                                    }
                                }
                            }
                            foreach($request->input('members')['invited'] as $val){
                                if($account=Common_account::where('AccountId',$val)->first()){
                                    if(Chat_member::where([['RoomId',$room['id']],['AccountId',$account['id']]])->count()==0){
                                        $member=new Chat_member();
                                        $member->MemberId=Str::uuid();
                                        $member->RoomId=$room['id'];
                                        $member->AccountId=$account['id'];
                                        $member->status=1;
                                        $member->save();
                                    }
                                }
                            }
                            return Response::generate(200,Chat_room::getRoomObject($room['id']));
                        }else{
                            return Response::error(500002);
                        }
                    }
                }
            }
        }
        return Response::error(404001);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        //
        if($room=Chat_room::where('RoomId',$id)->first()){
            foreach(Common_account::where('UserId',$request->user()->id)->get() as $row){
                if(Chat_member::where([['RoomId',$room['id']],['AccountId',$row['id']]])->first()){
                    if($room->delete()){
                        Chat_member::where('RoomId',$room['id'])->delete();
                        return Response::generate(204);
                    }else{
                        return Response::error(500002);
                    }
                }
            }
        }
        return Response::error(404001);
    }
}
