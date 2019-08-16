<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEaIds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('team', 'ea_id')) {
            return;
        }

        Schema::table('team', function (Blueprint $table) {
            $table->integer('ea_id')->nullable()->comment('ID команды в EA');
            $table->string('short_name', 3)->nullable()->comment('Краткое название команды');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['ea_id', 'short_name']);
        });
    }
}
