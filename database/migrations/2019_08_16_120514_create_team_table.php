<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('groupTournament')) {
            return;
        }

        Schema::create('team', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';

            $table->integerIncrements('id');
            $table->string('platform_id', 20)->comment('ID платформы');
            $table->string('name', 255)->comment('Название');
            $table->dateTime('createdAt')->default('CURRENT_TIMESTAMP');
            $table->softDeletes('deletedAt');

            $table->foreign('platform_id')->references('id')->on('platform');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('team');
    }
}
