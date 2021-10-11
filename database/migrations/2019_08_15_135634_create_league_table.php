<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateLeagueTable
 */
class CreateLeagueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('league')) {
            return;
        }

        Schema::create('league', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->string('id', 20)->comment('ID');
            $table->string('title', 255)->comment('Название');
            $table->dateTime('createdAt');
            $table->softDeletes('deletedAt');

            $table->primary('id');
        });

        DB::table('league')->insert([
            ['id' => 'iihf', 'title' => 'IIHF', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'nhl', 'title' => 'NHL', 'createdAt' => date('Y-m-d H:i:s')],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('league');
    }
}
