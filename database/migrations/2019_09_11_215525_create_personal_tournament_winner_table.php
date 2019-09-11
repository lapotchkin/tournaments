<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalTournamentWinnerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('personalTournamentWinner')) {
            return;
        }

        Schema::create('personalTournamentWinner', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->integerIncrements('id')->comment('ID');
            $table->integer('tournament_id')->comment('ID турнира');
            $table->integer('player_id')->comment('ID игрока');
            $table->integer('place')->comment('Место');
            $table->dateTime('createdAt');
            $table->softDeletes('deletedAt');
        });

        Schema::table('personalTournamentWinner', function (Blueprint $table) {
            $table->foreign('tournament_id')->references('id')->on('personalTournament');
            $table->foreign('player_id')->references('id')->on('player');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('personalTournamentWinner', function (Blueprint $table) {
            $table->dropForeign(['tournament_id']);
            $table->dropForeign(['player_id']);
        });
        Schema::dropIfExists('personalTournamentWinner');
    }
}
