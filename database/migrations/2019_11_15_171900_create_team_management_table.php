<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTeamManagementTable
 */
class CreateTeamManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('teamManagement')) {
            return;
        }

        Schema::create('teamManagement', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->integer('id', true);
            $table->integer('team_id')->comment('ID команды');
            $table->integer('manager_id')->comment('ID игрока совершившего действие');
            $table->integer('player_id')->comment('ID игрока');
            $table->tinyInteger('action_id')->comment('Действие');
            $table->dateTime('createdAt');
            $table->softDeletes('deletedAt');
        });

        Schema::table('teamManagement', function (Blueprint $table) {
            $table->foreign('team_id')->references('id')->on('team');
            $table->foreign('manager_id')->references('id')->on('player');
            $table->foreign('player_id')->references('id')->on('player');
            $table->foreign('action_id')->references('id')->on('teamManagementAction');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('teamManagement')) {
            return;
        }

        Schema::table('team_player', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropForeign(['manager_id']);
            $table->dropForeign(['player_id']);
            $table->dropForeign(['action_id']);
        });
        Schema::dropIfExists('teamManagement');
    }
}
