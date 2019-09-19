<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyPersonalGamePlayoffTable201909181419 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('personalGamePlayoff', 'updatedAt')) {
            return;
        }

        Schema::table('personalGamePlayoff', function (Blueprint $table) {
            $table->dateTime('updatedAt')->nullable();
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
            $table->dropColumn(['updatedAt']);
        });
    }
}
