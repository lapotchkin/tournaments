<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreatePersonalTournamentPlayoffTable
 */
class CreatePersonalTournamentPlayoffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('personalTournamentPlayoff')) {
            return;
        }

        Schema::create('personalTournamentPlayoff', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->integerIncrements('id');
            $table->integer('tournament_id')->comment('ID турнира');
            $table->tinyInteger('round')->comment('Круг');
            $table->tinyInteger('pair')->comment('Пара');
            $table->integer('player_one_id')->nullable()->comment('ID хозяина');
            $table->integer('player_two_id')->nullable()->comment('ID гостя');
            $table->dateTime('createdAt');
            $table->softDeletes('deletedAt');

            $table->unique(['tournament_id', 'round', 'pair']);
        });

        Schema::table('personalTournamentPlayoff', function (Blueprint $table) {
            $table->foreign('tournament_id')->references('id')->on('personalTournament');
            $table->foreign('player_one_id')->references('id')->on('player');
            $table->foreign('player_two_id')->references('id')->on('player');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('personalTournamentPlayoff', function (Blueprint $table) {
            $table->dropForeign(['tournament_id']);
            $table->dropForeign(['player_one_id']);
            $table->dropForeign(['player_two_id']);
        });
        Schema::dropIfExists('personalTournamentPlayoff');
    }
}
