<?php

namespace App\Http\Middleware\original;

use App\Http\Controllers\Common\Response;
use App\Models\Common\Common_api;
use App\Models\Common\Common_token;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class log
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
        //return $next($request);
        $apps=["spa",'android','ios'];
        try{
            $nonce=$request->header('Nonce');
            $accessToken=$request->header('AccessToken')?:"";
            $csrf=$request->header('Csrf');
            //csrf確認
            if($request->has('csrfToken')){
                if($request->header('Csrf')!==$request->input('csrfToken')){
                    return Response::error(406003);
                }
                unset($request['csrfToken']);
            }
            //ログ記録
            $log=new Common_api();
            $log->access_token=$accessToken;
            $log->authorization=$request->header('Authorization')?:"";
            $log->csrf=0;
            $log->UserId=$request->user()?$request->user()->id:0;
            $log->nonce=$nonce;
            $log->head=$request->header();
            $log->uri=$request->path();
            $log->method=$request->method();
            $log->params=$request->query();
            $log->posts=$request->except(array_merge(['password','password_confirmation'],array_keys($request->query())));
            $log->result=[];
            $log->error_message=[];
            $log->save();
            //accesstoken,csrf確認
            if($request->user()){
                if(empty($accessToken)){
                    $logged=$this->loggedResult(Response::error(406004),$log['id'],$nonce);
                    return response()->json($logged[0],$logged[1]);
                }
                $token=explode('-',Crypt::decrypt($accessToken));
                $log->csrf=(int)$token[4];
                $log->save();
                if(
                    !in_array($token[0],$apps) ||//appタイプ確認
                    //csrf確認
                    $csrf!=sha1($token[4]) ||
                    //構成確認
                    $token[1]!='user' ||
                    $token[3]!=='token' ||
                    //ユーザー一致確認
                    $token[2]!=$request->user()->id ||
                    !Common_token::where([['id',$token[4]],['UserId',$request->user()->id]])->first() ||
                    //署名確認
                    $token[5]!==implode('',[$token[2],$token[0],$token[4]])
                ){
                    $logged=$this->loggedResult(Response::error(406007),$log['id'],$nonce);
                    return response()->json($logged[0],$logged[1]);
                }
            }
            //ファイルアップロード
            if(
                $request->isMethod('POST') && 
                ($request->url()=='https://download.licsss.com/' ||
                $request->url()=='http://localhost/licsss/api/v1/public/')//https://file.licsss.com/
            ){
                if(!empty($accessToken)){
                    $token=explode('-',Crypt::decrypt($accessToken));
                    $log->csrf=(int)$token[4];
                    $log->save();
                    if(
                        !in_array($token[0],$apps) ||//appタイプ確認
                        //csrf確認
                        $csrf!=sha1($token[4]) ||
                        //構成確認
                        $token[1]!='user' ||
                        $token[3]!=='token' ||
                        //ユーザー一致確認
                        //$token[2]!=$request->user()->id ||
                        //!Common_token::where([['id',$token[4]],['UserId',$request->user()->id]])->first() ||
                        //署名確認
                        $token[5]!==implode('',[$token[2],$token[0],$token[4]])
                    ){
                        $logged=$this->loggedResult(Response::error(406007),$log['id'],$nonce);
                        return response()->json($logged[0],$logged[1]);
                    }
                }
            }
            if(empty($nonce)){
                $logged=$this->loggedResult(Response::error(406004),$log['id'],$nonce);
                return response()->json($logged[0],$logged[1]);
            }else{
                $response=$next($request);
                if(isset($response->original)){
                    $content=$response->original;
                }else{
                    $content=$response;
                }
                //$content=$next($request);
                //$content=json_decode($response->original,true);
                /*if(is_array($response)){
                    $content=json_decode($response,true);
                }else{
                    $content=$response;
                }
                $result=[
                    "result"=>isset($content['result'])?$content['result']:(isset($content['error'])?false:true),
                    "code"=>isset($content['code'])?(int)$content['code']:(isset($content['error'])?400:200),
                    "nonce"=>$nonce,
                ];*/
                //return response()->json($content);
                $logged=$this->loggedResult($content,$log['id'],$nonce);
                return response()->json($logged[0],$logged[1]);
            }
        }catch(Exception $e){
            return response()->json(array_merge(
                Response::error(500,"スクリプトエラー",$e->getMessage()),
                [
                    "result"=>false,
                    "code"=>500,
                    "nonce"=>$nonce
                ]
            ),500);
        }
    }

    private function loggedResult($content,$logId,$nonce){
        if($log=Common_api::find($logId)){
            $result=[
                "result"=>$content['result'],
                "code"=>(int)$content['code'],
                "nonce"=>$nonce,
            ];
            $log->response_code=$result['code'];
            if(isset($content['error'])){
                $result['error']=$content['error'];
                $log->error_title=$content['error']['title'];
                $log->error_code=$content['error']['code'];
                $log->error_message=$content['error']['message'];
            }
            if(isset($content['payloads'])){
                $result['payloads']=$content['payloads'];
            }
            $log->result=$result;
            $log->save();
            if($result['code']!=204){
                return [$result,$result['code']?:200];
            }else{
                return [null,204];
            }
        }
    }
}
