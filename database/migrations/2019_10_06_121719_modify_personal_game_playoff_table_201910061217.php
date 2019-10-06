<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class ModifyPersonalGamePlayoffTable201910061217
 */
class ModifyPersonalGamePlayoffTable201910061217 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('personalGamePlayoff', 'sharedAt')) {
            return;
        }

        Schema::table('personalGamePlayoff', function (Blueprint $table) {
            $table->dateTime('sharedAt')->nullable(); //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('personalGamePlayoff', function (Blueprint $table) {
            $table->dropColumn(['sharedAt']);
        });
    }
}
