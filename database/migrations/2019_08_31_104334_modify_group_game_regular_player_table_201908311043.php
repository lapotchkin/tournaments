<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class ModifyGroupGameRegularPlayerTable
 */
class ModifyGroupGameRegularPlayerTable201908311043 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('groupGameRegular_player', 'deletedAt')) {
            return;
        }

        Schema::table('groupGameRegular_player', function (Blueprint $table) {
            $table->dropSoftDeletes('deletedAt');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('groupGameRegular_player', function (Blueprint $table) {
            $table->softDeletes('deletedAt');
        });
    }
}
