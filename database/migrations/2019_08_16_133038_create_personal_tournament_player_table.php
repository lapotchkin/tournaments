<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalTournamentPlayerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('personalTournament_player')) {
            return;
        }

        Schema::create('personalTournament_player', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->integer('tournament_id')->comment('ID турнира');
            $table->integer('player_id')->comment('ID игрока');
            $table->string('club_id')->comment('ID клуба');
            $table->tinyInteger('division')->comment('Группа');
            $table->dateTime('createdAt');
            $table->softDeletes('deletedAt');

            $table->primary(['tournament_id', 'player_id']);
        });

        Schema::table('club', function (Blueprint $table) {
            $table->foreign('tournament_id')->references('id')->on('personalTournament');
            $table->foreign('player_id')->references('id')->on('player');
            $table->foreign('club_id')->references('id')->on('club');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_team', function (Blueprint $table) {
            $table->dropForeign(['tournament_id']);
            $table->dropForeign(['player_id']);
            $table->dropForeign(['club_id']);
        });
        Schema::dropIfExists('personalTournament_player');
    }
}
