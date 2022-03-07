<?php

namespace App\Http\Middleware\original;

use App\Http\Controllers\Common\Funcs;
use App\Http\Controllers\Common\Response;
use App\Models\Common\Common_account;
use App\Models\Common\Common_token;
use App\Models\User;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class auth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try{
            if(empty($request->header('app'))){
                return Response::error(406004);
            }
            //logも同じ
            $apps=["spa",'android','ios'];
            if(!in_array($request->header('app'),$apps)){
                return Response::error(406007);
            }



            $user=false;
            if($request->has('grant_type') && $request->input('grant_type')=='password'){
                //認証ユーザー取得
                if($user=User::select(['id','UserId','name as UserName','email','email_verified_at'])->where('email',$request->input('email'))->orWhere('tel',$request->input('email'))->first()){
                    if(empty($user['email_verified_at'])){
                        return Response::error(406006);
                    }
                    $request->merge([
                        "username"=>$user['email']
                    ]);
                }else{
                    $request->merge([
                        "username"=>""
                    ]);
                }
            }

            //処理開始
            $response=$next($request);

            $result=[];
            $content=json_decode($response->original,true);
            if(isset($content['access_token'])){
                //ログインorReferesh
                $result['auth']=$content;
                //csrfトークン生成
                $token=new Common_token();
                $token->UserId=$user['id'];
                $token->save();
                $result['csrf']=sha1($token['id']);
                //accessToken生成
                $result['token']=Crypt::encrypt($request->header('app').'-user-'.$user['id'].'-token-'.$token['id'].'-'.$user['id'].$request->header('app').$token['id']);
                if($request->has('grant_type') && $request->input('grant_type')=='password'){
                    //ログイン情報以外の付与
                    $result['variant']=[
                        'user'=>Funcs::getAuthInfo('user',$user['id']),
                        'accounts'=>Funcs::getAuthInfo('accounts',$user['id']),
                    ];
                }
                return Response::generate($response->status(),$result);
            }else{

                $ja_message=[
                    "unsupported_grant_type"=>"不明な認証方式",
                    "invalid_request"=>"不明なリクエスト",
                    "invalid_client"=>"不明なクライアント",
                    "invalid_grant"=>"認証不可",
                    "The authorization grant type is not supported by the authorization server."=>"サポートされていない認証方式です。",
                    "Check that all required parameters have been provided"=>"リクエストパラメータを確認してください。",
                    "Client authentication failed"=>"クライアントの認証に失敗しました。",
                    "The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed."=>"リクエスト内容を確認してください。",
                    "Check the `client_id` parameter"=>"リクエストパラメータの値を確認してください。",
                    "Check the `username` parameter"=>"メールアドレスもしくは電話番号，パスワードを確認してください。",
                    "Check the `password` parameter"=>"メールアドレスもしくは電話番号，パスワードを確認してください。",
                    "The user credentials were incorrect."=>"ユーザーを認可できませんでした。\nメールアドレスもしくは電話番号，パスワードを確認してください。"
                ];

                //ログインエラー
                $result=[
                    "title"=>isset($ja_message[$content['error']])?$ja_message[$content['error']]:$content['error'],
                    "message"=>[
                        isset($ja_message[$content['message']])?$ja_message[$content['message']]:$content['message'],
                    ]
                ];
                if(isset($content['hint'])){
                    $result['message'][]=isset($ja_message[$content['hint']])?$ja_message[$content['hint']]:$content['hint'];
                }
                return Response::error($response->status(),$result['title'],$result['message']);
            }
        }catch (Exception $e){
            return Response::error(500,"スクリプトエラー",$e->getMessage());
        }
    }
}
