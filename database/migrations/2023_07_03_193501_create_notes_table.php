<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('notes', function (Blueprint $table) {
            $table->id()->nullable(false)->autoIncrement();
            $table->string('title');
            $table->text('content');
            $table->timestamps();
        });

        Schema::table('games', function (Blueprint $table) {
            $table->foreign('current_note_id')->references('id')->on('notes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropForeign('game_current_note_id_foreign');
        });
        Schema::dropIfExists('notes');
    }
};
