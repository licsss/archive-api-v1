<?php

namespace App\Models\Recipe;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe_unit extends Model
{
    use HasFactory;

    static public function createUnitObject(Recipe_unit $recipe_unit):array
    {
        return [
            "UnitId"=>$recipe_unit['UnitId'],
            "unit"=>$recipe_unit['unit']
        ];
    }

    static public function getUnitObject(array|int|null $unitId=null,int $accountId=0):array
    {
        $payloads=[];
        if($unitId===null){
            foreach(static::where('status',1)->orWhere([['status',0],['AccountId',$accountId]])->get() as $unit){
                $payloads[$unit['UnitId']]=static::createUnitObject($unit);
            }
        }else{
            if(is_array($unitId)){
                foreach($unitId as $id){
                    if($unit=static::find($id)){
                        $payloads[$unit['UnitId']]=static::createUnitObject($unit);
                    }
                }
            }else{
                if($unit=static::find($unitId)){
                    $payloads=static::createUnitObject($unit);
                }
            }
        }

        return $payloads;
    }
}
