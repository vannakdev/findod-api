<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('title', 500)->nullable(false);
            $table->string('slug', 500)->nullable(false);
            $table->longText('content')->default("");
            $table->enum('visibility', ['published', 'draft'])->default('draft');
            $table->boolean('protected')->default(false);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            // $table->dropForeign('user_id');
            // $table->dropForeign('chat_channels_property_id_foreign');
        });
        Schema::dropIfExists('posts');
    }
}
