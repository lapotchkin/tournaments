<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyPersonalGamePlayoffTable201911181806 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('personalGamePlayoff', 'isConfirmed')) {
            return;
        }

        Schema::table('personalGamePlayoff', function (Blueprint $table) {
            $table->tinyInteger('isConfirmed')->default(0)->comment('Результат подтверждён');
            $table->integer('added_by')->nullable()->comment('ID подтвердившего игрока');
        });

        Schema::table('personalGamePlayoff', function (Blueprint $table) {
            $table->foreign('added_by')->references('id')->on('player');
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
            $table->dropForeign(['added_by']);
            $table->dropColumn(['isConfirmed']);
            $table->dropColumn(['added_by']);
        });
    }
}
