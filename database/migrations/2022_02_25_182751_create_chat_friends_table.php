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
        Schema::create('chat_friends', function (Blueprint $table) {
            $table->id();
            $table->uuid('FriendId')->unique()->index();
            $table->bigInteger('RoomId');
            $table->bigInteger('AccountId')->index();
            $table->bigInteger('Friend_AccountId');
            $table->text('name');
            $table->json('tag')->default(json_encode([0,'custom'=>[]]));
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('chat_friends');
    }
};
