<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTeamManagementActionTable
 */
class CreateTeamManagementActionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('teamManagementAction')) {
            return;
        }

        Schema::create('teamManagementAction', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->tinyInteger('id', true);
            $table->string('title', 255)->comment('Название');
            $table->dateTime('createdAt');
            $table->softDeletes('deletedAt');
        });

        DB::table('teamManagementAction')->insert([
            ['title' => 'добавил в команду', 'createdAt' => date('Y-m-d H:i:s')],
            ['title' => 'удалил из команды', 'createdAt' => date('Y-m-d H:i:s')],
            ['title' => 'назначил капитаном', 'createdAt' => date('Y-m-d H:i:s')],
            ['title' => 'назначил ассистентом капитана', 'createdAt' => date('Y-m-d H:i:s')],
            ['title' => 'сделал обычным игроком', 'createdAt' => date('Y-m-d H:i:s')],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teamManagementAction');
    }
}
