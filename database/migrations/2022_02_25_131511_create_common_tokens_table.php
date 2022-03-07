<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * CSRFトークン用テーブル
     *
     * @return void
     */
    public function up()
    {
        Schema::create('common_tokens', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('UserId');
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
        Schema::dropIfExists('common_tokens');
    }
};
