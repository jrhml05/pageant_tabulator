<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Segments the tabulator has opened to judges. Segment keys come from config/pageant.php.
        Schema::create('open_segments', function (Blueprint $table) {
            $table->string('segment', 32)->primary();
            $table->timestamps();
        });

        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->string('segment', 32);
            $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
            $table->foreignId('judge_id')->constrained('users')->cascadeOnDelete();
            $table->string('criterion', 32);
            $table->decimal('points', 5, 2)->nullable();
            $table->timestamps();

            $table->unique(['segment', 'candidate_id', 'judge_id', 'criterion']);
        });

        // A judge locks a whole segment at once, both divisions together.
        Schema::create('score_locks', function (Blueprint $table) {
            $table->id();
            $table->string('segment', 32);
            $table->foreignId('judge_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['segment', 'judge_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('score_locks');
        Schema::dropIfExists('scores');
        Schema::dropIfExists('open_segments');
    }
};
