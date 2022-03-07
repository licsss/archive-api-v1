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
        Schema::create('profile_profiles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('AccountId')->index()->unique();
            $table->text('display_name');
            $table->bigInteger('IconId')->default(0);
            $table->string('message',250)->default("");
            $table->date('birthday')->nullable();
            $table->tinyInteger('birthday_public')->default(0);
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
        Schema::dropIfExists('profile_profiles');
    }
};
