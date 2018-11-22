<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableNaptien extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('naptiens', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('tendaily');
            $table->decimal('sotien')->nullable();
            $table->date('ngaynap')->nullable();
            $table->boolean('trangthai')->default(false);
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
        Schema::dropIfExists('naptiens');
    }
}
