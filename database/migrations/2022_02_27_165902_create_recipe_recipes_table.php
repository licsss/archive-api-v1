<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipe_recipes', function (Blueprint $table) {
            $table->id();
            $table->uuid('RecipeId')->index()->unique();
            $table->bigInteger('AccountId')->index();
            $table->text('name');
            $table->string('time',50);
            $table->tinyInteger('status')->default(3);
            $table->json('ingredient')->default(json_encode([]));
            $table->json('process')->default(json_encode([]));
            $table->text('tip');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recipe_recipes');
    }
};
