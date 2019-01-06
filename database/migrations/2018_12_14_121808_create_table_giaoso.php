<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableGiaoso extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('giaosos', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('tuso')->unique();
            $table->string('denso');
            $table->string('menhgia')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('daily_id')->nullable();
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
        Schema::dropIfExists('giaosos');
    }
}
