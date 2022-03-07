<?php

namespace Database\Seeders;

use Database\Seeders\Recipe\Item;
use Database\Seeders\Recipe\RecipeUnit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            //Item::class,
            //RecipeUnit::class,
        ]);
    }
}
