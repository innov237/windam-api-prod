<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Facture extends Migration
{
    /**
     * Run the migrations.
     * 0 non payer
     * 1 payer
     * 2 demande de devis par l'utilisateur
     * 
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('demande_id');
            $table->unsignedInteger('user_id');
            $table->Integer('montant');
            $table->string('description');
            $table->json('lignecmd')->nullable();
            $table->integer('status');
             $table->double('tva')->default(0);
            $table->timestamps();
            $table->foreign('demande_id')->references('id')->on('demandes');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facture');
    }
}
