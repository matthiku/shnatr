<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'messages', function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();
                $table->text('message');
                $table->string('filename')->nullable();
                $table->string('filetype')->nullable();
                $table->integer('user_id')->unsigned();
                $table->integer('room_id')->unsigned();
                $table->foreign('room_id')
                    ->references('id')->on('rooms')
                    ->onDelete('cascade');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
