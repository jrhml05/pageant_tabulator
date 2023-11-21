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
        Schema::table('rankings', function (Blueprint $table) {
            $table->unsignedBigInteger('swim_suit_rank')->nullable();
            $table->unsignedBigInteger('evening_gown_rank')->nullable();
        });        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
