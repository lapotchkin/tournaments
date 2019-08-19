<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamPlayerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('team_player')) {
            return;
        }

        Schema::create('team_player', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->integer('team_id')->comment('ID команды');
            $table->integer('player_id')->comment('ID игрока');
            $table->tinyInteger('isCaptain')->default(0)->comment('Капитан');
            $table->dateTime('createdAt');
            $table->softDeletes('deletedAt');

            $table->primary(['team_id', 'player_id']);
        });

        Schema::table('club', function (Blueprint $table) {
            $table->foreign('team_id')->references('id')->on('team');
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
        Schema::table('app_team', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropForeign(['player_id']);
        });
        Schema::dropIfExists('team_player');
    }
}
