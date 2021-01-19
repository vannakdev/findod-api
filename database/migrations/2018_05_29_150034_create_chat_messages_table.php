<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('chat_channel_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('content', 200)->nullable(false)->default('');
            $table->enum('flag', ['sent', 'seen'])->nullable(false)->default('sent');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('chat_channel_id')
                  ->references('id')
                  ->on('chat_channels')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chat_channels', function (Blueprint $table) {
            // $table->dropForeign('chat_messages_user_id_foreign');
            // $table->dropForeign('chat_messages_chat_channel_id_foreign');
        });
        Schema::dropIfExists('chat_messages');
    }
}
