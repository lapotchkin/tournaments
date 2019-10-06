<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class ModifyPersonalGameRegularTable201910061217
 */
class ModifyPersonalGameRegularTable201910061217 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('personalGameRegular', 'sharedAt')) {
            return;
        }

        Schema::table('personalGameRegular', function (Blueprint $table) {
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
        Schema::table('personalGameRegular', function (Blueprint $table) {
            $table->dropColumn(['sharedAt']);
        });
    }
}
