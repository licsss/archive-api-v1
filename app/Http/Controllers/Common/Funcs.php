<?php

namespace App\Http\Controllers\Common;

use App\Models\Common\Common_account;
use App\Models\User;

class Funcs
{
    static public function getAuthInfo(string $type,int $userId):array
    {
        $payloads=null;
        switch($type){
            case 'accounts':
                if($account=Common_account::select(["AccountId","name as AccountName"])->where('UserId',$userId)->get()){
                    $payloads=$account->toArray();
                }
                break;
            case 'user':
                if($user=User::select(['UserId','name as UserName'])->find($userId)){
                    $payloads=$user->toArray();
                }
                break;
            default:
                $payloads=null;
                break;
        }
        return $payloads;
    }

    /**
     * 開発環境の判断(開発環境の場合，true)
     *
     * @return boolean
     */
    static public function developEnvironment():bool
    {
        return env('APP_DEBUG');
    }
}