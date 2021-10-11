<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreatePlayerTable
 */
class CreatePlayerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('player')) {
            return;
        }

        Schema::create('player', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->integerIncrements('id')->comment('ID');
            $table->string('tag', 50)->comment('Тэг');
            $table->string('name', 255)->comment('Имя');
            $table->tinyInteger('role')->comment('Роль');
            $table->string('vk', 255)->nullable()->comment('Страница игрока в ВК');
            $table->string('city', 255)->nullable()->comment('Город');
            $table->decimal('lat', 12, 9)->nullable()->comment('Широта');
            $table->decimal('lon', 12, 9)->nullable()->comment('Долгота');
            $table->string('platform_id')->comment('ID платформы');
            $table->dateTime('createdAt');
            $table->softDeletes('deletedAt');

            $table->unique(['tag', 'platform_id']);
        });

        Schema::table('player', function (Blueprint $table) {
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
        Schema::table('player', function (Blueprint $table) {
            $table->dropForeign(['platform_id']);
        });
        Schema::dropIfExists('player');
    }
}
