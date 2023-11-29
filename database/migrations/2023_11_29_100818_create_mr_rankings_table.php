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
        Schema::create('mr_rankings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidate_id');
            $table->unsignedBigInteger('judge_id');
            $table->unsignedBigInteger('prod_num');
            $table->unsignedBigInteger('sports_wear');
            $table->unsignedBigInteger('talent');
            $table->unsignedBigInteger('prepageant');
            $table->unsignedBigInteger('casual_wear');
            $table->unsignedBigInteger('formal_wear');
            $table->unsignedBigInteger('prelim');
            $table->unsignedBigInteger('final');
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
        Schema::dropIfExists('mr_rankings');
    }
};
