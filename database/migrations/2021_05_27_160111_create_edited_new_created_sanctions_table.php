<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEditedNewCreatedSanctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edited_new_created_sanctions_tbl', function (Blueprint $table) {
            $table->id('eNew_id');
            $table->unsignedBigInteger('eNew_from_eOld_id');
            // $table->foreign('eNew_from_eOld_id')->references('eOld_id')->on('edited_old_created_sanctions_tbl')->onDelete('cascade');
            $table->string('eNew_crSanct_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('edited_new_created_sanctions_tbl');
    }
}
