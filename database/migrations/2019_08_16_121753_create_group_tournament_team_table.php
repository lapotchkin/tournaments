<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupTournamentTeamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('groupTournament_team')) {
            return;
        }

        Schema::create('groupTournament_team', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';

            $table->integer('tournament_id')->comment('ID турнира');
            $table->integer('team_id')->comment('ID команды');
            $table->tinyInteger('division')->comment('Группа');
            $table->dateTime('createdAt')->default('CURRENT_TIMESTAMP');
            $table->softDeletes('deletedAt');

            $table->primary(['tournament_id', 'team_id']);
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
        Schema::dropIfExists('groupTournament_team');
    }
}
