<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registered_students_tbl', function (Blueprint $table) {
            $table->id('stud_num');
            $table->string('stud_lname');    
            $table->string('stud_fname');    
            $table->string('stud_image');
            $table->string('stud_course');
            $table->string('stud_yearlvl');
            $table->string('stud_section');
            $table->string('stud_school');
            $table->string('stud_age');
            $table->string('stud_sex');
            $table->string('stud_email');
            $table->string('stud_phnum')->nullable();
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
        Schema::dropIfExists('registered_students_tbl');
    }
}
