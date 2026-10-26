<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // The board's order for candidates tied on a segment's average. It holds only while the
        // same candidates are tied on the same average (`candidates`, `average`).
        Schema::create('tie_decisions', function (Blueprint $table) {
            $table->id();
            $table->string('segment', 32);
            $table->string('division', 8);
            $table->unsignedSmallInteger('rank');
            $table->integer('average');
            $table->json('candidates');
            $table->json('order');
            $table->timestamps();

            $table->unique(['segment', 'division', 'rank']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tie_decisions');
    }
};
