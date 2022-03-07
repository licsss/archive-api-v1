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
        Schema::create('recipe_item_prices', function (Blueprint $table) {
            $table->id();
            $table->uuid('ItemPriceId')->index()->unique();
            $table->bigInteger('ItemId');
            $table->bigInteger('AccountId');
            $table->integer('price');
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
        Schema::dropIfExists('recipe_item_prices');
    }
};
