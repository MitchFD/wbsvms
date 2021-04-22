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
        Schema::create('students_tbl', function (Blueprint $table) {
            $table->id('Student_Number');
            $table->string('First_Name')->nullable();    
            $table->string('Middle_Name')->nullable();    
            $table->string('Last_Name')->nullable();
            $table->string('Gender')->nullable();
            $table->string('Age')->nullable();
            $table->string('Email')->nullable();
            $table->string('School_Name')->nullable();
            $table->string('Course')->nullable();
            $table->string('YearLevel')->nullable();
            $table->string('Student_Image')->nullable();
            $table->tinyInteger('Status')->default(1);
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
        Schema::dropIfExists('students_tbl');
    }
}
