<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateGroupGameRegularTable
 */
class CreateGroupGameRegularTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('groupGameRegular')) {
            return;
        }

        Schema::create('groupGameRegular', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->integerIncrements('id');
            $table->integer('tournament_id')->comment('ID турнира');
            $table->integer('round')->comment('Тур');
            $table->integer('home_team_id')->comment('ID хозяев');
            $table->integer('away_team_id')->comment('ID гостей');
            $table->tinyInteger('home_score')->nullable()->comment('Голы хозяев');
            $table->tinyInteger('away_score')->nullable()->comment('Голы гостей');
            $table->tinyInteger('home_shot')->nullable()->comment('Броски хозяев');
            $table->tinyInteger('away_shot')->nullable()->comment('Броски гостей');
            $table->tinyInteger('home_hit')->nullable()->comment('Силовые хозяев');
            $table->tinyInteger('away_hit')->nullable()->comment('Силовые гостей');
            $table->time('home_attack_time')->nullable()->comment('Время в атаке хозяев');
            $table->time('away_attack_time')->nullable()->comment('Время в атаке гостей');
            $table->decimal('home_pass_percent', 3, 1)->nullable()->comment('Процент паса хозяев');
            $table->decimal('away_pass_percent', 3, 1)->nullable()->comment('Процент паса гостей');
            $table->tinyInteger('home_faceoff')->nullable()->comment('Выигранные вбрасывания хозяев');
            $table->tinyInteger('away_faceoff')->nullable()->comment('Выигранные вбрасывания гостей');
            $table->time('home_penalty_time')->nullable()->comment('Штрафные минуты хозяев');
            $table->time('away_penalty_time')->nullable()->comment('Штрафные минуты гостей');
            $table->tinyInteger('home_penalty_total')->nullable()->comment('Всего попыток большинства хозяев');
            $table->tinyInteger('away_penalty_total')->nullable()->comment('Всего попыток большинства гостей');
            $table->tinyInteger('home_penalty_success')->nullable()->comment('Реализовано попыток большинства хозяев');
            $table->tinyInteger('away_penalty_success')->nullable()->comment('Реализовано попыток большинства гостей');
            $table->time('home_powerplay_time')->nullable()->comment('Время в большинстве хозяев');
            $table->time('away_powerplay_time')->nullable()->comment('Время в большинстве гостей');
            $table->tinyInteger('home_shorthanded_goal')->nullable()->comment('Голы в меньшинстве хозяев');
            $table->tinyInteger('away_shorthanded_goal')->nullable()->comment('Голы в меньшинстве гостей');
            $table->tinyInteger('isOvertime')->default(0)->comment('Игра завершилась в овертайме');
            $table->tinyInteger('isShootout')->default(0)->comment('Игра завершилась в серии буллитов');
            $table->tinyInteger('isTechnicalDefeat')->default(0)->comment('Игра завершилась техническим поражением');
            $table->dateTime('createdAt');
            $table->dateTime('playedAt')->nullable()->comment('Дата проведения игры');
            $table->dateTime('updatedAt')->nullable();
            $table->softDeletes('deletedAt');

        });

        Schema::table('club', function (Blueprint $table) {
            $table->foreign('tournament_id')->references('id')->on('groupTournament');
            $table->foreign('home_team_id')->references('id')->on('team');
            $table->foreign('away_team_id')->references('id')->on('team');
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
            $table->dropForeign(['home_team_id']);
            $table->dropForeign(['away_team_id']);
        });
        Schema::dropIfExists('groupGameRegular');
    }
}
