<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateGroupTournamentWinnerTable
 */
class CreateGroupTournamentWinnerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('groupTournamentWinner')) {
            return;
        }

        Schema::create('groupTournamentWinner', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->integerIncrements('id')->comment('ID');
            $table->integer('tournament_id')->comment('ID турнира');
            $table->integer('team_id')->comment('ID команды');
            $table->integer('place')->comment('Место');
            $table->dateTime('createdAt');
            $table->softDeletes('deletedAt');
        });

        Schema::table('groupTournamentWinner', function (Blueprint $table) {
            $table->foreign('tournament_id')->references('id')->on('groupTournament');
            $table->foreign('team_id')->references('id')->on('team');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('groupTournamentWinner', function (Blueprint $table) {
            $table->dropForeign(['tournament_id']);
            $table->dropForeign(['team_id']);
        });
        Schema::dropIfExists('groupTournamentWinner');
    }
}
