<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasswordupdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('password_updates_tbl', function (Blueprint $table) {
            $table->id('pass_upd_id');
            $table->unsignedBigInteger('sel_user_id');
                // $table->foreign('sel_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('upd_by_user_id');
            // $table->foreign('upd_by_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('reason_update')->nullable();  
            $table->timestamp('updated_at')->format('Y-m-d H:i:s')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('password_updates_tbl');
    }
}
