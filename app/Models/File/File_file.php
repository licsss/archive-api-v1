<?php

namespace App\Models\File;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class File_file extends Model
{
    use HasFactory;
    protected $casts=[
        "name"=>"encrypted"
    ];

    static public function createFileObject(File_file $file_file):array
    {
        return [
            'FileId'=>$file_file['FileId'],
            'FileName'=>$file_file['name'],
            'type'=>$file_file['type'],
            'link'=>static::getLinkObject($file_file['FileId'])
        ];
    }

    static public function getFileObject(int|array $id):array
    {
        $payloads=[];
        if(is_array($id)){
            foreach($id as $key=>$val){
                if($file=static::find($val)){
                    $payloads[$key]=static::createFileObject($file);
                }
            }
        }else{
            if($file=static::find($id)){
                $payloads=static::createFileObject($file);
            }
        }
        return $payloads;
    }
    static public function getLinkObject(string $FileId):array
    {
        $link=static::getUrl();
        return [
            "file"=>"{$link['file']}{$FileId}",
            "download"=>"{$link['download']}{$FileId}",
        ];
    }
    static public function getUrl():array
    {
        if(App::environment('local')){
            return [
                'file'=>'http://localhost/licsss/api/v1/public/',
                'download'=>'http://localhost/licsss/api/v1/public/',
            ];
        }else{
            return [
                'file'=>'https://file.licsss.com/',
                'download'=>'https://download.licsss.com/',
            ];
        }
    }
}
