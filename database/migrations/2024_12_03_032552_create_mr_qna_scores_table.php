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
        Schema::create('mr_qna_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidate_id');
            $table->unsignedBigInteger('judge_id');
            $table->decimal('relevance', 16)->nullable();
            $table->decimal('delivery', 16)->nullable();
            $table->decimal('content', 16)->nullable();
            $table->decimal('audience_impact', 16)->nullable();
            $table->boolean('is_lock')->default(0);
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
        Schema::dropIfExists('mr_qna_scores');
    }
};
