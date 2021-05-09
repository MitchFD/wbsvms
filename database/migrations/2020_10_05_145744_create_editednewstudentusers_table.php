<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEditednewstudentusersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edited_new_stud_users_tbl', function (Blueprint $table) {
            $table->id('eNewStud_id');
            $table->unsignedBigInteger('from_eOldStud_id');
                // $table->foreign('from_eOldStud_id')->references('eOldStud_id')->on('edited_old_stud_users_tbl')->onDelete('cascade');
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
            $table->string('eNew_school')->nullable();    
            $table->string('eNew_program')->nullable();    
            $table->string('eNew_yearlvl')->nullable();    
            $table->string('eNew_section')->nullable();    
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
        Schema::dropIfExists('edited_new_stud_users_tbl');
    }
}
