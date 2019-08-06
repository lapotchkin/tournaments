<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateClubsTable
 */
class CreateClubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('club', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';

            $table->string('id', 20)
                ->comment('ID');
            $table->string('league_id', 20)
                ->comment('ID лиги');
            $table->string('title', 20)
                ->comment('Название');
            $table->dateTime('createdAt')
                ->comment('Дата создания')
                ->useCurrent();
            $table->dateTime('deletedAt')
                ->comment('Дата удаления')
                ->nullable(true);

            $table->primary('id');
            $table->foreign('league_id', 'club_league_id_fk')
                ->references('id')
                ->on('league');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('club', function (Blueprint $table) {
            $table->dropForeign('club_league_id_fk');
        });
        Schema::dropIfExists('club');
    }
}
