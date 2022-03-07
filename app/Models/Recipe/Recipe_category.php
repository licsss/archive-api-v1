<?php

namespace App\Models\Recipe;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe_category extends Model
{
    use HasFactory;
    protected $casts=[
        "name"=>"encrypted",
        "description"=>"encrypted"
    ];
    
    static public function createCategoryObject(Recipe_category $recipe_category):array
    {
        return [
            "CategoryId"=>$recipe_category['CategoryId'],
            "name"=>$recipe_category['name'],
            "description"=>$recipe_category['description']
        ];
    }

    static public function getCategoryObject(int|array|null $categoryId=null,int $accountId=0):array
    {
        $payloads=[];
        if($categoryId===null){
            foreach(parent::where('status',1)->orWhere([['AccountId',$accountId],['status',0]])->get() as $category){
                $payloads[$category['CategoryId']]=static::createCategoryObject($category);
            }
        }else{
            if(is_array($categoryId)){
                foreach($categoryId as $id){
                    if($category=parent::find($id)){
                        $payloads[$category['CategoryId']]=static::createCategoryObject($category);
                    }
                }
            }else{
                if($category=parent::find($categoryId)){
                    $payloads=static::createCategoryObject($category);
                }
            }
        }
        return $payloads;
    }
}
