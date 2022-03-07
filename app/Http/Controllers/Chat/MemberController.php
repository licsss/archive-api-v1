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

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$room)
    {
        //
        if($room=Chat_room::where('RoomId',$room)->first()){
            foreach(Common_account::where('UserId',$request->user()->id)->get() as $row){
                if(Chat_member::where([['RoomId',$room['id']],['AccountId',$row['id']]])->count()){
                    return Response::generate(200,Chat_member::getRoomMemberObject($room['id']));
                }
            }
        }
        return Response::error(404001);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$room)
    {
        //
        if($room=Chat_room::where('RoomId',$room)->first()){
            foreach(Common_account::where('UserId',$request->user()->id)->get() as $row){
                if(Chat_member::where([['RoomId',$room['id']],['AccountId',$row['id']]])->count()){
                    //受け取るパラメータ
                    $receiveInput=["member","invited"];
                    //過剰パラメータの確認
                    if($request->except($receiveInput)){
                        return Response::error(406002);
                    }
                    //バリデーション
                    $validator=Validator::make($request->input(),[
                        "member"=>"array",
                        "invited"=>"array"
                    ]);
                    if($validator->fails()){
                        //バリデーションエラー
                        return Response::error(406003,null,$validator->errors()->toArray());
                    }
                    foreach($request->input('member') as $val){
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
                    foreach($request->input('invited') as $val){
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
                    return Response::generate(200,Chat_member::getRoomMemberObject($room['id']));
                }
            }
        }
        return Response::error(404001);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$room,$id)
    {
        //
        if($room=Chat_room::where('RoomId',$room)->first()){
            if($member=Chat_member::where('MemberId',$id)->first()){
                return Response::generate(200,array_merge(
                    Common_account::getProfileObject($member['AccountId']),
                    ['status'=>$member['status']]
                ));
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
    public function update(Request $request, $room, $id)
    {
        //
        if($room=Chat_room::where('RoomId',$room)->first()){
            foreach(Common_account::where('UserId',$request->user()->id)->get() as $row){
                if(Chat_member::where([['RoomId',$room['id']],['AccountId',$row['id']]])->count()){
                    if($member=Chat_member::where('MemberId',$id)->first()){
                        //受け取るパラメータ
                        $receiveInput=["status"];
                        //過剰パラメータの確認
                        if($request->except($receiveInput)){
                            return Response::error(406002);
                        }
                        //バリデーション
                        $validator=Validator::make($request->input(),[
                            "status"=>"numeric|min:0|max:1",
                        ]);
                        if($validator->fails()){
                            //バリデーションエラー
                            return Response::error(406003,null,$validator->errors()->toArray());
                        }
                        $member->status=$request->input('status');
                        if($member->save()){
                            return Response::generate(200,array_merge(
                                Common_account::getProfileObject($member['AccountId']),
                                ['status'=>$member['status']]
                            ));
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
    public function destroy(Request $request, $room, $id)
    {
        //
        if($room=Chat_room::where('RoomId',$room)->first()){
            foreach(Common_account::where('UserId',$request->user()->id)->get() as $row){
                if(Chat_member::where([['RoomId',$room['id']],['AccountId',$row['id']]])->count()){
                    if($member=Chat_member::where('MemberId',$id)->first()){
                        if($member->delete()){
                            return Response::generate(204);
                        }else{
                            return Response::error(500002);
                        }
                    }
                }
            }
        }
        return Response::error(404001);
    }
}
