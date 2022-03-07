<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Common\Response;
use App\Http\Controllers\Controller;
use App\Models\Common\Common_account;
use App\Models\Profile\Profile_profile;
use App\Models\User as ModelsUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;

class User extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $payloads=[
            'users'=>ModelsUser::select(['UserId','name as UserName','email','tel'])->find($request->user()->id),
            'accounts'=>Common_account::select(['AccountId','name as AccountName'])->where('UserId',$request->user()->id)->get()
        ];
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
        $receiveInput=["AccountId","UserName","email","tel","password","password_confirmation"];
        //過剰パラメータの確認
        if($request->except($receiveInput)){
            return Response::error(406002);
        }
        //バリデーション
        $validator=Validator::make($request->input(),[
            "AccountId"=>["string","required","regex:/^[a-zA-Z0-9]{6,20}$/","unique:common_accounts"],
            "UserName"=>"string|max:50|required",
            "email"=>"required|email|max:100|unique:users",
            "tel"=>"required|string|min:11|max:11|unique:users",
            "password"=>["required","confirmed",Password::min(12)->mixedCase()->numbers()->symbols()],
        ]);
        if($validator->fails()){
            //バリデーションエラー
            return Response::error(406003,null,$validator->errors()->toArray());
        }else{
            //処理
            $user=new ModelsUser();
            $user->UserId=Str::uuid();
            $user->name=$request->input('UserName');
            $user->email=$request->input('email');
            $user->tel=$request->input('tel');
            $user->password=Hash::make($request->input('password'));
            if($user->save()){
                //account処理
                $account=new Common_account();
                $account->uid=Str::uuid();
                $account->UserId=$user->id;
                $account->AccountId=$request->input('AccountId');
                $account->name="@".$request->input('AccountId');
                if($account->save()){
                    //profile
                    $profile=new Profile_profile();
                    $profile->AccountId=$account['id'];
                    $profile->display_name=$account['name'];
                    $profile->message="";
                    if($profile->save()){
                        //アカウント作成メール
                        return Response::generate(201);
                    }else{
                        $user->delete();
                        $account->delete();
                        return Response::error(500002);
                    }
                }else{
                    $user->delete();
                    return Response::error(500002);
                }
            }else{
                return Response::error(500002);
            }
        }
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
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        //
        if($request->user()->UserId==$id){
            //受け取るパラメータ
            $receiveInput=["UserName","email","tel"];
            //過剰パラメータの確認
            if($request->except($receiveInput)){
                return Response::error(406002);
            }
            //バリデーション
            $validator=Validator::make($request->input(),[
                "UserName"=>"string|max:50|required",
                "email"=>"required|email|max:100|unique:users,email,{$request->user()->id},id",
                "tel"=>"required|string|min:11|max:11|unique:users,tel,{$request->user()->id},id",
            ]);
            if($validator->fails()){
                //バリデーションエラー
                return Response::error(406003,null,$validator->errors()->toArray());
            }else{
                //処理
                $user=ModelsUser::find($request->user()->id);
                $user->name=$request->input('UserName');
                $user->email=$request->input('email');
                $user->tel=$request->input('tel');
                if($user->save()){
                    return Response::generate(200,[
                        "UserName"=>$user->name,
                        "email"=>$user->email,
                        "tel"=>$user->tel
                    ]);
                }else{
                    return Response::error(500002);
                }
            }
        }else{
            return Response::error(403001);
        }
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
        return Response::error(503000);
    }
}
