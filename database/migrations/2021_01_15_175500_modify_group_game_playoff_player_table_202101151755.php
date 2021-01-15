<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class ModifyGroupGamePlayoffPlayerTable
 */
class ModifyGroupGamePlayoffPlayerTable202101151755 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('groupGamePlayoff_player', 'shot_attempts')) {
            return;
        }

        Schema::table('groupGamePlayoff_player', function (Blueprint $table) {
            $table->tinyInteger('shot_attempts')
                ->nullable()
                ->after('pokechecks')
                ->default(0)
                ->comment('Попытки бросков');
            $table->tinyInteger('deflections')
                ->nullable()
                ->after('shot_attempts')
                ->default(0)
                ->comment('Отклонения');
            $table->tinyInteger('interceptions')
                ->nullable()
                ->after('deflections')
                ->default(0)
                ->comment('Перехваты шайбы');
            $table->tinyInteger('pass_attempts')
                ->nullable()
                ->after('interceptions')
                ->default(0)
                ->comment('Попытки паса');
            $table->tinyInteger('passes')
                ->nullable()
                ->after('pass_attempts')
                ->default(0)
                ->comment('Удачные пасы');
            $table->tinyInteger('saucer_passes')
                ->nullable()
                ->after('passes')
                ->default(0)
                ->comment('Пасы подкидкой');
            $table->tinyInteger('clear_zone')
                ->nullable()
                ->after('saucer_passes')
                ->default(0)
                ->comment('Выбросы шайбы из зоны');
            $table->tinyInteger('possession')
                ->nullable()
                ->after('clear_zone')
                ->default(0)
                ->comment('Владение шайбой в секундах');
            $table->tinyInteger('penalties_drawn')
                ->nullable()
                ->after('possession')
                ->default(0)
                ->comment('Количество штрафов');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasColumn('groupGamePlayoff_player', 'shot_attempts')) {
            return;
        }

        Schema::table('groupGamePlayoff_player', function (Blueprint $table) {
            $table->dropColumn([
                'shot_attempts',
                'deflections',
                'interceptions',
                'pass_attempts',
                'passes',
                'saucer_passes',
                'clear_zone',
                'possession',
                'penalties_drawn',
            ]);
        });
    }
}
