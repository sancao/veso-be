<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDaily extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dailies', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('madaily')->unique();
            $table->string('tendaily');
            $table->string('diachi')->nullable();
            $table->string('sodienthoai')->unique();
            $table->integer('dailyquanly')->nullable();
            $table->enum('cap', array('cap1', 'cap2','cap3','cap4'))->default("cap2");
            $table->softDeletes();
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
        Schema::dropIfExists('dailies');
    }
}
