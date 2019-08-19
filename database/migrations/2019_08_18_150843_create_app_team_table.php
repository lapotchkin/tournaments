<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateAppTeamTable
 */
class CreateAppTeamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('app_team')) {
            return;
        }

        Schema::create('app_team', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->string('app_id', 20);
            $table->integer('team_id');
            $table->integer('app_team_id');

            $table->primary(['app_id', 'team_id', 'app_team_id']);
        });

        Schema::table('app_team', function (Blueprint $table) {
            $table->foreign('app_id')->references('id')->on('app');
            $table->foreign('team_id')->references('id')->on('team');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_team', function (Blueprint $table) {
            $table->dropForeign(['app_id']);
            $table->dropForeign(['team_id']);
        });
        Schema::dropIfExists('app_team');
    }
}
