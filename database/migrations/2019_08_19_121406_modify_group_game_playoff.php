<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class ModifyGroupGamePlayoff
 */
class ModifyGroupGamePlayoff extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('groupGamePlayoff', 'match_id')) {
            return;
        }

        Schema::table('groupGamePlayoff', function (Blueprint $table) {
            $table->string('match_id', 20)->nullable()->comment('ID матча в EASHL');
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
            $table->dropColumn(['match_id']);
        });
    }
}
