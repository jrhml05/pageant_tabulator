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
        Schema::create('mr_prelim_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidate_id');
            $table->unsignedBigInteger('judge_id');
            $table->decimal('national_costume', 16)->nullable();
            $table->decimal('dept_uniform', 16)->nullable();
            $table->decimal('swim_wear', 16)->nullable();
            $table->decimal('formal_wear', 16)->nullable();
            $table->decimal('qna', 16)->nullable();
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
        Schema::dropIfExists('mr_prelim_scores');
    }
};
