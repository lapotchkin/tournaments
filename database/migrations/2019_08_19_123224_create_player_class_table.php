<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayerClassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('player_class')) {
            return;
        }

        Schema::create('player_class', function (Blueprint $table) {
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

        DB::table('player_class')->insert([
            ['id' => 0, 'title' => 'Жёсткий нападающий', 'short_title' => 'ЖСТ', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 1, 'title' => 'Разыгрывающий', 'short_title' => 'РЗГ', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 2, 'title' => 'Снайпер', 'short_title' => 'СНП', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 3, 'title' => '???', 'short_title' => '???', 'createdAt' => date('Y-m-d H:i:s')],
            [
                'id'          => 4,
                'title'       => 'Универсальный нападающий',
                'short_title' => 'УН',
                'createdAt'   => date('Y-m-d H:i:s'),
            ],
            ['id' => 5, 'title' => '???', 'short_title' => '???', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 6, 'title' => '???', 'short_title' => '???', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 7, 'title' => '???', 'short_title' => '???', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 8, 'title' => '???', 'short_title' => '???', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 9, 'title' => '???', 'short_title' => '???', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 10, 'title' => '??', 'short_title' => '???', 'createdAt' => date('Y-m-d H:i:s')],
            [
                'id'          => 11,
                'title'       => 'Оборонительный защитник',
                'short_title' => 'ОЗ',
                'createdAt'   => date('Y-m-d H:i:s'),
            ],
            ['id' => 12, 'title' => 'Атакующий защитник', 'short_title' => 'АЗ', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 13, 'title' => 'Защитник-тафгай', 'short_title' => 'ЗТФ', 'createdAt' => date('Y-m-d H:i:s')],
            [
                'id'          => 14,
                'title'       => 'Универсальный защитник',
                'short_title' => 'УЗ',
                'createdAt'   => date('Y-m-d H:i:s'),
            ],
            ['id' => 15, 'title' => '???', 'short_title' => '???', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 16, 'title' => '???', 'short_title' => '???', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 17, 'title' => '???', 'short_title' => '???', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 18, 'title' => '???', 'short_title' => '???', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 19, 'title' => '???', 'short_title' => '???', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 20, 'title' => 'Стойка', 'short_title' => 'ВСТ', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 21, 'title' => 'Смешанный вратарь', 'short_title' => 'СМШ', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 22, 'title' => '???', 'short_title' => '', 'createdAt' => date('Y-m-d H:i:s')],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('player_class');
    }
}
