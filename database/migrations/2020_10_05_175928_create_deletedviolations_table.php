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
            $table->tinyInteger('del_status')->default(1);  
            $table->unsignedBigInteger('from_viola_id');
                // $table->foreign('from_violation_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('del_recorded_at')->format('Y-m-d H:i:s')->nullable();
            $table->string('del_violation_status'); 
            $table->tinyInteger('del_offense_count');    
            $table->json('del_minor_off')->nullable();
            $table->json('del_less_serious_off')->nullable();
            $table->json('del_other_off')->nullable();
            $table->unsignedBigInteger('del_stud_num');
                // $table->foreign('del_stud_num')->references('stud_num')->on('registered_students_tbl')->onDelete('cascade');
            $table->tinyInteger('del_has_sanction')->default(0);
            $table->tinyInteger('del_has_sanct_count')->default(0);
            $table->unsignedBigInteger('del_respo_user_id');
                // $table->foreign('del_respo_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('del_cleared_at')->format('Y-m-d H:i:s')->nullable();
            $table->string('reason_deletion')->nullable();
            $table->unsignedBigInteger('respo_user_id');
                // $table->foreign('respo_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('deleted_at')->format('Y-m-d H:i:s')->nullable();
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
