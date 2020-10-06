<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeletedoldviolationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deleted_old_violations_tbl', function (Blueprint $table) {
            $table->id('dOldViola_id');
            $table->unsignedBigInteger('from_del_id');
                // $table->foreign('from_del_id')->references('del_id')->on('deleted_violations_tbl')->onDelete('cascade');
            $table->tinyInteger('dOld_offense_count');    
            $table->json('dOld_minor_off')->nullable();
            $table->json('dOld_less_serious_off')->nullable();
            $table->json('dOld_other_off')->nullable();
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
        Schema::dropIfExists('deleted_old_violations_tbl');
    }
}
