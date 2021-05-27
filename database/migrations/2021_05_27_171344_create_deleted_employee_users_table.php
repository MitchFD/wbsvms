<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeletedEmployeeUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deleted_employee_users_tbl', function (Blueprint $table) {
            $table->id('del_id');
            $table->tinyInteger('del_Status')->default(1);  
            $table->string('del_user_role')->nullable();   
            $table->string('del_user_type')->nullable();  
            $table->string('del_user_image')->nullable();  
            $table->string('del_user_lname')->nullable();    
            $table->string('del_user_fname')->nullable(); 
            $table->string('del_user_gender')->nullable(); 
            $table->string('del_user_email')->nullable(); 
            $table->unsignedBigInteger('del_user_sdca_id')->nullable();   
            $table->string('del_uEmp_job_desc')->nullable();    
            $table->string('del_uEmp_dept')->nullable();    
            $table->unsignedBigInteger('del_uEmp_phnum')->nullable();  
            $table->unsignedBigInteger('del_created_by'); 
                // $table->foreign('del_created_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('del_created_at')->format('Y-m-d H:i:s')->nullable();
            $table->string('reason_deletion')->nullable();
            $table->unsignedBigInteger('respo_user_id'); 
                // $table->foreign('respo_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('deleted_at')->format('Y-m-d H:i:s')->nullable(); 
            $table->unsignedBigInteger('perm_deleted_by'); 
                // $table->foreign('perm_deleted_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('perm_deleted_at')->format('Y-m-d H:i:s')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deleted_employee_users_tbl');
    }
}
