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
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('judge_id');
            $table->unsignedBigInteger('barangay_id');
            $table->unsignedBigInteger('candidate_id');
            $table->unsignedBigInteger('stage_id');
            $table->decimal('talent',16)->nullable();
            $table->decimal('beauty',16)->nullable();
            $table->decimal('poise',16)->nullable();
            $table->decimal('intelligence',16)->nullable();
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
        Schema::dropIfExists('scores');
    }
};
