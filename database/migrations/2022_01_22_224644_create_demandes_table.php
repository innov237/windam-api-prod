<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemandesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demandes', function (Blueprint $table) {
            $table->increments('id');;
            $table->unsignedInteger('agent_id')->nullable();
            $table->unsignedInteger('service_id');
            $table->unsignedInteger('user_id');
            $table->string('description');
            $table->string('tel');
            $table->string('city');
            $table->date('date');
            $table->time('heure');
            $table->string('file')->nullable();
            $table->timestamps();
            $table->integer('status');
             $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demandes');
    }
}
