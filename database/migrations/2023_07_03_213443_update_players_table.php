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
        Schema::table('players', function (Blueprint $table) {
            $table->string('game_id', 5)->nullable(false);

            $table->foreign('game_id')->references('id')->on('games');
        });

        Schema::table('notes', function (Blueprint $table) {
            $table->string('game_id', 5)->nullable(false);

            $table->foreign('game_id')->references('id')->on('games');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropForeign('notes_game_id_foreign');

            $table->dropColumn('game_id');
        });
        Schema::table('players', function (Blueprint $table) {
            $table->dropForeign('players_game_id_foreign');

            $table->dropColumn('game_id');
        });
    }
};
