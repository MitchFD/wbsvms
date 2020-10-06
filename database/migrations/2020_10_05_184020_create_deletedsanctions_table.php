<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeletedsanctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deleted_sanctions_tbl', function (Blueprint $table) {
            $table->id('del_id');
            $table->unsignedBigInteger('del_from_sanct_id');
                // $table->foreign('del_from_sanct_id')->references('sanct_id')->on('sanctions_tbl')->onDelete('cascade');
            $table->unsignedBigInteger('del_by_user_id');
            // $table->foreign('del_by_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('deleted_at')->format('Y-m-d H:i:s')->nullable();
            $table->string('reason_deletion')->nullable();
            $table->unsignedBigInteger('del_stud_num');
                // $table->foreign('del_stud_num')->references('stud_num')->on('registered_students_tbl')->onDelete('cascade');
            $table->string('del_sanct_status');
            $table->string('del_sanct_details');
            $table->json('del_sel_viola_ids')->nullable();
            $table->unsignedBigInteger('del_respo_user_id');
            // $table->foreign('del_respo_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('del_created_at')->format('Y-m-d H:i:s')->nullable();
            $table->timestamp('del_completed_at')->format('Y-m-d H:i:s')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deleted_sanctions_tbl');
    }
}
