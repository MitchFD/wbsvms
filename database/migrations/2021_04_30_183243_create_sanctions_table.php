<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSanctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sanctions_tbl', function (Blueprint $table) {
            $table->id('sanct_id');
            $table->unsignedBigInteger('stud_num');
                // $table->foreign('stud_num')->references('stud_num')->on('registered_students_tbl')->onDelete('cascade');
            $table->unsignedBigInteger('for_viola_id')->nullable();
            $table->string('sanct_status')->default('not completed');
            $table->string('sanct_details');
            $table->unsignedBigInteger('respo_user_id');
                // $table->foreign('respo_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('created_at')->format('Y-m-d H:i:s')->nullable();
            $table->timestamp('completed_at')->format('Y-m-d H:i:s')->nullable();
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
        Schema::dropIfExists('sanctions_tbl');
    }
}
