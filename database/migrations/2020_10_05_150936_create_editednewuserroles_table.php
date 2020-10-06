<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEditednewuserrolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edited_new_user_roles_tbl', function (Blueprint $table) {
            $table->id('eNew_uRole_id');
            $table->unsignedBigInteger('from_eOld_uRole_id');
                // $table->foreign('from_eOld_uRole_id')->references('eOld_uRole_id')->on('edited_old_user_roles_tbl')->onDelete('cascade');
            $table->string('new_uRole_status');    
            $table->string('new_uRole_type');    
            $table->string('new_uRole');    
            $table->json('new_uRole_access');
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
        Schema::dropIfExists('edited_new_user_roles_tbl');
    }
}
