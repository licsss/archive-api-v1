<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Common\Response;
use App\Http\Controllers\Controller;
use App\Mail\Auth\PasswordReset as AuthPasswordReset;
use App\Models\Auth\Password_reset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class PasswordReset extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $receiveInput=['email'];
        //過剰パラメータの確認
        if($request->except($receiveInput)){
            return Response::error(406002);
        }
        //バリデーション
        $validator=Validator::make($request->input(),[
            "email"=>"email|required",
        ]);
        if($validator->fails()){
            //バリデーションエラー
            return Response::error(406003,null,$validator->errors()->toArray());
        }else{
            //処理
            //ユーザーの取得
            if($user=User::where('email',$request->input('email'))->orWhere('tel',$request->input('email'))->first()){
                $deadline=new Carbon('+ 10minutes');//10分期限
                $reset=new Password_reset();
                $reset->ResetId=Str::uuid();
                $reset->UserId=$user['id'];
                $reset->deadline=$deadline;
                if($reset->save()){
                    //リセットメール送信
                    Mail::send(new AuthPasswordReset($user['name'],$user['email'],$reset->ResetId));
                    return Response::generate(201);
                }else{
                    return Response::error(500002);
                }
            }else{
                return Response::error(404001);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Auth\Password_reset  $password_reset
     * @return \Illuminate\Http\Response
     */
    public function show($resetId)
    {
        if($reset=Password_reset::where('ResetId',$resetId)->first()){
            if($reset['deadline']<Carbon::now()){
                $response=Response::generate(200,["available"=>false]);
            }else{
                $response=Response::generate(200,['available'=>true]);
            }
            Password_reset::where('deadline','<',Carbon::now())->delete();
            return $response;
        }else{
            return Response::error(404001);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Auth\Password_reset  $password_reset
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $resetId)
    {
        //受け取るパラメータ
        $receiveInput=['password','password_confirmation'];
        //過剰パラメータの確認
        if($request->except($receiveInput)){
            return Response::error(406002);
        }
        if($reset=Password_reset::where('ResetId',$resetId)->first()){
            if($reset['deadline']<Carbon::now()){
                $response=Response::generate(200,["available"=>false]);
            }else{
                //バリデーション
                $validator=Validator::make($request->input(),[
                    "password"=>["required","confirmed",Password::min(12)->mixedCase()->numbers()->symbols()]
                ]);
                if($validator->fails()){
                    //バリデーションエラー
                    $response=Response::error(406003,null,$validator->errors()->toArray());
                }else{
                    //処理
                    if($user=User::find($reset['UserId'])){
                        $user->password=Hash::make($request->input('password'));
                        if($user->save()){
                            $response=Response::generate(200);
                        }else{
                            $response=Response::error(500002);
                        }
                    }else{
                        $response=Response::error(404001);
                    }
                }
            }
            Password_reset::where('deadline','<',Carbon::now())->delete();
            return $response;
        }else{
            return Response::error(404001);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Auth\Password_reset  $password_reset
     * @return \Illuminate\Http\Response
     */
    public function destroy($resetId)
    {
        if(Password_reset::where('ResetId',$resetId)->first()->delete()){
            return Response::generate(204);
        }else{
            return Response::error(404001);
        }
    }
}
