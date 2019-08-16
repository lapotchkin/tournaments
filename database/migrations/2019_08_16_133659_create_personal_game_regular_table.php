<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalGameRegularTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('personalGameRegular')) {
            return;
        }

        Schema::create('personalGameRegular', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';

            $table->integerIncrements('id');
            $table->integer('tournament_id')->comment('ID турнира');
            $table->tinyInteger('round')->nullable('Тур');
            $table->integer('home_player_id')->comment('Хозяин');
            $table->integer('away_player_id')->comment('Гость');
            $table->tinyInteger('home_score')->nullable()->comment('Голы хозяина');
            $table->tinyInteger('away_score')->nullable()->comment('Голы гостя');
            $table->tinyInteger('isOvertime')->default(0)->comment('Игра завершилась в овертайме');
            $table->tinyInteger('isShootout')->default(0)->comment('Игра завершилась в серии буллитов');
            $table->tinyInteger('isTechnicalDefeat')->default(0)->comment('Игра завершилась техническим поражением');
            $table->dateTime('createdAt')->default('CURRENT_TIMESTAMP');
            $table->dateTime('playedAt')->nullable()->comment('Дата проведения игры');
            $table->dateTime('updatedAt')->nullable();
            $table->softDeletes('deletedAt');

            $table->foreign('tournament_id')->references('id')->on('personalTournament');
            $table->foreign('home_player_id')->references('id')->on('player');
            $table->foreign('away_player_id')->references('id')->on('player');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personalGameRegular');
    }
}
