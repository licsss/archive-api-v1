<?php

namespace App\Models\Common;

use App\Models\File\File_file;
use App\Models\File\File_icon;
use App\Models\Profile\Profile_profile;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Common_account extends Model
{
    use HasFactory;

    protected $casts=[
        'name'=>'encrypted',
        'AccountName'=>'encrypted'
    ];

    static public function getAccountVirtualObject(int $id,bool $my=false){
        return array_merge(
            parent::select(['AccountId','name as VirtualName'])->withCasts(['VirtualName'=>"encrypted"])->find($id)->toArray(),
            static::getProfileObject($id,$my)
        );
    }
    static public function getProfileObject(int $id,bool $my=false){
        if($profile=Profile_profile::select(['display_name as DisplayName','IconId as icon','message','birthday','birthday_public as BirthdayPublic'])->where('AccountId',$id)->withCasts(['DisplayName'=>"encrypted"])->first()->toArray()){
            if($account=Common_account::find($id)){
                $profile=array_merge(['AccountId'=>$account['AccountId']],$profile);
                $birthday=new Carbon($profile['birthday']);
                if(!$my){
                    switch((int)$profile['BirthdayPublic']){
                        case 0:
                            $profile['birthday']="";
                            break;
                        case 1:
                            $profile['birthday']=$birthday->year;
                            break;
                        case 2:
                            $profile['birthday']=$birthday->month;
                            break;
                        case 3:
                            $profile['birthday']=$birthday->format('m/d');
                            break;
                        case 4:
                            $profile['birthday']=$birthday->format('Y-m-d');
                            break;

                    }
                }else{
                    $profile['birthday']=$birthday->format('Y-m-d');
                }
                //iconファイルオブジェクトに変換
                $profile['icon']=File_icon::getIconObject($profile['icon']);
                return $profile;
            }
        }
        return [];
    }
}
