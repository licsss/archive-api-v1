<?php

namespace App\Http\Controllers\Recipe;

use App\Http\Controllers\Common\Response;
use App\Http\Controllers\Controller;
use App\Models\Recipe\Recipe_recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Response::generate(200,Recipe_recipe::getRecipeObject(null,$this->virtualAccount['id']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //受け取るパラメータ
        $receiveInput=["name","time","status","ingredient","process","tip"];
        //過剰パラメータの確認
        if($request->except($receiveInput)){
            return Response::error(406002);
        }
        //バリデーション
        $validator=Validator::make($request->input(),[
            "name"=>"string|required|max:100",
            "time"=>"string|required|max:50",
            "status"=>"numeric|min:0|max:3|required",
            "ingredient"=>"array|required",
            "process"=>"array|required",
            "tip"=>"string|nullable|max:250"
        ]);
        if($validator->fails()){
            //バリデーションエラー
            return Response::error(406003,null,$validator->errors()->toArray());
        }else{
            //処理
            $recipe=new Recipe_recipe();
            $recipe->RecipeId=Str::uuid();
            $recipe->AccountId=$this->virtualAccount['id'];
            $recipe->name=$request->input('name');
            $recipe->time=$request->input('time');
            $recipe->status=$request->input('status');
            $recipe->ingredient=Recipe_recipe::convertFromIngredientObjecy($request->input('ingredient'));
            $recipe->process=Recipe_recipe::convertFromProcessObject($request->input('process'));
            $recipe->tip=$request->input('tip');
            if($recipe->save()){
                return Response::generate(201,Recipe_recipe::createRecipeObject($recipe,$this->virtualAccount['id']));
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
        if($recipe=Recipe_recipe::where('RecipeId',$id)->first()){
            return Response::generate(200,Recipe_recipe::createRecipeObject($recipe,$this->virtualAccount['id']));
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
        if($recipe=Recipe_recipe::where('RecipeId',$id)->first()){
            //受け取るパラメータ
            $receiveInput=["name","time","status","ingredient","process","tip"];
            //過剰パラメータの確認
            if($request->except($receiveInput)){
                return Response::error(406002);
            }
            //バリデーション
            $validator=Validator::make($request->input(),[
                "name"=>"string|required|max:100",
                "time"=>"string|required|max:50",
                "status"=>"numeric|min:0|max:3|required",
                "ingredient"=>"array|required",
                "process"=>"array|required",
                "tip"=>"string|nullable|max:250"
            ]);
            if($validator->fails()){
                //バリデーションエラー
                return Response::error(406003,null,$validator->errors()->toArray());
            }else{
                //処理
                $recipe->name=$request->input('name');
                $recipe->time=$request->input('time');
                $recipe->status=$request->input('status');
                $recipe->ingredient=Recipe_recipe::convertFromIngredientObjecy($request->input('ingredient'));
                $recipe->process=Recipe_recipe::convertFromProcessObject($request->input('process'));
                $recipe->tip=$request->input('tip');
                if($recipe->save()){
                    return Response::generate(200,Recipe_recipe::createRecipeObject($recipe,$this->virtualAccount['id']));
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
        if($recipe=Recipe_recipe::where('RecipeId',$id)->first()){
            if($recipe->delete()){
                return Response::generate(204);
            }else{
                return Response::error(401002);
            }
        }
        return Response::error(404001);
    }
}
