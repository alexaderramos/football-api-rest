<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
//            $table->id();
            $table->unsignedBigInteger('id')->primary();
            $table->unsignedBigInteger('competition_id');
            $table->string('name')->nullable();
            $table->string('shortName')->nullable();
            $table->string('crest')->nullable();
            $table->string('clubColors')->nullable();
            $table->string('founded',4)->nullable();
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
        Schema::dropIfExists('teams');
    }
}
