<?php

namespace Database\Seeders\Recipe;

use App\Models\Recipe\Recipe_unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RecipeUnit extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $units=[
            '',
            'ml(cc)',
            'l',
            'kl',
            'mg',
            'g',
            'kg',
            '個',
            'cm',
            'm',
            '本',
            "合",
            "升",
            "玉"
        ];
        foreach($units as $unit){
            $recipe=new Recipe_unit();
            $recipe->UnitId=Str::uuid();
            $recipe->unit=$unit;
            $recipe->AccountId=0;
            $recipe->status=1;
            $recipe->save();
        }
    }
}
