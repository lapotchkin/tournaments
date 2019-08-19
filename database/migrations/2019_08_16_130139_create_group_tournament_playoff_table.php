<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupTournamentPlayoffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('groupTournamentPlayoff')) {
            return;
        }

        Schema::create('groupTournamentPlayoff', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->integerIncrements('id');
            $table->integer('tournament_id')->comment('ID турнира');
            $table->tinyInteger('round')->comment('Раунд');
            $table->tinyInteger('pair')->comment('Пара');
            $table->integer('team_one_id')->nullable()->comment('ID хозяев');
            $table->integer('team_two_id')->nullable()->comment('ID гостей');
            $table->dateTime('createdAt')->default('CURRENT_TIMESTAMP');
            $table->softDeletes('deletedAt');

            $table->unique(['tournament_id', 'round', 'pair']);
        });

        Schema::table('club', function (Blueprint $table) {
            $table->foreign('tournament_id')->references('id')->on('groupTournament');
            $table->foreign('team_one_id')->references('id')->on('team');
            $table->foreign('team_two_id')->references('id')->on('team');
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
            $table->dropForeign(['team_one_id']);
            $table->dropForeign(['team_two_id']);
        });
        Schema::dropIfExists('groupTournamentPlayoff');
    }
}
