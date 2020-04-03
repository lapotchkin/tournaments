<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyPersonalTournamentPlayerTable202002281145 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('personalTournament_player', 'delete_reason')) {
            return;
        }

        Schema::table('personalTournament_player', function (Blueprint $table) {
            $table->string('delete_reason')->nullable()->comment('Причина удаления с турнира');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('personalTournament_player', function (Blueprint $table) {
            $table->dropColumn('delete_reason');
        });
    }
}
