<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ModifyPersonalTournamentTable202005061807 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('personalTournament', 'playoff_limit')) {
            return;
        }

        Schema::table('personalTournament', function (Blueprint $table) {
            $table->tinyInteger('playoff_limit')
                ->nullable()
                ->comment('Ручной лимит количества участников плей-офф');
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
            $table->dropColumn('playoff_limit');
        });
    }
}
