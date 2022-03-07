<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Response extends Controller
{
    //
    static public function generate(int $code,array|string|null $payloads=null){
        $return=[
            "result"=>true,
            "code"=>$code?:200,
        ];
        if($payloads!==null){
            $return['payloads']=is_array($payloads)?$payloads:[$payloads];
        }
        return $return;
    }
    static public function error(int $code,string|null $title="",array|string|null $message=""){

        //メッセージ編集
        $editedMessage=[];
        $method=false;//メソッドエラー確認

        

        if(!is_array($message)){
            if(!empty($message)){
                //メソッドエラー確認
                if(strpos($message,"method is not supported")!==false){
                    $method=true;
                }else{
                    $editedMessage[]=$message;
                }
            }
        }else{
            foreach($message as $val){
                if(is_array($val)){
                    //メソッドエラー
                    if(strpos(implode(",",$val),"method is not supported")!==false){
                        $method=true;
                    }else{
                        $editedMessage=array_merge($editedMessage,$val);
                    }
                }else{
                    //メソッドエラー
                    if(strpos($val,"method is not supported")!==false){
                        $method=true;
                    }else{
                        $editedMessage[]=$val;
                    }
                }
            }
        }
        /**
         * メソッドエラーの場合
         * codeを405に変更
         * メッセージをデフォルトに
         */
        if($method){
            $editedMessage=[];
            $title="";
            $code=405;
        }

        /**
         * codeを生成
         * httpCodeに変換
         */
        if(empty($code)){
            $code=400;
        }
        if($code>600){
            $httpCode=substr($code,0,3);
        }else{
            $httpCode=$code;
            $code=$code*1000;
        }
        /**
         * デフォルトメッセージの設定
         */
        if(count($editedMessage)==0 || empty($tile)){
            switch($code){
                case 400:
                case 400000:
                    $defaultTitle="エラー発生";
                    $defaultMessage=["エラーが発生しました。"];
                    break;
                case 401:
                case 401000:
                    $defaultTitle="認証失敗";
                    $defaultMessage=["認証できませんでした。"];
                    break;
                case 401001:
                    $defaultTitle="認証失敗";
                    $defaultMessage=["メールアドレス(電話番号)もしくはパスワードが一致しません。\n確認してください。"];
                    break;
                case 401002:
                    $defaultTitle="認証失敗";
                    $defaultMessage=["パスワードを確認してください。"];
                    break;
                case 403:
                case 40300:
                    $defaultTitle="アクセス不可";
                    $defaultMessage=["アクセスできる権限がありません。"];
                    break;
                case 403001:
                    $defaultTitle="編集不可";
                    $defaultMessage=["編集できる権限がありません。"];
                    break;
                case 403002:
                    $defaultTitle="登録不可";
                    $defaultMessage=["登録できる権限がありません。"];
                    break;
                case 403003:
                    $defaultTitle="削除不可";
                    $defaultMessage=["削除できる権限がありません。"];
                    break;
                case 404:
                case 404000:
                    $defaultTitle="存在しないURL";
                    $defaultMessage=["存在しないURLです。"];
                    break;
                case 404001:
                    $defaultTitle="存在しないレコード";
                    $defaultMessage=["存在しないレコードです。"];
                    break;
                case 405:
                case 405000:
                    $defaultTitle="不許可メソッド";
                    $defaultMessage=["許可されていないメソッドです。"];
                    break;
                case 406:
                case 406000:
                    $defaultTitle="リクエストエラー";
                    $defaultMessage=["受付不可能なエラーです。"];
                    break;
                case 406001:
                    $defaultTitle="パラメータ不足";
                    $defaultMessage=["リクエストパラメータが不足しています。"];
                    break;
                case 406002:
                    $defaultTitle="過剰パラメータ";
                    $defaultMessage=["リクエストパラメータが過剰です。"];
                    break;
                case 406003:
                    $defaultTitle="入力値エラー";
                    $defaultMessage=["パラメータの入力値エラーです。"];
                    break;
                case 406004:
                    $defaultTitle="ヘッダ不足";
                    $defaultMessage=["ヘッダが不足しています。"];
                    break;
                case 406005:
                    $defaultTitle="クエリ不可";
                    $defaultMessage=["クエリが許可されていません。"];
                    break;
                case 406006:
                    $defaultTitle="メールアドレス未認証";
                    $defaultMessage=["メールアドレスの認証をしてください。"];
                    break;
                case 406007:
                    $defaultTitle="ヘッダ不可";
                    $defaultMessage=["ヘッダを確認してください。"];
                    break;
                case 409:
                case 409000:
                    $defaultTitle="データ保存不可";
                    $defaultMessage=["他のデータと競合しているため保存できませんでした。"];
                    break;
                case 500:
                case 500000:
                    $defaultTitle="サーバエラー";
                    $defaultMessage=["サーバでエラーが発生しました。"];
                    break;
                case 500001:
                    $defaultTitle="スクリプトエラー";
                    $defaultMessage=["スクリプトエラーが発生しました。\n運営にご連絡ください。"];
                    break;
                case 500002:
                    $defaultTitle="保存失敗";
                    $defaultMessage=["スクリプトエラーにより保存に失敗しました。\n運営にご連絡ください。"];
                    break;
                case 503:
                case 503000:
                    $defaultTitle="サービス利用不可";
                    $defaultMessage=["サービスが利用できない状態です。\n時間をおいても続く場合は運営にご連絡ください。"];
                    break;
                default:
                    $defaultTitle="エラー発生";
                    $defaultMessage=["エラーが発生しました。"];
                    break;
            }
            if(empty($title)){
                $title=$defaultTitle;
            }
            if(count($editedMessage)==0){
                $editedMessage=$defaultMessage;
            }
        }
        
        $abstract=[
            400000=>"BAD REQUEST",
            401000=>"UNAUTHORIEZED",
            401001=>"NOT MATCH EMAIL OR PASSWORD",
            401002=>"NOT MATCH PASSWORD",
            403000=>"FORBITTEN",
            403001=>"EDIT FORBITTEN",
            403002=>"CREATE FORBITTEN",
            403003=>"DELETE FORBITTEN",
            404000=>"NOT FOUND",
            404001=>"NOT EXIST RECORD",
            405000=>"METHOD NOT ALLOWED",
            406000=>"NOT ACCEPTABLE",
            406001=>"LACK PARAMETERS",
            406002=>"OVER PARAMETERS",
            406003=>"VALIDATION ERROR",
            406004=>"LACK HEADER",
            406005=>"NOT ACCEPT QUERIES",
            406006=>"NOT VERIFIED EMAIL",
            406007=>"NOT ACCEPT HEADER",
            409000=>"CONFLICT",
            500000=>"INTERNAL SERVER ERROR",
            500001=>"SCRIPT ERROR",
            503000=>"SERVICE UNAVAILABLE",
        ];
        return [
            "result"=>false,
            "code"=>(int)$httpCode?:400,
            "error"=>[
                "code"=>$code,
                "title"=>$title,
                "abstract"=>isset($abstract[$code])?$abstract[$code]:'ERROR',
                "message"=>$editedMessage
            ]
        ];
    }
}
