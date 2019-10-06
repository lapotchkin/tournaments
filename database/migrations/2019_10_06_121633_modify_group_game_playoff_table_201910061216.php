<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


/**
 * Class ModifyGroupGamePlayoffTable201910061216
 */
class ModifyGroupGamePlayoffTable201910061216 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('groupGamePlayoff', 'sharedAt')) {
            return;
        }

        Schema::table('groupGamePlayoff', function (Blueprint $table) {
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
        Schema::table('groupGamePlayoff', function (Blueprint $table) {
            $table->dropColumn(['sharedAt']);
        });
    }
}
