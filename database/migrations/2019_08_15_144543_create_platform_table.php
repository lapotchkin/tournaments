<?php

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
            $table->dateTime('createdAt');
            $table->softDeletes('deletedAt');

            $table->primary('id');
        });

        DB::table('platform')->insert([
            [
                'id'        => 'playstation4',
                'name'      => 'PlayStation 4',
                'icon'      => 'playstation',
                'createdAt' => date('Y-m-d H:i:s'),
            ],
            [
                'id'        => 'xboxone',
                'name'      => 'Xbox One',
                'icon'      => 'xbox',
                'createdAt' => date('Y-m-d H:i:s'),
            ],
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
