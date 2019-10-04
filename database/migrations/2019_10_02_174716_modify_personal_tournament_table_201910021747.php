<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyPersonalTournamentTable201910021747 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('personalTournament', 'vk_group_id')) {
            return;
        }

        Schema::table('personalTournament', function (Blueprint $table) {
            $table->integer('vk_group_id')->nullable()->comment('Группа Турнира в ВК');
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
            $table->dropColumn('vk_group_id');
        });
    }
}
