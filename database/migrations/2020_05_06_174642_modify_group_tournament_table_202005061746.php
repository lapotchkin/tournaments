<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ModifyGroupTournamentTable202005061746 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('groupTournament', 'playoff_limit')) {
            return;
        }

        Schema::table('groupTournament', function (Blueprint $table) {
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
        Schema::table('groupTournament', function (Blueprint $table) {
            $table->dropColumn('playoff_limit');
        });
    }
}
