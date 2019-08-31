<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class ModifyPersonalTournamentTable
 */
class ModifyPersonalTournamentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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
