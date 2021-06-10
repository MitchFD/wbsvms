<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreatedOffensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('created_offenses_tbl', function (Blueprint $table) {
            $table->id('crOffense_id');
            $table->string('crOffense_category')->nullable();
            $table->string('crOffense_type')->default('custom');
            $table->text('crOffense_details')->nullable();
            $table->unsignedBigInteger('respo_user_id')->nullable();
                // $table->foreign('respo_user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('created_offenses_tbl');
    }
}
