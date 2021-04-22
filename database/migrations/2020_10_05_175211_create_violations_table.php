<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViolationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('violations_tbl', function (Blueprint $table) {
            $table->id('viola_id');
            $table->timestamp('created_at')->format('Y-m-d H:i:s')->nullable();
            $table->string('violation_status')->default('not cleared');    
            $table->tinyInteger('offense_count');    
            $table->json('minor_off')->nullable();
            $table->json('less_serious_off')->nullable();
            $table->json('other_off')->nullable();
            $table->unsignedBigInteger('stud_num');
                // $table->foreign('stud_num')->references('stud_num')->on('registered_students_tbl')->onDelete('cascade');
            $table->unsignedBigInteger('from_sanct_id')->nullable();
                // $table->foreign('from_sanct_id')->references('sanct_id')->on('sanctions_tbl')->onDelete('cascade');
            $table->unsignedBigInteger('respo_user_id');
                // $table->foreign('respo_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('updated_at')->format('Y-m-d H:i:s')->nullable();
            $table->timestamp('recovered_at')->format('Y-m-d H:i:s')->nullable();
            $table->timestamp('cleared_at')->format('Y-m-d H:i:s')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('violations_tbl');
    }
}
