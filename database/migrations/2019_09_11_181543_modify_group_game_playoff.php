<?php

use Illuminate\Support\Facades\Schema;
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
        if (Schema::hasColumn('groupGamePlayoff', 'isOvertime')) {
            return;
        }

        Schema::table('groupGamePlayoff', function (Blueprint $table) {
            $table->tinyInteger('isOvertime')->default(0)->comment('Игра завершилась в овертайме');
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
            $table->dropColumn(['isOvertime']);
        });
    }
}
