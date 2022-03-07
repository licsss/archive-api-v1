<?php

namespace App\Http\Controllers\Recipe;

use App\Http\Controllers\Common\Response;
use App\Http\Controllers\Controller;
use App\Models\Recipe\Recipe_category;
use App\Models\Recipe\Recipe_item;
use App\Models\Recipe\Recipe_item_price;
use App\Models\Recipe\Recipe_unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($category)
    {
        //
        if($category=Recipe_category::where('CategoryId',$category)->first()){
            return Response::generate(200,Recipe_item::getItemObject(null,$this->virtualAccount['id'],$category['id']));
        }
        return Response::error(404001);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$category)
    {
        //
        if($category=Recipe_category::where('CategoryId',$category)->first()){
            ////受け取るパラメータ
            $receiveInput=["name","description","unit","UnitPrice"];
            //過剰パラメータの確認
            if($request->except($receiveInput)){
                return Response::error(406002);
            }
            //バリデーション
            $validator=Validator::make($request->input(),[
                "name"=>"string|max:100|required",
                "description"=>"string|max:250|required",
                "unit"=>"string|required",
                "UnitPrice"=>"numeric|required",
            ]);
            if($validator->fails()){
                //バリデーションエラー
                return Response::error(406003,null,$validator->errors()->toArray());
            }else{
                //処理
                $item=new Recipe_item();
                $item->ItemId=Str::uuid();
                $item->CategoryId=$category['id'];
                $item->name=$request->input('name');
                $item->description=$request->input('description');
                $item->unit=Recipe_unit::where('UnitId',$request->input('unit'))?->first()->id;
                $item->UnitPrice=$request->input('UnitPrice');
                $item->status=1;
                if($item->save()){
                    return Response::generate(200,Recipe_item::createItemObject($item));
                }else{
                    return Response::error(401002);
                }
            }
        }
        return Response::error(404001);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($category,$id)
    {
        //
        if($category=Recipe_category::where('CategoryId',$category)->first()){
            if($item=Recipe_item::where([['ItemId',$id],['CategoryId',$category['id']]])->first()){
                $item_price=Recipe_item_price::where([['AccountId',$this->virtualAccount['id']],['ItemId',$item['id']]])?->first();
                return Response::generate(200,Recipe_item::createItemObject($item,$item_price));
            }
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
    public function update(Request $request,$category, $id)
    {
        //
        if($category=Recipe_category::where('CategoryId',$category)->first()){
            if($item=Recipe_item::where([['ItemId',$id],['CategoryId',$category['id']]])->first()){
                ////受け取るパラメータ
                $receiveInput=["name","description","unit","UnitPrice","CategoryId","status"];
                //過剰パラメータの確認
                if($request->except($receiveInput)){
                    return Response::error(406002);
                }
                //バリデーション
                $validator=Validator::make($request->input(),[
                    "name"=>"string|max:100|required",
                    "description"=>"string|max:250|required",
                    "unit"=>"string|required",
                    "UnitPrice"=>"numeric|required",
                    "CategoryId"=>"string|required",
                    "status"=>"numeric|required|min:0|max:1"
                ]);
                if($validator->fails()){
                    //バリデーションエラー
                    return Response::error(406003,null,$validator->errors()->toArray());
                }else{
                    //処理
                    $item->CategoryId=Recipe_category::where('CategoryId',$request->input('CategoryId'))->first()?->id;
                    $item->name=$request->input('name');
                    $item->description=$request->input('description');
                    $item->unit=Recipe_unit::where('UnitId',$request->input('unit'))->first()?->id;
                    $item->UnitPrice=$request->input('UnitPrice');
                    $item->status=$request->input('status');
                    if($item->save()){
                        return Response::generate(200,Recipe_item::createItemObject($item,Recipe_item_price::where([['AccountId',$this->virtualAccount['id']],['ItemId',$item['id']]])->first()));
                    }else{
                        return Response::error(401002);
                    }
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
    public function destroy($category,$id)
    {
        //
        if($category=Recipe_category::where('CategoryId',$category)->first()){
            if($item=Recipe_item::where([['ItemId',$id],['CategoryId',$category['id']]])->first()){
                if($item->delete()){
                    return Response::generate(204);
                }else{
                    return Response::error(401002);
                }
            }
        }
        return Response::error(404001);
    }
}
