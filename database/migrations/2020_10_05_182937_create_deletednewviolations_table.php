<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeletednewviolationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deleted_new_violations_tbl', function (Blueprint $table) {
            $table->id('dNewViola_id');
            $table->unsignedBigInteger('from_dOldViola_id');
                // $table->foreign('from_dOldViola_id')->references('dOldViola_id')->on('deleted_old_violations_tbl')->onDelete('cascade');
            $table->tinyInteger('dNew_offense_count');    
            $table->json('dNew_minor_off')->nullable();
            $table->json('dNew_less_serious_off')->nullable();
            $table->json('dNew_other_off')->nullable();
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
        Schema::dropIfExists('deleted_new_violations_tbl');
    }
}
