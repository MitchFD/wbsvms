<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeletedviolationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deleted_violations_tbl', function (Blueprint $table) {
            $table->id('del_id');
            $table->unsignedBigInteger('from_violation_id');
                // $table->foreign('from_violation_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('del_violation_status');    
            $table->tinyInteger('del_offense_count');    
            $table->json('del_minor_off')->nullable();
            $table->json('del_less_serious_off')->nullable();
            $table->json('del_other_off')->nullable();
            $table->timestamp('del_created_at')->format('Y-m-d H:i:s')->nullable();
            $table->timestamp('del_cleared_at')->format('Y-m-d H:i:s')->nullable();
            $table->unsignedBigInteger('del_stud_num');
                // $table->foreign('del_stud_num')->references('stud_num')->on('registered_students_tbl')->onDelete('cascade');
            $table->unsignedBigInteger('del_from_sanct_id');
                // $table->foreign('del_from_sanct_id')->references('sanct_id')->on('sanctions_tbl')->onDelete('cascade');
            $table->unsignedBigInteger('del_respo_user_id');
                // $table->foreign('del_respo_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('reason_deletion')->nullable();
            $table->tinyInteger('count_selected_off');
            $table->timestamp('deleted_at')->format('Y-m-d H:i:s')->nullable();
            $table->unsignedBigInteger('respo_user_id');
                // $table->foreign('respo_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deleted_violations_tbl');
    }
}