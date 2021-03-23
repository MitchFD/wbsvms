<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEditedolduserrolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edited_old_user_roles_tbl', function (Blueprint $table) {
            $table->id('eOld_uRole_id');
            $table->unsignedBigInteger('from_uRole_id');
                // $table->foreign('from_uRole_id')->references('uRole_id')->on('user_roles_tbl')->onDelete('cascade');
            $table->string('old_uRole_status');    
            $table->string('old_uRole_type');    
            $table->string('old_uRole');    
            $table->json('old_uRole_access');
            $table->tinyInteger('old_assUsers_count')->nullable(); 
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
        Schema::dropIfExists('edited_old_user_roles_tbl');
    }
}
