<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreatePersonalTournamentTable
 */
class CreatePersonalTournamentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('personalTournament')) {
            return;
        }

        Schema::create('personalTournament', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->integerIncrements('id');
            $table->string('platform_id', 20)->comment('ID платформы');
            $table->string('app_id', 20)->comment('ID игры');
            $table->string('league_id', 20)->comment('ID лиги');
            $table->string('title', 255)->comment('Название');
            $table->tinyInteger('playoff_rounds')->comment('Количество раундов плейофф');
            $table->dateTime('createdAt');
            $table->softDeletes('deletedAt');
        });

        Schema::table('club', function (Blueprint $table) {
            $table->foreign('platform_id')->references('id')->on('platform');
            $table->foreign('app_id')->references('id')->on('app');
            $table->foreign('league_id')->references('id')->on('league');
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
            $table->dropForeign(['platform_id']);
            $table->dropForeign(['app_id']);
            $table->dropForeign(['league_id']);
        });
        Schema::dropIfExists('personalTournament');
    }
}
