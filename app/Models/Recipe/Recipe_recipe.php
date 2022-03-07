<?php

namespace App\Models\Recipe;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe_recipe extends Model
{
    use HasFactory;

    protected $casts=[
        "name"=>"encrypted",
        "ingredient"=>"json",
        "process"=>"json",
        "tip"=>"json"
    ];

    static public function createRecipeObject(Recipe_recipe $recipe_recipe,int $accountId=0):array
    {
        return [
            "RecipeId"=>$recipe_recipe['RecipeId'],
            "name"=>$recipe_recipe['name'],
            "status"=>$recipe_recipe['status'],
            "time"=>$recipe_recipe['time'],
            "ingredient"=>static::getIngredientObject($recipe_recipe['ingredient'],$accountId),
            "process"=>static::getProcessObject($recipe_recipe['process']),
            "tip"=>$recipe_recipe['tip']
        ];
    }

    static public function getRecipeObject(array|int|null $recipeId=null,int $accountId=0):array
    {
        $payloads=[];

        if($recipeId===null){
            foreach(Recipe_recipe::where('AccountId',$accountId)->get() as $recipe){
                $payloads[$recipe['RecipeId']]=static::createRecipeObject($recipe,$accountId);
            }
        }else{
            if(is_array($recipeId)){
                foreach($recipeId as $id){
                    if($recipe=parent::find($id)){
                        $payloads[$recipe['RecipeId']]=static::createRecipeObject($recipe,$accountId);
                    }
                }
            }else{
                if($recipe=parent::find($recipeId)){
                    $payloads[$recipe['RecipeId']]=static::createRecipeObject($recipe,$accountId);
                }
            }
        }
        
        return $payloads;
    }

    static public function convertProcess(array $process):array
    {
        return $process;
    }

    static public function convertFromProcessObject(array $process):array
    {
        return $process;
        /*処理が必要になったら
        if(isset($process['text'])){
            return static::convertProcess($process);
        }else{
            $payloads=[];
            foreach($process as $row){
                $payloads[]=static::convertProcess($row);
            }
            return $payloads;
        }*/
    }

    static public function getProcessObject(array $process):array
    {
        return $process;
    }

    static public function convertIngredient(array $ingredient):array
    {
        if(isset($ingredient['item']) && isset($ingredient['amount']) && isset($ingredient['unit'])){
            return [
                "item"=>Recipe_item::where('ItemId',$ingredient['item'])->first()?->id,
                "amount"=>$ingredient['amount'],
                "unit"=>Recipe_unit::where('UnitId',$ingredient['unit'])->first()?->id
            ];
        }else{
            return $ingredient;
        }
    }

    static public function convertFromIngredientObjecy(array $ingredient):array
    {
        if(isset($ingredient['item']) && isset($ingredient['amount']) && isset($ingredient['unit'])){
            return static::convertIngredient($ingredient);
        }else{
            $payloads=[];
            foreach($ingredient as $row){
                $payloads[]=static::convertIngredient($row);
            }
            return $payloads;
        }
    }

    static public function getIngredientObject(array $ingredient,int $accountId=0):array
    {
        if(isset($ingredient['item']) && isset($ingredient['amount'])){
            return [
                "item"=>Recipe_item::getItemObject($ingredient['item'],$accountId),
                "amount"=>$ingredient['amount'],
                "unit"=>Recipe_unit::getUnitObject($ingredient['unit'])
            ];
        }else{
            $payloads=[];
            foreach($ingredient as $key=>$row){
                if(isset($row['item']) && isset($row['amount']) && isset($row['unit'])){
                    $payloads[$key]=[
                        "item"=>Recipe_item::getItemObject($row['item'],$accountId),
                        "amount"=>$row['amount'],
                        'unit'=>Recipe_unit::getUnitObject($row['unit'])
                    ];
                }
            }
            return $payloads;
        }
    }
}
