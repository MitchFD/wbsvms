<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserstudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_students_tbl', function (Blueprint $table) {
            $table->unsignedBigInteger('uStud_num')->primary();
                // $table->foreign('uStud_num')->references('id')->on('users')->onDelete('cascade');
            $table->string('uStud_school');    
            $table->string('uStud_program');    
            $table->string('uStud_yearlvl');    
            $table->string('uStud_section');    
            $table->string('uStud_phnum')->nullable();    
            $table->timestamp('created_at')->format('Y-m-d H:i:s')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_students_tbl');
    }
}
