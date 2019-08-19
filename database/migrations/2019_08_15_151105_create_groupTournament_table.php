<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupTournamentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('groupTournament')) {
            return;
        }

        Schema::create('groupTournament', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->integerIncrements('id')->comment('ID');
            $table->string('platform_id', 20)->comment('ID платформы');
            $table->string('app_id', 20)->comment('ID игры');
            $table->string('title', 255)->comment('Название');
            $table->tinyInteger('playoff_rounds')->nullable()->comment('Количество раундов плейофф');
            $table->tinyInteger('min_players')->nullable()->comment('Минимальное количество игроков в команде');
            $table->dateTime('createdAt');
            $table->softDeletes('deletedAt');
        });

        Schema::table('club', function (Blueprint $table) {
            $table->foreign('platform_id')->references('id')->on('platform');
            $table->foreign('app_id')->references('id')->on('app');
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
        });
        Schema::dropIfExists('groupTournament');
    }
}
