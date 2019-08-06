<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlatformsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platform', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';

            $table->string('id', 20)
                ->comment('ID');
            $table->string('name', 100)
                ->comment('Название');
            $table->string('icon', 100)
                ->comment('FA Класс иконки');
            $table->dateTime('createdAt')
                ->comment('Дата создания')
                ->useCurrent();
            $table->dateTime('deletedAt')
                ->comment('Дата удаления')
                ->nullable(true);

            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('platform');
    }
}
