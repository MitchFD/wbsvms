<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEditedoldsanctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edited_old_sanctions_tbl', function (Blueprint $table) {
            $table->id('eOld_id');
            $table->unsignedBigInteger('edi_from_sanct_id');
                // $table->foreign('edi_from_sanct_id')->references('sanct_id')->on('sanctions_tbl')->onDelete('cascade');
            $table->unsignedBigInteger('edi_by_user_id');
                // $table->foreign('edi_by_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('edited_at')->format('Y-m-d H:i:s')->nullable();
            $table->string('eOld_sanct_status');
            $table->string('eOld_sanct_details');
            $table->timestamp('eOld_created_at')->format('Y-m-d H:i:s')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('edited_old_sanctions_tbl');
    }
}
