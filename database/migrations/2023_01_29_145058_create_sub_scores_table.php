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
        Schema::create('sub_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('score_id');
            $table->unsignedBigInteger('barangay_id');
            $table->unsignedBigInteger('candidate_id');
            $table->unsignedBigInteger('stage_id');
            $table->unsignedBigInteger('judge_id');
            $table->decimal('mastery_and_execution',16)->nullable();
            $table->decimal('originality',16)->nullable();
            $table->decimal('audience_impact',16)->nullable();
            $table->boolean('is_lock')->default(0);
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_scores');
    }
};
