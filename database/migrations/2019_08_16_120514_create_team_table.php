<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTeamTable
 */
class CreateTeamTable extends Migration
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

        Schema::create('team', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->integerIncrements('id');
            $table->string('platform_id', 20)->comment('ID платформы');
            $table->string('name', 255)->comment('Название');
            $table->dateTime('createdAt');
            $table->softDeletes('deletedAt');
        });

        Schema::table('club', function (Blueprint $table) {
            $table->foreign('platform_id')->references('id')->on('platform');
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
        });
        Schema::dropIfExists('team');
    }
}
