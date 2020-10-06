<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEditednewsanctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edited_new_sanctions_tbl', function (Blueprint $table) {
            $table->id('eNew_id');
            $table->unsignedBigInteger('edi_from_eOld_id');
                // $table->foreign('edi_from_eOld_id')->references('eOld_id')->on('edited_old_sanctions_tbl')->onDelete('cascade');
            $table->timestamp('edited_at')->format('Y-m-d H:i:s')->nullable();
            $table->json('eNew_sel_violation_ids')->nullable();
            $table->string('eNew_sanct_status');
            $table->string('eNew_sanct_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('edited_new_sanctions_tbl');
    }
}
