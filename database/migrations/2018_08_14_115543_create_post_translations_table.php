<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts',  function (Blueprint $table) {
            $table->dropColumn(['title','content']);
        });

        Schema::create('post_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_id')->unsigned();
            $table->string('locale')->index();
            $table->string('title', 500)->nullable(false);
            $table->longText('content')->default("");
            $table->string('meta_keyword',70)->default("")->nullable(false);
            $table->string('meta_description',500)->default("")->nullable(false);

            $table->unique(['post_id','locale']);
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('post_translations', function (Blueprint $table) {
            $table->dropForeign('post_translations_post_id_foreign');
            // $table->dropForeign('chat_channels_property_id_foreign');
        });
        Schema::dropIfExists('post_translations');

        Schema::table('posts',  function (Blueprint $table) {
            $table->string('title', 500)->nullable(false)->after('user_id');
            $table->longText('content')->default("")->after("slug");
        });
    }
}
