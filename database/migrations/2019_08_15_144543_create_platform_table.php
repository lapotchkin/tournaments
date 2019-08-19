<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreatePlatformTable
 */
class CreatePlatformTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('platform')) {
            return;
        }

        Schema::create('platform', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->string('id', 20)->comment('ID');
            $table->string('name', 100)->comment('Название');
            $table->string('icon', 20)->comment('Класс иконки FA для платформы');
            $table->dateTime('createdAt')->default('CURRENT_TIMESTAMP');
            $table->softDeletes('deletedAt');

            $table->primary('id');
        });

        DB::table('platform')->insert([
            ['id' => 'playstation4', 'title' => 'PlayStation 4', 'icon' => 'playstation'],
            ['id' => 'xboxone', 'title' => 'Xbox One', 'icon' => 'xbox'],
        ]);
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
