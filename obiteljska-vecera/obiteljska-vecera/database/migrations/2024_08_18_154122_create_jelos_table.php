<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJelosTable extends Migration
{
    public function up()
    {
        Schema::create('jelos', function (Blueprint $table) {
            $table->id();
            $table->string('naziv');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jelos');
    }
}
