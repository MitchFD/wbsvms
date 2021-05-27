<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEditedNewCreatedOffensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edited_new_created_offenses_tbl', function (Blueprint $table) {
            $table->id('eNew_id');
            $table->unsignedBigInteger('eNew_from_eOld_id')->nullable();
            // $table->foreign('eNew_from_eOld_id')->references('eOld_id')->on('edited_old_created_offenses_tbl')->onDelete('cascade');
            $table->string('eNew_crOffense_category')->nullable();
            $table->string('eNew_crOffense_type')->nullable();
            $table->string('eNew_crOffense_details')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('edited_new_created_offenses_tbl');
    }
}
