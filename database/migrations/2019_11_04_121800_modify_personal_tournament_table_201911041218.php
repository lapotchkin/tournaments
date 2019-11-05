<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyPersonalTournamentTable201911041218 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('personalTournament', 'startedAt')) {
            return;
        }

        Schema::table('personalTournament', function (Blueprint $table) {
            $table->dateTime('startedAt')->nullable()->comment('Дата начала турнира');
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
            $table->dropColumn('startedAt');
        });
    }
}
