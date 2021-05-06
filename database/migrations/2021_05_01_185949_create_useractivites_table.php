<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUseractivitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_activity_tbl', function (Blueprint $table) {
            $table->id('act_id');
            $table->timestamp('created_at')->format('Y-m-d H:i:s')->nullable();
            $table->unsignedBigInteger('act_respo_user_id');
                // $table->foreign('act_respo_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('act_respo_users_lname');
            $table->string('act_respo_users_fname');
            $table->string('act_type');
            $table->string('act_details');
            $table->unsignedBigInteger('act_affected_id')->nullable();
            $table->json('act_affected_sanct_ids')->nullable();
            $table->json('act_deleted_viola_ids')->nullable();
            $table->json('act_perm_deleted_viola_ids')->nullable();
            $table->json('act_recovered_viola_ids')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_activity_tbl');
    }
}
