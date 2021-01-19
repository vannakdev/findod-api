<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyAddResidenceTypeIdToResidencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('residences', function (Blueprint $table) {
            $table->integer('residence_type_id')->unsigned()->default(1)->index();
            $table->foreign('residence_type_id')
                ->references('id')->on('residence_type')
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
        // Schema::disableForeignKeyConstraints();

        // Schema::table('residences', function (Blueprint $table) {
        //     $table->dropColumn('residence_type_id');
        // });

        // Schema::enableForeignKeyConstraints();

    }
}
