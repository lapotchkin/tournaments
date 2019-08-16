<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->collation = 'utf8_unicode_ci';

            $table->integerIncrements('id')->comment('ID');
            $table->string('tag', 50)->comment('Тэг');
            $table->string('name', 255)->comment('Имя');
            $table->tinyInteger('role')->comment('Роль');
            $table->string('vk', 255)->nullable()->comment('Страница игрока в ВК');
            $table->string('city', 255)->nullable()->comment('Город');
            $table->decimal('lat', 12, 9)->nullable()->comment('Широта');
            $table->decimal('lon', 12, 9)->nullable()->comment('Долгота');
            $table->string('platform_id')->comment('ID платформы');
            $table->dateTime('createdAt')->default('CURRENT_TIMESTAMP');
            $table->softDeletes('deletedAt');

            $table->foreign('platform_id')->references('id')->on('platform');
            $table->unique(['tag', 'platform_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('player');
    }
}
