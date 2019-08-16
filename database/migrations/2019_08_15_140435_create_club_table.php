<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateClubTable
 */
class CreateClubTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('club')) {
            return;
        }

        Schema::create('club', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';

            $table->string('id', 20)->comment('ID');
            $table->string('league_id', 20)->comment('ID лиги');
            $table->string('title', 255)->comment('Название');
            $table->dateTime('createdAt')->default('CURRENT_TIMESTAMP');
            $table->softDeletes('deletedAt');

            $table->primary('id');
            $table->foreign('league_id')->references('id')->on('league');
        });

        DB::table('club')->insert([
            ['id' => 'ana', 'league_id' => 'nhl', 'title' => 'Anaheim Ducks'],
            ['id' => 'ari', 'league_id' => 'nhl', 'title' => 'Arizona Coyotes'],
            ['id' => 'aut', 'league_id' => 'iihf', 'title' => 'Австрия'],
            ['id' => 'bel', 'league_id' => 'iihf', 'title' => 'Беларусь'],
            ['id' => 'bos', 'league_id' => 'nhl', 'title' => 'Boston Bruins'],
            ['id' => 'buf', 'league_id' => 'nhl', 'title' => 'Buffalo Sabres'],
            ['id' => 'can', 'league_id' => 'iihf', 'title' => 'Канада'],
            ['id' => 'car', 'league_id' => 'nhl', 'title' => 'Carolina Hurricanes'],
            ['id' => 'cbj', 'league_id' => 'nhl', 'title' => 'Columbus Blue Jacket'],
            ['id' => 'cgy', 'league_id' => 'nhl', 'title' => 'Calgary Flames'],
            ['id' => 'chi', 'league_id' => 'nhl', 'title' => 'Chicago Blackhawks'],
            ['id' => 'col', 'league_id' => 'nhl', 'title' => 'Colorado Avalanche'],
            ['id' => 'cze', 'league_id' => 'iihf', 'title' => 'Чехия'],
            ['id' => 'dal', 'league_id' => 'nhl', 'title' => 'Dallas Stars'],
            ['id' => 'den', 'league_id' => 'iihf', 'title' => 'Дания'],
            ['id' => 'det', 'league_id' => 'nhl', 'title' => 'Detroit Red Wings'],
            ['id' => 'edm', 'league_id' => 'nhl', 'title' => 'Edmonton Oilers'],
            ['id' => 'fin', 'league_id' => 'iihf', 'title' => 'Финляндия'],
            ['id' => 'fla', 'league_id' => 'nhl', 'title' => 'Florida Panthers'],
            ['id' => 'fra', 'league_id' => 'iihf', 'title' => 'Франция'],
            ['id' => 'gbr', 'league_id' => 'iihf', 'title' => 'Великобритания'],
            ['id' => 'ger', 'league_id' => 'iihf', 'title' => 'Германия'],
            ['id' => 'ita', 'league_id' => 'iihf', 'title' => 'Италия'],
            ['id' => 'jpn', 'league_id' => 'iihf', 'title' => 'Япония'],
            ['id' => 'kaz', 'league_id' => 'iihf', 'title' => 'Казахстан'],
            ['id' => 'lak', 'league_id' => 'nhl', 'title' => 'Los Angeles Kings'],
            ['id' => 'lat', 'league_id' => 'iihf', 'title' => 'Латвия'],
            ['id' => 'min', 'league_id' => 'nhl', 'title' => 'Minnesota Wild'],
            ['id' => 'mtl', 'league_id' => 'nhl', 'title' => 'Montreal Canadiens'],
            ['id' => 'njd', 'league_id' => 'nhl', 'title' => 'New Jersey Devils'],
            ['id' => 'nor', 'league_id' => 'iihf', 'title' => 'Норвегия'],
            ['id' => 'nsh', 'league_id' => 'nhl', 'title' => 'Nashville Predators'],
            ['id' => 'nyi', 'league_id' => 'nhl', 'title' => 'New York Islanders'],
            ['id' => 'nyr', 'league_id' => 'nhl', 'title' => 'New York Rangers'],
            ['id' => 'ott', 'league_id' => 'nhl', 'title' => 'Ottawa Senators'],
            ['id' => 'phi', 'league_id' => 'nhl', 'title' => 'Philadelphia Flyers'],
            ['id' => 'pit', 'league_id' => 'nhl', 'title' => 'Pittsburgh Penguins'],
            ['id' => 'pol', 'league_id' => 'iihf', 'title' => 'Польша'],
            ['id' => 'rus', 'league_id' => 'iihf', 'title' => 'Россия'],
            ['id' => 'sjs', 'league_id' => 'nhl', 'title' => 'San Jose Sharks'],
            ['id' => 'stl', 'league_id' => 'nhl', 'title' => 'St. Louis Blues'],
            ['id' => 'sui', 'league_id' => 'iihf', 'title' => 'Швейцария'],
            ['id' => 'svk', 'league_id' => 'iihf', 'title' => 'Словакия'],
            ['id' => 'swe', 'league_id' => 'iihf', 'title' => 'Швеция'],
            ['id' => 'tbl', 'league_id' => 'nhl', 'title' => 'Tampa Bay Lightning'],
            ['id' => 'tor', 'league_id' => 'nhl', 'title' => 'Toronto Maple Leafs'],
            ['id' => 'ukr', 'league_id' => 'iihf', 'title' => 'Украина'],
            ['id' => 'usa', 'league_id' => 'iihf', 'title' => 'США'],
            ['id' => 'van', 'league_id' => 'nhl', 'title' => 'Vancouver Canucks'],
            ['id' => 'vgk', 'league_id' => 'nhl', 'title' => 'Vegas Golden Knights'],
            ['id' => 'wpg', 'league_id' => 'nhl', 'title' => 'Winnipeg Jets'],
            ['id' => 'wsh', 'league_id' => 'nhl', 'title' => 'Washington Capitals'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('club');
    }
}
