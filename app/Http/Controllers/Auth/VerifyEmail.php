<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Common\Response;
use App\Http\Controllers\Controller;
use App\Mail\auth\VerifiedEmail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class VerifyEmail extends Controller
{
    //
    public function store(Request $request){
        //受け取るパラメータ
        $receiveInput=['email',"password"];
        //過剰パラメータの確認
        if($request->except($receiveInput)){
            return Response::error(406002);
        }
        //バリデーション
        $validator=Validator::make($request->input(),[
            "email"=>"email|required",
            "password"=>"string|required"
        ]);
        if($validator->fails()){
            //バリデーションエラー
            return Response::error(406003,null,$validator->errors()->toArray());
        }else{
            //処理
            //ユーザーの取得
            if($user=User::where('email',$request->input('email'))->orWhere('tel',$request->input('email'))->first()){
                if(Hash::check($request->input('password'),$user['password'])){
                    $user->email_verified_at=Carbon::now();
                    if($user->save()){
                        //アカウント登録完了メール
                        Mail::send(new VerifiedEmail($user['email'],$user['name']));
                        return Response::generate(201);
                    }else{
                        return Response::error(500002);
                    }
                }else{
                    return Response::error(401001);
                }
            }else{
                return Response::error(404001);
            }
        }
    }
}
