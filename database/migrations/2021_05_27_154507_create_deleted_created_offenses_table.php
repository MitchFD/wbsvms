<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeletedCreatedOffensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deleted_created_offenses_tbl', function (Blueprint $table) {
            $table->id('del_id');
            $table->tinyInteger('del_Status')->default(1);
            $table->string('del_crOffense_category')->nullable();
            $table->string('del_crOffense_type')->nullable();
            $table->text('del_crOffense_details')->nullable();    
            $table->unsignedBigInteger('del_created_by')->nullable();
                // $table->foreign('del_created_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('del_created_at')->format('Y-m-d H:i:s')->nullable();
            $table->string('reason_deletion')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
                // $table->foreign('deleted_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('deleted_at')->format('Y-m-d H:i:s')->nullable();
            $table->unsignedBigInteger('perm_deleted_by')->nullable();
                // $table->foreign('perm_deleted_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('perm_deleted_at')->format('Y-m-d H:i:s')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deleted_created_offenses_tbl');
    }
}
