<?php

namespace App\Models\Recipe;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe_item extends Model
{
    use HasFactory;

    protected $casts=[
        "name"=>"encrypted",
        "description"=>"encrypted"
    ];

    static public function createItemObject(Recipe_item $recipe_item,?Recipe_item_price $item=null,?string $unit=null,?array $category=null):array
    {
        return [
            "ItemId"=>$recipe_item['ItemId'],
            "name"=>$recipe_item['name'],
            "description"=>$recipe_item['description'],
            "unit"=>$unit?:Recipe_unit::find($recipe_item['unit'])->unit,
            'UnitPrice'=>$item?$item['price']:$recipe_item['UnitPrice'],
            'category'=>$category?:Recipe_category::getCategoryObject($recipe_item['CategoryId'])
        ];
    }

    static public function getItemObject(int|array|null $itemId=null,int $accountId=0,int $categoryId=0):array
    {
        $payloads=[];
        $units=[];
        $categories=[];
        if($itemId===null){
            foreach(Recipe_item::where('CategoryId',$categoryId?'=':'<>',$categoryId)->where('status',1)->orWhere([['AccountId',$accountId],['status',0]])->get() as $item){
                if(!isset($units[$item['unit']])){
                    $units[$item['unit']]=Recipe_unit::find($item['unit'])->unit;
                }
                if(!isset($categories[$item['CategoryId']])){
                    $categories[$item['CategoryId']]=Recipe_category::getCategoryObject($item['CategoryId']);
                }
                $payloads[$item['ItemId']]=static::createItemObject(
                    $item,
                    Recipe_item_price::where([['ItemId',$item['id']],['AccountId',$accountId]])->first()?:null,
                    $units[$item['unit']],
                    $categories[$item['CategoryId']]
                );
            }
        }else{
            if(is_array($itemId)){
                foreach($itemId as $id){
                    if($item=parent::find($id)){
                        if(!isset($units[$item['unit']])){
                            $units[$item['unit']]=Recipe_unit::find($item['unit'])->unit;
                        }
                        if(!isset($categories[$item['CategoryId']])){
                            $categories[$item['CategoryId']]=Recipe_category::getCategoryObject($item['CategoryId']);
                        }
                        $payloads[$item['ItemId']]=static::createItemObject(
                            $item,
                            Recipe_item_price::where([['ItemId',$item['id']],['AccountId',$accountId]])->first()?:null,
                            $units[$item['unit']],
                            $categories[$item['CategoryId']]
                        );
                    }
                }
            }else{
                if($item=parent::find($itemId)){
                    if(!isset($units[$item['unit']])){
                        $units[$item['unit']]=Recipe_unit::find($item['unit'])->unit;
                    }
                    if(!isset($categories[$item['CategoryId']])){
                        $categories[$item['CategoryId']]=Recipe_category::getCategoryObject($item['CategoryId']);
                    }
                    $payloads[$item['ItemId']]=static::createItemObject(
                        $item,
                        Recipe_item_price::where([['ItemId',$item['id']],['AccountId',$accountId]])->first()?:null,
                        $units[$item['unit']],
                        $categories[$item['CategoryId']]
                    );
                }
            }
        }
        return $payloads;
    }
}
