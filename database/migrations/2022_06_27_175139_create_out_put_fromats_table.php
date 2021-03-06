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
        Schema::create('out_put_fromats', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->date('date'); //dateTime string
            $table->string('name');
            $table->time('original_start_time')->nullable();
            $table->time('original_end_time')->nullable(); //dateTime
            $table->time('round_up_start_time')->nullable();
            $table->time('round_down_end_time')->nullable();
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
        Schema::dropIfExists('out_put_fromats');
    }
};
