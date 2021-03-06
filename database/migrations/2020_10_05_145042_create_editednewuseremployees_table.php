<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEditednewuseremployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edited_new_emp_users_tbl', function (Blueprint $table) {
            $table->id('eNewEmp_id');
            $table->unsignedBigInteger('from_eOldEmp_id');
                // $table->foreign('from_eOldEmp_id')->references('eOldEmp_id')->on('edited_old_emp_users_tbl')->onDelete('cascade');
            $table->string('eNew_email');
            // $table->string('eNew_password');
            $table->string('eNew_uRole');
            // $table->string('eNew_user_status');    
            // $table->string('eNew_uRole_status');    
            $table->string('eNew_user_type')->nullable();    
            $table->string('eNew_user_image')->nullable();    
            $table->string('eNew_user_lname')->nullable();    
            $table->string('eNew_user_fname')->nullable(); 
            $table->string('eNew_user_gender')->nullable(); 
            $table->unsignedBigInteger('eNew_sdca_id')->nullable(); 
            $table->string('eNew_job_desc')->nullable();    
            $table->string('eNew_dept')->nullable();    
            $table->unsignedBigInteger('eNew_phnum')->nullable(); 
            $table->timestamp('edited_at')->format('Y-m-d H:i:s')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('edited_new_emp_users_tbl');
    }
}
