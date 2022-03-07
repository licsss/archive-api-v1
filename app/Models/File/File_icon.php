<?php

namespace App\Models\File;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File_icon extends Model
{
    use HasFactory;

    static public function createIconObject(File_icon $file_icon):array
    {
        return [
            "IconId"=>$file_icon['IconId'],
            "link"=>File_file::getUrl()['file'].$file_icon['IconId']."/"
        ];
        /*return array_merge([
            "IconId"=>$file_icon['IconId'],
        ],File_file::getFileObject($file_icon->only(['tiny','small','middle','large','regular'])));*/
    }

    static public function getIconObject(int|array $iconId):array
    {
        $payloads=[];
        if(is_array($iconId)){
            foreach($iconId as $key=>$id){
                if($icon=static::find($id)){
                    $payloads[$key]=static::createIconObject($icon);
                }
            }
        }else{
            if($icon=static::find($iconId)){
                $payloads=static::createIconObject($icon);
            }
        }
        return $payloads;
    }
}
