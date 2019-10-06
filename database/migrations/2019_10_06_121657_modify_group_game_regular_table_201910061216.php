<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class ModifyGroupGameRegularTable201910061216
 */
class ModifyGroupGameRegularTable201910061216 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('groupGameRegular', 'sharedAt')) {
            return;
        }

        Schema::table('groupGameRegular', function (Blueprint $table) {
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
        Schema::table('groupGameRegular', function (Blueprint $table) {
            $table->dropColumn(['sharedAt']);
        });
    }
}
