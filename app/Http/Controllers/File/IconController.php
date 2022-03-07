<?php

namespace App\Http\Controllers\File;

use App\Http\Controllers\Common\Response;
use App\Http\Controllers\Controller;
use App\Models\File\File_file;
use App\Models\File\File_icon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use InterventionImage;

class IconController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Response::generate(200,File_icon::getIconObject(array_column(File_icon::select(['id'])->where('UserId',$this->user['id'])->get()->toArray(),'id')));
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
        $payloads=[];
        $fileName=Carbon::now()->format('Ymd_His');/*
        $token=explode('-',Crypt::decrypt($request->header('AccessToken')));
        $user=$token[2]?:0;*/
        $user=0;
        if(!empty($request->file('file'))){
            foreach($request->file('file') as $key=>$file){
                $fileId=[];
                $name=$file->getClientOriginalName();
                $ext=".".$file->getClientOriginalExtension();

                //regular
                $icons=[
                    "regular"=>$file->storeAs('icon',$fileName.'-'.$key.'-regular'.$ext),
                    "tiny"=>InterventionImage::make($file)->orientate()->resize(64,64)->save(storage_path('app/icon/'.$fileName.'-'.$key.'-tiny'.$ext)),
                    "small"=>InterventionImage::make($file)->orientate()->resize(128,128)->save(storage_path('app/icon/'.$fileName.'-'.$key.'-small'.$ext)),
                    "middle"=>InterventionImage::make($file)->orientate()->resize(256,256)->save(storage_path('app/icon/'.$fileName.'-'.$key.'-middle'.$ext)),
                    "large"=>InterventionImage::make($file)->orientate()->resize(512,512)->save(storage_path('app/icon/'.$fileName.'-'.$key.'-large'.$ext)),
                ];
                foreach($icons as $size=>$icon){
                    $File=new File_file();
                    $File->FileId=Str::uuid();
                    $File->UserId=$user;
                    $File->path='icon/'.$fileName.'-'.$key.'-'.$size.$ext;
                    $File->name=$name;
                    $File->srv=1;
                    $File->type=$file->getMimeType();
                    if($File->save()){
                        $fileId[$size]=$File->id;
                        $payloads[$key][$size]=$File->id;
                    }else{
                        $fileId[$size]=0;
                        $error=Response::error(500002);
                        $payloads[$key][$size]=$error['error'];
                    }
                }
                $icon=new File_icon();
                $icon->IconId=Str::uuid();
                $icon->UserId=$user;
                $icon->regular=$fileId['regular'];
                $icon->tiny=$fileId['tiny'];
                $icon->small=$fileId['small'];
                $icon->middle=$fileId['middle'];
                $icon->large=$fileId['large'];
                if($icon->save()){
                    $payloads[$key]=File_icon::getIconObject($icon['id']);
                }else{
                    $error=Response::error(500002);
                    $payloads[$key]=$error['error'];
                }
            }
        }
        return Response::generate(201,$payloads);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id,$size)
    {
        //
        $path=$this->GetPath($id,$size);
        if(strpos($request->url(),'https://download.licsss.com/')!==false){
            return response()->download($path[0],$path[1]);
        }else{
            return response()->file($path[0],$path[1]);
        }
    }
    private function GetPath(string $id,string $size){
        $file_path = 'app/public/404.jpg';
        $title='NotFound.jpg';
        $error=true;
        switch($id){
            case '0':
                $file_path = 'app/public/noimage.jpg';
                $title='NoImage.jpg';
                $error=false;
                break;
            case '403':
                $file_path = 'app/public/403.jpg';
                $title='Forbitten.jpg';
                $error=403;
                break;
            case '404':
                $file_path = 'app/public/404.jpg';
                $title='NotFound.jpg';
                $error=404;
                break;
            default:
                if($icon=File_icon::where('IconId',$id)->first()){
                    if($file=File_file::find($icon[$size])){
                        $file_path = 'app/'.$file['path'];
                        $title=$file['title'];
                        $error=false;
                    }
                }
        }
        $headers = ['Content-disposition' => 'inline; filename="'.$title.'"'];
        return [storage_path($file_path), $headers,$title,$error];
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
        //
        if($file=File_icon::where('IconId',$id)->first()){
            //受け取るパラメータ
            $receiveInput=[];
            //過剰パラメータの確認
            if($request->except($receiveInput)){
                return Response::error(406002);
            }
            //バリデーション
            $validator=Validator::make($request->input(),[
            ]);
            if($validator->fails()){
                //バリデーションエラー
                return Response::error(406003,null,$validator->errors()->toArray());
            }else{
                //処理
                $UserId=$request->user()->id;
                if($file['UserId']==0){
                    $file->UserId=$UserId;
                }
                if($file->save()){
                    foreach($file->only(['regular','tiny','small','middle','large']) as $id){
                        if($file=File_file::find($id)){
                            if($file['UserId']==0){
                                $file->UserId=$UserId;
                                $file->save();
                            }
                        }
                    }
                    return Response::generate(200,$file->getIconObject($file['id']));
                }else{
                    return Response::error(500002);
                }
            }
        }else{
            return Response::error(404001);
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
