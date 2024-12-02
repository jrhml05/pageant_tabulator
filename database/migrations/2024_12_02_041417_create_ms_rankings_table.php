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
        Schema::create('ms_rankings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidate_id');
            $table->unsignedBigInteger('judge_id');
            $table->unsignedBigInteger('rave_wear');
            $table->unsignedBigInteger('talent');
            $table->unsignedBigInteger('prepageant');
            $table->unsignedBigInteger('national_costume');
            $table->unsignedBigInteger('dept_uniform');
            $table->unsignedBigInteger('swim_wear');
            $table->unsignedBigInteger('formal_wear');
            $table->unsignedBigInteger('qna');
            $table->unsignedBigInteger('pageant');
            $table->unsignedBigInteger('to_top_5');
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
        Schema::dropIfExists('ms_rankings');
    }
};
