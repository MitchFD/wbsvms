<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEditedOldCreatedOffensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edited_old_created_offenses_tbl', function (Blueprint $table) {
            $table->id('eOld_id');
            $table->unsignedBigInteger('eOld_from_crOffense_id');
            // $table->foreign('eOld_from_crOffense_id')->references('crOffense_id')->on('created_offenses_tbl')->onDelete('cascade');
            $table->string('eOld_crOffense_details');
            $table->unsignedBigInteger('edited_by');
                // $table->foreign('edited_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('edited_at')->format('Y-m-d H:i:s')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('edited_old_created_offenses_tbl');
    }
}
