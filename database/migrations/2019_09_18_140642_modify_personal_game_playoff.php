<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyPersonalGamePlayoff extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('personalGamePlayoff', 'isOvertime')) {
            return;
        }

        Schema::table('personalGamePlayoff', function (Blueprint $table) {
            $table->tinyInteger('isOvertime')->default(0)->comment('Игра завершилась в овертайме');
            $table->tinyInteger('isShootout')->default(0)->comment('Игра завершилась по буллитам');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('personalGamePlayoff', function (Blueprint $table) {
            $table->dropColumn(['isOvertime']);
            $table->dropColumn(['isShootout']);
        });
    }
}
