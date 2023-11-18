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
        Schema::table('user_attends_event', function (Blueprint $table) {

            $table->dropForeign(['event_id']);

            $table->foreign('event_id')->references('id')->on('events')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_attends_event', function (Blueprint $table) {

            $table->dropForeign(['event_id']);
            $table->foreign('event_id')->references('id')->on('roles')->cascadeOnDelete();
        });
    }
};
