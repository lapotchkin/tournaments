<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyGroupGamePlayoffTable201911181806 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('groupGamePlayoff', 'isConfirmed')) {
            return;
        }

        Schema::table('groupGamePlayoff', function (Blueprint $table) {
            $table->tinyInteger('isConfirmed')->default(0)->comment('Результат подтверждён');
            $table->integer('added_by')->nullable()->comment('ID подтвердившей команды');
        });

        Schema::table('groupGamePlayoff', function (Blueprint $table) {
            $table->foreign('added_by')->references('id')->on('team');
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
            $table->dropForeign(['added_by']);
            $table->dropColumn(['isConfirmed']);
            $table->dropColumn(['added_by']);
        });
    }
}
