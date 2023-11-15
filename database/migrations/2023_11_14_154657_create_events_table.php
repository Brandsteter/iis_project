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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('is_approved')->default(false);
            $table->integer('capacity_max')->nullable();
            $table->integer('capacity_current')->default(0);
            $table->date('event_start');
            $table->date('event_end');

            $table->foreignId('place_id');
            $table->foreign('place_id')->references('id')->on('places')->onDelete('restrict');

            $table->string('description')->nullable();


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
        Schema::dropIfExists('events');
    }
};
