<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Chats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoices_id')->nullable();
            $table->unsignedInteger('sender_id');
            $table->unsignedBigInteger('message_id')->nullable();
            $table->unsignedInteger('receiver_id');
            $table->string('message');
            $table->integer('status')->default(0);
            $table->timestamps();
            $table->foreign('message_id')->references('id')->on('chats');
            $table->foreign('invoices_id')->references('id')->on('invoices');
            $table->foreign('sender_id')->references('id')->on('users');
            $table->foreign('receiver_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chats');
    }
}
