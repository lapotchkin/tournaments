<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class ModifyGroupGamePlayoffPlayerTable
 */
class ModifyGroupGamePlayoffPlayerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('groupGamePlayoff_player', function (Blueprint $table) {
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
        Schema::table('groupGamePlayoff_player', function (Blueprint $table) {
            $table->softDeletes('deletedAt');
        });
    }
}
