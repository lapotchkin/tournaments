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
            $table->collation = 'utf8_unicode_ci';

            $table->integer('tournament_id')->comment('ID турнира');
            $table->integer('player_id')->comment('ID игрока');
            $table->string('club_id')->comment('ID клуба');
            $table->tinyInteger('division')->comment('Группа');
            $table->dateTime('createdAt')->default('CURRENT_TIMESTAMP');
            $table->softDeletes('deletedAt');

            $table->primary(['tournament_id', 'player_id']);
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
        Schema::dropIfExists('personalTournament_player');
    }
}
