<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Common\Response;
use App\Http\Controllers\Controller;
use App\Models\Chat\Chat_member;
use App\Models\Chat\Chat_message;
use App\Models\Chat\Chat_room;
use App\Models\Common\Common_account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MessageController extends Controller
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
            foreach($this->account as $row){
                if(Chat_member::where([['RoomId',$room['id']],['AccountId',$row['id']]])->count()){
                    return Response::generate(200,Chat_message::getMessageListObject($room['id'],$request->input('page')?:1));
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
            foreach($this->account as $row){
                if(Chat_member::where([['RoomId',$room['id']],['AccountId',$row['id']]])->count()){
                    //受け取るパラメータ
                    $receiveInput=["type","content"];
                    //過剰パラメータの確認
                    if($request->except($receiveInput)){
                        return Response::error(406002);
                    }
                    //バリデーション
                    switch($request->input('type')?:0){
                        default:
                        case 0:
                            $content="string|required";
                            break;
                        case 1:
                            $content="array|required";
                            break;
                    }
                    $validator=Validator::make($request->input(),[
                        "type"=>"numeric|min:0|max:1",
                        "content"=>$content
                    ]);
                    if($validator->fails()){
                        //バリデーションエラー
                        return Response::error(406003,null,$validator->errors()->toArray());
                    }
                    $message=new Chat_message();
                    $message->MessageId=Str::uuid();
                    $message->RoomId=$room['id'];
                    $message->AccountId=$row['id'];
                    $message->type=$request->input('type');
                    switch($request->input('type')){
                        default:
                        case 0:
                            $message->message=$request->input('content');
                            break;
                        case 1:
                            $message->not_message=$request->input('content');
                            break;
                    }
                    $message->status=0;
                    if($message->save()){
                        return Response::generate(201,Chat_message::createMessageObject($message,$row['AccountId']));
                    }else{
                        return Response::error(500002);
                    }
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
    public function show(Request $request,$room,$message)
    {
        //
        if($room=Chat_room::where('RoomId',$room)->first()){
            foreach($this->account as $row){
                if(Chat_member::where([['RoomId',$room['id']],['AccountId',$row['id']]])->count()){
                    if($message=Chat_message::where([['MessageId',$message],['RoomId',$room['id']]])->first()){
                        return Response::generate(200,Chat_message::createMessageObject($message,$row['AccountId']));
                    }
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
    public function update(Request $request, $room,$message)
    {
        //
        if($room=Chat_room::where('RoomId',$room)->first()){
            foreach($this->account as $row){
                if(Chat_member::where([['RoomId',$room['id']],['AccountId',$row['id']]])->count()){
                    if($message=Chat_message::where([['MessageId',$message],['RoomId',$room['id']]])->first()){
                        //受け取るパラメータ
                        $receiveInput=["status"];
                        //過剰パラメータの確認
                        if($request->except($receiveInput)){
                            return Response::error(406002);
                        }
                        //バリデーション
                        $validator=Validator::make($request->input(),[
                            "status"=>"numeric|min:0|max:1"
                        ]);
                        if($validator->fails()){
                            //バリデーションエラー
                            return Response::error(406003,null,$validator->errors()->toArray());
                        }
                        $message->status=$request->input('status');
                        if($message->save()){
                            return Response::generate(200,Chat_message::createMessageObject($message,$row['AccountId']));
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
    public function destroy($room,$message)
    {
        //
        if($room=Chat_room::where('RoomId',$room)->first()){
            foreach($this->account as $row){
                if(Chat_member::where([['RoomId',$room['id']],['AccountId',$row['id']]])->count()){
                    if($message=Chat_message::where([['MessageId',$message],['RoomId',$room['id']]])->first()){
                        if($message->delete()){
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
