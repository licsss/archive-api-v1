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
        Schema::create('common_apis', function (Blueprint $table) {
            $table->id();
            $table->text('access_token');
            $table->text('authorization');
            $table->bigInteger('csrf');
            $table->bigInteger('UserId');
            $table->string('nonce')->nullable();
            $table->json('head');
            $table->string('uri');
            $table->string('method');
            $table->json('params');
            $table->json('posts');
            $table->integer('response_code')->default(0);
            $table->json('result')->default(json_encode([]));
            $table->integer('error_code')->default(0);
            $table->string('error_title')->default("");
            $table->json('error_message')->default(json_encode([]));
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
        Schema::dropIfExists('common_apis');
    }
};
