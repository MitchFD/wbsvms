<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            // $table->unsignedBigInteger('id')->primary();
            $table->id();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('user_role')->nullable();
            $table->string('user_status')->nullable();    
            $table->string('user_role_status')->nullable();    
            $table->string('user_type');  
            $table->unsignedBigInteger('user_sdca_id'); 
            $table->string('user_image')->nullable();    
            $table->string('user_lname');    
            $table->string('user_fname'); 
            $table->string('user_gender'); 
            $table->unsignedBigInteger('registered_by'); 
                // $table->foreign('registered_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('created_at')->format('Y-m-d H:i:s')->nullable();
            $table->timestamp('updated_at')->format('Y-m-d H:i:s')->nullable();
            $table->timestamp('deleted_at')->format('Y-m-d H:i:s')->nullable();
            $table->timestamp('recovered_at')->format('Y-m-d H:i:s')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
