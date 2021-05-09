<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEditedolduseremployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edited_old_emp_users_tbl', function (Blueprint $table) {
            $table->id('eOldEmp_id');
            $table->unsignedBigInteger('from_user_id');
                // $table->foreign('from_user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->string('eOld_password');
            // $table->string('eOld_user_status');    
            // $table->string('eOld_uRole_status');    
            $table->string('eOld_uRole')->nullable();
            $table->string('eOld_email')->nullable();
            $table->string('eOld_user_type')->nullable();    
            $table->string('eOld_user_image')->nullable();    
            $table->string('eOld_user_lname')->nullable();    
            $table->string('eOld_user_fname')->nullable(); 
            $table->string('eOld_user_gender')->nullable(); 
            $table->unsignedBigInteger('eOld_sdca_id')->nullable(); 
            $table->string('eOld_job_desc')->nullable();    
            $table->string('eOld_dept')->nullable();    
            $table->unsignedBigInteger('eOld_phnum')->nullable(); 
            $table->unsignedBigInteger('respo_user_id'); 
                // $table->foreign('respo_user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('edited_old_emp_users_tbl');
    }
}
