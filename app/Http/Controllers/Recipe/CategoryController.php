<?php

namespace App\Http\Controllers\Recipe;

use App\Http\Controllers\Common\Response;
use App\Http\Controllers\Controller;
use App\Models\Recipe\Recipe_category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Response::generate(200,Recipe_category::getCategoryObject(null,$this->virtualAccount['id']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        ////受け取るパラメータ
        $receiveInput=["name","description"];
        //過剰パラメータの確認
        if($request->except($receiveInput)){
            return Response::error(406002);
        }
        //バリデーション
        $validator=Validator::make($request->input(),[
            "name"=>"string|required|max:100",
            "description"=>"string|required|max:250"
        ]);
        if($validator->fails()){
            //バリデーションエラー
            return Response::error(406003,null,$validator->errors()->toArray());
        }else{
            //処理
            $category=new Recipe_category();
            $category->CategoryId=Str::uuid();
            $category->name=$request->input('name');
            $category->description=$request->input('description');
            $category->AccountId=$this->virtualAccount['id'];
            $category->status=1;
            if($category->save()){
                return Response::generate(201,Recipe_category::createCategoryObject($category));
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
        if($category=Recipe_category::where('CategoryId',$id)->first()){
            return Response::generate(200,Recipe_category::createCategoryObject($category));
        }
        return Response::error(404001);
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
        if($category=Recipe_category::where('CategoryId',$id)->first()){
            ////受け取るパラメータ
            $receiveInput=["name","description","status"];
            //過剰パラメータの確認
            if($request->except($receiveInput)){
                return Response::error(406002);
            }
            //バリデーション
            $validator=Validator::make($request->input(),[
                "name"=>"string|required|max:100",
                "description"=>"string|required|max:250",
                "status"=>"numeric|required|min:0|max:1"
            ]);
            if($validator->fails()){
                //バリデーションエラー
                return Response::error(406003,null,$validator->errors()->toArray());
            }else{
                //処理
                $category->name=$request->input('name');
                $category->description=$request->input('description');
                $category->status=$request->input('status');
                if($category->save()){
                    return Response::generate(200,Recipe_category::createCategoryObject($category));
                }else{
                    return Response::error(401002);
                }
            }
        }
        return Response::error(404001);
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
        if($category=Recipe_category::where('CategoryId',$id)->first()){
            if($category->delete()){
                return Response::generate(204);
            }else{
                return Response::error(401002);
            }
        }
        return Response::error(404001);
    }
}
