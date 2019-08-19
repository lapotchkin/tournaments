<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreatePlayerPositionTable
 */
class CreatePlayerPositionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('player_position')) {
            return;
        }

        Schema::create('player_position', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->tinyInteger('id');
            $table->string('title', 100)->comment('Название');
            $table->string('short_title', 3)->comment('Краткое название');
            $table->dateTime('createdAt');
            $table->softDeletes('deletedAt');

            $table->primary('id');
        });

        DB::table('player_position')->insert([
            ['id' => 0, 'title' => 'Вратарь', 'short_title' => 'ВРТ', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 1, 'title' => 'Защитник', 'short_title' => 'З', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 2, 'title' => '???', 'short_title' => '???', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 3, 'title' => 'Левый нападающий', 'short_title' => 'ЛН', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 4, 'title' => 'Центр', 'short_title' => 'Ц', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 5, 'title' => 'Правый нападающий', 'short_title' => 'ПН', 'createdAt' => date('Y-m-d H:i:s')],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('player_position');
    }
}
