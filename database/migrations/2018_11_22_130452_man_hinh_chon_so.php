<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ManHinhChonSo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chonsos', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->integer('soduthuong')->nullable();
            $table->decimal('tienduthuong')->nullable();
            $table->integer('hanmucconso')->nullable();
            $table->decimal('tonghanmuc')->nullable();
            $table->date('ngayduthuong')->nullable();
            $table->string('loduthuong')->nullable();
            $table->string('daiduthuong')->nullable();
            $table->string('mobile')->nullable();
            $table->string('menhgia')->nullable();
            $table->boolean('deleted')->default(false);
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
        Schema::dropIfExists('chonsos');
        //
    }
}
