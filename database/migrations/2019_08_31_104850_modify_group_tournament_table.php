<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyGroupTournamentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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
