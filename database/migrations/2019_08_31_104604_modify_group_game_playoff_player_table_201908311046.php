<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class ModifyGroupGamePlayoffPlayerTable
 */
class ModifyGroupGamePlayoffPlayerTable201908311046 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('groupGamePlayoff_player', 'deletedAt')) {
            return;
        }

        Schema::table('groupGamePlayoff_player', function (Blueprint $table) {
            $table->dropSoftDeletes('deletedAt');
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
            $table->softDeletes('deletedAt');
        });
    }
}
