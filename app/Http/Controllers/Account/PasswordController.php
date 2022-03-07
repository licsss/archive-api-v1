<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Common\Response;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
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
        //
        //受け取るパラメータ
        $receiveInput=["password"];
        //過剰パラメータの確認
        if($request->except($receiveInput)){
            return Response::error(406002);
        }
        //バリデーション
        $validator=Validator::make($request->input(),[
            "password"=>["required"],
        ]);
        if($validator->fails()){
            //バリデーションエラー
            return Response::error(406003,null,$validator->errors()->toArray());
        }else{
            //処理
            if(Hash::check($request->input('password'),$request->user()->password)){
                return Response::generate(200);
            }else{
                return Response::error(401002);
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
    public function update(Request $request, $id)
    {
        if($request->user()->UserId==$id){
            //受け取るパラメータ
            $receiveInput=["current_password","password","password_confirmation"];
            //過剰パラメータの確認
            if($request->except($receiveInput)){
                return Response::error(406002);
            }
            //バリデーション
            $validator=Validator::make($request->input(),[
                "current_password"=>"required",
                "password"=>["required","confirmed",Password::min(12)->mixedCase()->numbers()->symbols()],
            ]);
            if($validator->fails()){
                //バリデーションエラー
                return Response::error(406003,null,$validator->errors()->toArray());
            }else{
                //処理
                $user=User::find($request->user()->id);
                if(Hash::check($request->input('current_password'),$user['password'])){
                    $user->password=Hash::make($request->input('password'));
                    if($user->save()){
                        return Response::generate(200);
                    }else{
                        return Response::error(500002);
                    }
                }else{
                    return Response::error(401002,null,"現在のパスワードを確認してください。");
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
    }
}
