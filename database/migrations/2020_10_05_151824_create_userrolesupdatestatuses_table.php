<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserrolesupdatestatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_role_status_updates_tbl', function (Blueprint $table) {
            $table->id('uRoleStatUp_id');
            $table->unsignedBigInteger('from_uRole_id');
                // $table->foreign('from_uRole_id')->references('uRole_id')->on('user_roles_tbl')->onDelete('cascade');
            $table->string('updated_status');    
            $table->string('reason_update')->nullable();  
            $table->timestamp('updated_at')->format('Y-m-d H:i:s')->nullable();
            $table->unsignedBigInteger('updated_by');
                // $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_role_status_updates_tbl');
    }
}
