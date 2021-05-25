<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserrolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_roles_tbl', function (Blueprint $table) {
            $table->id('uRole_id');
            $table->string('uRole_status');    
            $table->string('uRole_type');    
            $table->string('uRole');    
            $table->json('uRole_access');
            $table->tinyInteger('assUsers_count')->default(0); 
            $table->unsignedBigInteger('created_by');
                // $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('user_roles_tbl');
    }
}
