<?php

namespace App\Http\Controllers\File;

use App\Http\Controllers\Common\Response;
use App\Http\Controllers\Controller;
use App\Models\File\File_file;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $services=[
            "file",
            "account",
            "profile",
            "chat",
            "recipe",
        ];
        $payloads=[];
        foreach($services as $key=>$val){
            $payloads[$key]=File_file::getFileObject(File_file::select('id')->where([['UserId',$request->user()->id],['srv',$key]])->get()->toArray());
        }
        return Response::generate(200,$payloads);
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
        $fileName=Carbon::now()->format('Ymd_His_');/*
        $token=explode('-',Crypt::decrypt($request->header('AccessToken')));
        $user=$token[2]?:0;*/
        $user=0;
        if(!empty($request->file('file'))){
            foreach($request->file('file') as $key=>$file){
                $name=$file->storeAs('private',$fileName.'-'.$key);
                $File=new File_file();
                $File->FileId=Str::uuid();
                $File->UserId=$user;
                $File->path=$name;
                $File->name=isset($request->input('FileName')[$key])?$request->input('FileName')[$key]:$file->getClientOriginalName();
                $File->srv=$request->header('Licsss-Srv');
                $File->type=$file->getMimeType();
                if($File->save()){
                    $payloads[$key]=$File->getFileObject($File->id);
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
    public function show(Request $request,$id)
    {
        //
        if(!empty($request->server('HTTP_REFERER')) && strpos($request->server('HTTP_REFERER'),'licsss')){
            $path=$this->GetPath($id);
        }else{
            $path=$this->GetPath(403);
        }
        if($path[3]){
            return view("errors/".$path[3]);
        }else{
            if(strpos($request->url(),'https://download.licsss.com/')!==false){
                return response()->download($path[0],$path[1]);
            }else{
                return response()->file($path[0],$path[1]);
            }
        }
    }
    
    private function GetPath($id){
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
                if($file=File_file::where('FileId',$id)->first()){
                    $file_path = 'app/'.$file['name'];
                    $title=$file['title'];
                    $error=false;
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
        if($file=File_file::where('FileId',$id)->first()){
            //受け取るパラメータ
            $receiveInput=["FileName"];
            //過剰パラメータの確認
            if($request->except($receiveInput)){
                return Response::error(406002);
            }
            //バリデーション
            $validator=Validator::make($request->input(),[
                "FileName"=>"string|required|max:50",
            ]);
            if($validator->fails()){
                //バリデーションエラー
                return Response::error(406003,null,$validator->errors()->toArray());
            }else{
                //処理
                $file->name=$request->input('FileName');
                if($file['UserId']==0){
                    $file->UserId=$request->user()->id;
                }
                if($file->save()){
                    return Response::generate(200,$file->getFileObject($file['id']));
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
