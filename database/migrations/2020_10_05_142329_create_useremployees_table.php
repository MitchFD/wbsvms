<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUseremployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_employees_tbl', function (Blueprint $table) {
            $table->unsignedBigInteger('uEmp_id')->primary();
                // $table->foreign('uEmp_id')->references('user_sdca_id')->on('users')->onDelete('cascade');
            $table->string('uEmp_job_desc')->nullable();    
            $table->string('uEmp_dept')->nullable();    
            $table->string('uEmp_phnum')->nullable();    
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
        Schema::dropIfExists('user_employees_tbl');
    }
}
