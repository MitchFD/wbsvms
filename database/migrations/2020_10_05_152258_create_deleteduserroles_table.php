<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeleteduserrolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deleted_user_roles_tbl', function (Blueprint $table) {
            $table->id('del_uRole_id');
            $table->string('del_uRole_status');    
            $table->string('del_uRole_type');    
            $table->string('del_uRole');    
            $table->json('del_uRole_access');
            $table->tinyInteger('del_assUsers_count')->nullable(); 
            $table->timestamp('del_created_at')->format('Y-m-d H:i:s')->nullable();
            $table->string('reason_deletion')->nullable();
            $table->unsignedBigInteger('respo_user_id');
                // $table->foreign('respo_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('deleted_at')->format('Y-m-d H:i:s')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deleted_user_roles_tbl');
    }
}
