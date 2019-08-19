<?php

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
        if (Schema::hasColumn('groupGameRegular_player', 'star')) {
            return;
        }

        Schema::table('groupGameRegular_player', function (Blueprint $table) {
            $table->tinyInteger('star')
                ->nullable()
                ->after('position_id')
                ->default(0)
                ->comment('Звезда матча');
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
            $table->dropColumn([
                'star',
            ]);
        });
    }
}
