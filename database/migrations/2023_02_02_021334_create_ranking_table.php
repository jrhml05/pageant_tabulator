<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rankings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('judge_id');
            $table->unsignedBigInteger('barangay_id');
            $table->unsignedBigInteger('prepageant_rank')->nullable();
            $table->unsignedBigInteger('prelim_rank')->nullable();
            $table->unsignedBigInteger('semi_rank')->nullable();
            $table->unsignedBigInteger('final_rank')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rankings');
    }
};
