<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class ModifyPersonalTournamentTable
 */
class ModifyPersonalTournamentTable201908311052 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('personalTournament', 'thirdPlaceSeries')) {
            return;
        }

        Schema::table('personalTournament', function (Blueprint $table) {
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
        Schema::table('personalTournament', function (Blueprint $table) {
            $table->dropColumn('thirdPlaceSeries');
        });
    }
}
