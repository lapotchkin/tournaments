<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyPlayerPositionTable201909191104 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('player_position', 'ea_id')) {
            return;
        }

        Schema::table('player_position', function (Blueprint $table) {
            $table->string('ea_id', 30)->comment('ID в ЕА NHL')->nullable();
        });

        DB::table('player_position')->where('id', 0)->update(['ea_id' => 'goalie']);
        DB::table('player_position')->where('id', 1)->update(['ea_id' => 'defenseMen']);
        DB::table('player_position')->where('id', 2)->update(['ea_id' => 'unknown']);
        DB::table('player_position')->where('id', 3)->update(['ea_id' => 'leftWing']);
        DB::table('player_position')->where('id', 4)->update(['ea_id' => 'center']);
        DB::table('player_position')->where('id', 5)->update(['ea_id' => 'rightWing']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('player_position', function (Blueprint $table) {
            $table->dropColumn(['ea_id']);
        });
    }
}
