<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateAppsTable
 */
class CreateAppsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';

            $table->string('id', 20)
                ->comment('ID');
            $table->string('title', 255)
                ->comment('Игра');
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
        Schema::dropIfExists('app');
    }
}
