<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalGamePlayoffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('personalGamePlayoff')) {
            return;
        }

        Schema::create('personalGamePlayoff', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->integerIncrements('id');
            $table->integer('playoff_pair_id')->comment('ID пары');
            $table->tinyInteger('round')->nullable('Тур');
            $table->integer('home_player_id')->comment('Хозяин');
            $table->integer('away_player_id')->comment('Гость');
            $table->tinyInteger('home_score')->nullable()->comment('Голы хозяина');
            $table->tinyInteger('away_score')->nullable()->comment('Голы гостя');
            $table->tinyInteger('isTechnicalDefeat')->default(0)->comment('Игра завершилась техническим поражением');
            $table->dateTime('createdAt')->default('CURRENT_TIMESTAMP');
            $table->dateTime('playedAt')->nullable()->comment('Дата проведения игры');
            $table->dateTime('updatedAt')->nullable();
            $table->softDeletes('deletedAt');
        });

        Schema::table('club', function (Blueprint $table) {
            $table->foreign('playoff_pair_id')->references('id')->on('personalTournamentPlayoff');
            $table->foreign('home_player_id')->references('id')->on('player');
            $table->foreign('away_player_id')->references('id')->on('player');
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
            $table->dropForeign(['playoff_pair_id']);
            $table->dropForeign(['home_player_id']);
            $table->dropForeign(['away_player_id']);
        });
        Schema::dropIfExists('personalGamePlayoff');
    }
}
