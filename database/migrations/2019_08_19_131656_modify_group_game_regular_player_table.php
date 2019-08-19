<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class ModifyGroupGameRegularPlayerTable
 */
class ModifyGroupGameRegularPlayerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('groupGameRegular_player', 'class_id')) {
            return;
        }

        Schema::table('groupGameRegular_player', function (Blueprint $table) {
            $table->tinyInteger('class_id')
                ->nullable()
                ->after('player_id')
                ->comment('ID класса игрока');
            $table->tinyInteger('position_id')
                ->nullable()
                ->after('class_id')
                ->comment('ID позиции игрока');
            $table->integer('time_on_ice_seconds')
                ->nullable()
                ->after('position_id')
                ->comment('Игровое время в секундах');
            $table->tinyInteger('power_play_goals')
                ->nullable()
                ->after('goals')
                ->comment('Голы в большинстве');
            $table->tinyInteger('shorthanded_goals')
                ->nullable()
                ->after('power_play_goals')
                ->comment('Голы в меньшиестве');
            $table->tinyInteger('game_winning_goals')
                ->nullable()
                ->after('shorthanded_goals')
                ->comment('Победный гол');
            $table->tinyInteger('shots')
                ->nullable()
                ->after('game_winning_goals')
                ->comment('Броски в створ ворот');
            $table->tinyInteger('plus_minus')
                ->nullable()
                ->after('shots')
                ->comment('+/-');
            $table->tinyInteger('faceoff_win')
                ->nullable()
                ->after('plus_minus')
                ->comment('Выиграно вбрасываний');
            $table->tinyInteger('faceoff_lose')
                ->nullable()
                ->after('faceoff_win')
                ->comment('Проиграно вбрасываний');
            $table->tinyInteger('blocks')
                ->nullable()
                ->after('faceoff_lose')
                ->comment('Заблокировал бросков');
            $table->tinyInteger('giveaways')
                ->nullable()
                ->after('blocks')
                ->comment('Потери шайбы');
            $table->tinyInteger('takeaways')
                ->nullable()
                ->after('giveaways')
                ->comment('Перехваты шайбы');
            $table->tinyInteger('hits')
                ->nullable()
                ->after('takeaways')
                ->comment('Силовые приёмы');
            $table->tinyInteger('penalty_minutes')
                ->nullable()
                ->after('hits')
                ->comment('Штрафные минуты');
            $table->decimal('rating_defense', 5, 2)
                ->nullable()
                ->after('penalty_minutes')
                ->comment('Рейтинг защиты');
            $table->decimal('rating_offense', 5, 2)
                ->nullable()
                ->after('rating_defense')
                ->comment('Рейтинг нападения');
            $table->decimal('rating_teamplay', 5, 2)
                ->nullable()
                ->after('rating_offense')
                ->comment('Рейтинг командной игры');
            $table->tinyInteger('shots_on_goal')
                ->nullable()
                ->after('rating_teamplay')
                ->comment('Броски по воротам вратаря');
            $table->tinyInteger('saves')
                ->nullable()
                ->after('shots_on_goal')
                ->comment('Отбито бросков вратарём');
            $table->tinyInteger('breakeaway_shots')
                ->nullable()
                ->after('saves')
                ->comment('Броски по воротам вратаря 1 на 1');
            $table->tinyInteger('breakeaway_saves')
                ->nullable()
                ->after('breakeaway_shots')
                ->comment('Отбито бросков вратарём_1_на_1');
            $table->tinyInteger('penalty_shots')
                ->nullable()
                ->after('breakeaway_saves')
                ->comment('Буллиты');
            $table->tinyInteger('penalty_saves')
                ->nullable()
                ->after('penalty_shots')
                ->comment('Отбито буллитов');
            $table->tinyInteger('goals_against')
                ->nullable()
                ->after('penalty_saves')
                ->comment('Пропущено голов');
            $table->tinyInteger('pokechecks')
                ->nullable()
                ->after('goals_against')
                ->comment('Покчек (тычки клюшкой)');
            $table->tinyInteger('isWin')
                ->nullable()
                ->after('pokechecks')
                ->comment('Победа');

            $table->foreign('class_id')->references('id')->on('player_class');
            $table->foreign('position_id')->references('id')->on('player_position');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('groupGameRegular_player', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
            $table->dropForeign(['position_id']);

            $table->dropColumn([
                'class_id',
                'position_id',
                'time_on_ice_seconds',
                'power_play_goals',
                'shorthanded_goals',
                'game_winning_goals',
                'shots',
                'plus_minus',
                'faceoff_win',
                'faceoff_lose',
                'blocks',
                'giveaways',
                'takeaways',
                'hits',
                'penalty_minutes',
                'rating_defense',
                'rating_offense',
                'rating_teamplay',
                'shots_on_goal',
                'saves',
                'breakeaway_shots',
                'breakeaway_saves',
                'penalty_shots',
                'penalty_saves',
                'goals_against',
                'pokechecks',
                'isWin',
            ]);
        });
    }
}
