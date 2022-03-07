<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Common\Response;
use App\Http\Controllers\Controller;
use App\Models\Common\Common_account;
use App\Models\File\File_icon;
use App\Models\Profile\Profile_profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class VirtualController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $payloads=[];
        foreach(Common_account::where('UserId',$request->user()->id)->get() as $row){
            $payloads[$row['AccountId']]=Common_account::getAccountVirtualObject($row['id'],true);
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
        if(Common_account::where('UserId',$request->user()->id)->count()<6){//仮想アカウントは5つまで
            //受け取るパラメータ
            $receiveInput=["AccountId","VirtualName","DisplayName","IconId","message","birthday","BirthdayPublic"];
            //過剰パラメータの確認
            if($request->except($receiveInput)){
                return Response::error(406002);
            }
            //バリデーション
            $validator=Validator::make($request->input(),[
                "AccountId"=>["string","required","regex:/^[a-z0-9]{6,20}$/","unique:common_accounts"],
                "VirtualName"=>"string|required|max:50",
                "DisplayName"=>"string|required|max:50",
                "IconId"=>"string",
                "message"=>"string|nullable|max:250",
                "birthday"=>"date|nullable",
                "BirthdayPublic"=>"numeric|required|min:0,max:4",
            ]);
            if($validator->fails()){
                //バリデーションエラー
                return Response::error(406003,null,$validator->errors()->toArray());
            }else{
                //処理
                $account=new Common_account();
                $account->uid=Str::uuid();
                $account->UserId=$request->user()->id;
                $account->AccountId=$request->input('AccountId');
                $account->name=$request->input('VirtualName');
                if($account->save()){
                    $profile=new Profile_profile();
                    $profile->AccountId=$account['id'];
                    $profile->display_name=$request->input('DisplayName');
                    if($request->has('IconId')){
                        $profile->IconId=File_icon::where('IconId',$request->input('IconId'))->first()?->id;
                    }
                    $profile->message=$request->input('message')?:"";
                    $profile->birthday=$request->input('birthday');
                    $profile->birthday_public=$request->input('BirthdayPublic');
                    if($profile->save()){
                        return Response::generate(201,$account->getAccountVirtualObject($account->id));
                    }else{
                        $account->delete();
                        return Response::error(500002);
                    }
                }else{
                    return Response::error(500002);
                }
            }
        }else{
            return Response::error(403002);
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
        if($account=Common_account::where([['UserId',$request->user()->id],['AccountId',$id]])->first()){
            return Response::generate(200,Common_account::getAccountVirtualObject($account['id']));
        }else{
            return Response::error(404001);
        }
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
        if($account=Common_account::where([['UserId',$request->user()->id],['AccountId',$id]])->first()){
            //受け取るパラメータ
            $receiveInput=["AccountId","VirtualName","DisplayName","IconId","message","birthday","BirthdayPublic"];
            //過剰パラメータの確認
            if($request->except($receiveInput)){
                return Response::error(406002);
            }
            //バリデーション
            $validator=Validator::make($request->input(),[
                "AccountId"=>["string","required"],
                "VirtualName"=>"string|required|max:50",
                "DisplayName"=>"string|required|max:50",
                "IconId"=>"string",
                "message"=>"string|nullable|max:250",
                "birthday"=>"date|nullable",
                "BirthdayPublic"=>"numeric|required|min:0,max:4",
            ]);
            $error=$validator->errors()->toArray();
            if($id!=$request->input('AccountId')){
                $error[]='アカウントIDが一致しません。';
            }
            if(count($error)){
                //バリデーションエラー
                return Response::error(406003,null,$error);
            }else{
                //処理
                $account->name=$request->input('VirtualName');
                if($account->save()){
                    $profile=Profile_profile::where('AccountId',$account['id'])->first();
                    $profile->display_name=$request->input('DisplayName');
                    if($request->has('IconId')){
                        $profile->IconId=File_icon::where('IconId',$request->input('IconId'))->first()?->id;
                    }
                    $profile->message=$request->input('message')?:"";
                    $profile->birthday=$request->input('birthday');
                    $profile->birthday_public=$request->input('BirthdayPublic');
                    if($profile->save()){
                        return Response::generate(200,$account->getAccountVirtualObject($account->id));
                    }else{
                        return Response::error(500002);
                    }
                }else{
                    return Response::error(500002);
                }
            }
        }else{
            return Response::error(404001);
        }
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
        if($account=Common_account::where([['UserId',$request->user()->id],['AccountId',$id]])->first()){
            if(Common_account::where('UserId',$request->user()->id)->count()>1){
                if($account->delete()){
                    Profile_profile::where('AccountId',$account['id'])->delete();
                    return Response::generate(204);
                }else{
                    return Response::error(500002);
                }
            }else{
                return Response::error(403003,null,"全ての仮想アカウントを削除することはできません。");
            }
        }else{
            return Response::error(404001);
        }
    }
}
