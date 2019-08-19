<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class ModifyGroupGameRegularTable
 */
class ModifyGroupGameRegularTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('groupGameRegular', 'match_id')) {
            return;
        }

        Schema::table('groupGameRegular', function (Blueprint $table) {
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
        Schema::table('groupGameRegular', function (Blueprint $table) {
            $table->dropColumn(['match_id']);
        });
    }
}
