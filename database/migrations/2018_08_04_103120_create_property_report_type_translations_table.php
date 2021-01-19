<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyReportTypeTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_reporty_type_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('report_type_id')->unsigned();
            $table->char('locale', 20)->index();
            $table->string('title', 50);
            $table->longText('content', 500);
            $table->timestamps();

            $table->unique(['report_type_id', 'locale']);
            $table->foreign('report_type_id')->references('id')->on('type_of_property_reports')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('property_reporty_type_translations');
    }
}
