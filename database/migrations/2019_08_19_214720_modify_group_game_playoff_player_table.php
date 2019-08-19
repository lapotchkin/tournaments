<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class ModifyGroupGamePlayoffPlayerTable
 */
class ModifyGroupGamePlayoffPlayerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('groupGamePlayoff_player', 'star')) {
            return;
        }

        Schema::table('groupGamePlayoff_player', function (Blueprint $table) {
            $table->tinyInteger('star')
                ->nullable()
                ->after('position_id')
                ->default(0)
                ->comment('Звезда матча');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('groupGamePlayoff_player', function (Blueprint $table) {
            $table->dropColumn([
                'star',
            ]);
        });
    }
}
