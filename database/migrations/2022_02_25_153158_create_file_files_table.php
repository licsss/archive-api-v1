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
        Schema::create('file_files', function (Blueprint $table) {
            $table->id();
            $table->uuid('FileId')->index()->unique();
            $table->bigInteger('UserId')->default(0);
            $table->string('path');
            $table->text('name');
            $table->string('type');
            $table->tinyInteger('srv')->default(0);
            //$table->bigInteger('FolderId')->default(0);
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
        Schema::dropIfExists('file_files');
    }
};
