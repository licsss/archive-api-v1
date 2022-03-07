<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Common\Response;
use App\Http\Controllers\Controller;
use App\Models\Chat\Chat_friend;
use App\Models\Common\Common_account;
use App\Models\Profile\Profile_profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FriendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Response::generate(200,Chat_friend::getFriendObject($this->account));
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
        $receiveInput=["AccountId","Friend_AccountId","tag","name"];
        //過剰パラメータの確認
        if($request->except($receiveInput)){
            return Response::error(406002);
        }
        //バリデーション
        $validator=Validator::make($request->input(),[
            "AccountId"=>"string|required",
            "Friend_AccountId"=>"string|required",
            "name"=>"string|required",
            "tag"=>"array",
        ]);
        if($validator->fails()){
            //バリデーションエラー
            return Response::error(406003,null,$validator->errors()->toArray());
        }
        if($account=Common_account::where([['AccountId',$request->input('AccountId')],['UserId',$request->user()->id]])->first()){
            if($friend_account=Common_account::where([['AccountId',$request->input('Friend_AccountId')]])->first()){
                if(!$friend=Chat_friend::where([['AccountId',$account['id']],['Friend_AccountId',$friend_account['id']]])->first()){
                    //ルーム作成
                    $input=[
                        "RoomName"=>$request->input('name'),
                        "icon"=>[],
                        "message"=>"",
                        "RoomType"=>0,
                        "members"=>[
                            'member'=>[
                                $request->input('AccountId'),
                                $request->input('Friend_AccountId'),
                            ],
                            'invited'=>[]
                        ]
                    ];
                    $room=new RoomController($this->authenticated);
                    $room=$room->create($input);

                    //フレンド登録
                    $friend=new Chat_friend();
                    $friend->FriendId=Str::uuid();
                    $friend->RoomId=$room?$room['id']:0;
                    $friend->AccountId=$account['id'];
                    $friend->Friend_AccountId=$friend_account['id'];
                }
                $friend->name=$request->input('name');
                $friend->tag=$request->input('tag');
                $friend->status=0;
                if($friend->save()){
                    if(!Chat_friend::where([['Friend_AccountId',$account['id']],['AccountId',$friend_account['id']]])->first()){
                        $newfriend=new Chat_friend();
                        $newfriend->FriendId=Str::uuid();
                        $newfriend->RoomId=$room?$room['id']:0;
                        $newfriend->Friend_AccountId=$account['id'];
                        $newfriend->AccountId=$friend_account['id'];
                        $newfriend->name=Profile_profile::where('AccountId',$account['id'])->first()->display_name;
                        $newfriend->tag=$request->input('tag');
                        $newfriend->status=1;
                        if($newfriend->save()){
                            return Response::generate(201,Chat_friend::createFriendObject($friend));
                        }else{
                            return Response::error(500002);
                        }
                    }else{
                        return Response::generate(201,Chat_friend::createFriendObject($friend));
                    }
                }else{
                    return Response::error(500002);
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
    public function show($id)
    {
        //
        foreach($this->account as $row){
            if($friend=Chat_friend::where([['FriendId',$id],['AccountId',$row['id']]])->first()){
                return Response::generate(200,Chat_friend::createFriendObject($friend));
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
        //受け取るパラメータ
        $receiveInput=["tag","name","status"];
        //過剰パラメータの確認
        if($request->except($receiveInput)){
            return Response::error(406002);
        }
        //バリデーション
        $validator=Validator::make($request->input(),[
            "name"=>"string|required",
            "tag"=>"array",
            "status"=>"numeric|required"
        ]);
        if($validator->fails()){
            //バリデーションエラー
            return Response::error(406003,null,$validator->errors()->toArray());
        }
        foreach($this->account as $row){
            if($friend=Chat_friend::where([['AccountId',$row['id']],['FriendId',$id]])->first()){
                $friend->name=$request->input('name');
                $friend->tag=$request->input('tag');
                $friend->status=$request->input('status');
                if($friend->save()){
                    return Response::generate(200,Chat_friend::createFriendObject($friend));
                }else{
                    return Response::error(500002);
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
    public function destroy($id)
    {
        //
        foreach($this->account as $row){
            if($friend=Chat_friend::where([['AccountId',$row['id']],['FriendId',$id]])->first()){
                if($friend->delete()){
                    return Response::generate(204);
                }else{
                    return Response::error(500002);
                }
            }
        }
        return Response::error(404001);
    }
}
