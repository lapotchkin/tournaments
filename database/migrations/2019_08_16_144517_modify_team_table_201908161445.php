<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddEaIds
 */
class ModifyTeamTable201908161445 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('team', 'short_name')) {
            return;
        }

        Schema::table('team', function (Blueprint $table) {
            $table->string('short_name', 3)->nullable()->comment('Краткое название команды');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('team', function (Blueprint $table) {
            $table->dropColumn(['short_name']);
        });
    }
}
