<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class ModifyGroupGameRegularPlayerTable
 */
class ModifyGroupGameRegularPlayerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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
