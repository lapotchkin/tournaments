<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateGroupGameRegularPlayerTable
 */
class CreateGroupGameRegularPlayerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('groupGameRegular_player')) {
            return;
        }

        Schema::create('groupGameRegular_player', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->integerIncrements('id');
            $table->integer('game_id')->comment('ID игры');
            $table->integer('team_id')->comment('ID команды');
            $table->integer('player_id')->comment('ID игрока');
            $table->tinyInteger('goals')->nullable()->comment('Голы');
            $table->tinyInteger('assists')->nullable()->comment('передачи');
            $table->tinyInteger('isGoalie')->default(0)->comment('Вратарь');
            $table->dateTime('createdAt');
            $table->softDeletes('deletedAt');

            $table->unique(['game_id', 'team_id', 'player_id']);
        });

        Schema::table('club', function (Blueprint $table) {
            $table->foreign('game_id')->references('id')->on('groupGameRegular');
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
            $table->dropForeign(['game_id']);
            $table->dropForeign(['team_id']);
            $table->dropForeign(['player_id']);
        });
        Schema::dropIfExists('groupGameRegular_player');
    }
}
