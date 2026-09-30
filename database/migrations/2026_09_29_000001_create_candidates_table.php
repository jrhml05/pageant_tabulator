<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->enum('division', ['mr', 'ms']);
            // Judges pair Mr. and Ms. candidates by this number; photos are named by it too.
            $table->unsignedSmallInteger('number');
            $table->string('school')->nullable();
            $table->boolean('is_finalist')->default(false);
            // Random call order for the top 5 announcement, set when finalists are saved.
            $table->unsignedTinyInteger('announce_order')->nullable();
            $table->timestamps();

            $table->unique(['division', 'number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
