<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyGroupTournamentTable201908311048 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('groupTournament', 'thirdPlaceSeries')) {
            return;
        }

        Schema::table('groupTournament', function (Blueprint $table) {
            $table->tinyInteger('thirdPlaceSeries')->default(0)->comment('Серия за третье место');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('groupTournament', function (Blueprint $table) {
            $table->dropColumn('thirdPlaceSeries');
        });
    }
}
