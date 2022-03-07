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
        Schema::create('file_icons', function (Blueprint $table) {
            $table->id();
            $table->uuid('IconId')->unique()->index();
            $table->bigInteger('UserId')->default(0);
            $table->bigInteger('regular')->default(0);
            $table->bigInteger('tiny')->default(0);
            $table->bigInteger('small')->default(0);
            $table->bigInteger('middle')->default(0);
            $table->bigInteger('large')->default(0);
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
        Schema::dropIfExists('file_icons');
    }
};
