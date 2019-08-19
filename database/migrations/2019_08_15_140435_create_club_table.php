<?php

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
            $table->collation = 'utf8_general_ci';

            $table->string('id', 20)->comment('ID');
            $table->string('league_id', 20)->comment('ID лиги');
            $table->string('title', 255)->comment('Название');
            $table->dateTime('createdAt');
            $table->softDeletes('deletedAt');

            $table->primary('id');
        });

        Schema::table('club', function (Blueprint $table) {
            $table->foreign('league_id')->references('id')->on('league');
        });

        DB::table('club')->insert([
            ['id' => 'ana', 'league_id' => 'nhl', 'title' => 'Anaheim Ducks', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'ari', 'league_id' => 'nhl', 'title' => 'Arizona Coyotes', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'aut', 'league_id' => 'iihf', 'title' => 'Австрия', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'bel', 'league_id' => 'iihf', 'title' => 'Беларусь', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'bos', 'league_id' => 'nhl', 'title' => 'Boston Bruins', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'buf', 'league_id' => 'nhl', 'title' => 'Buffalo Sabres', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'can', 'league_id' => 'iihf', 'title' => 'Канада', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'car', 'league_id' => 'nhl', 'title' => 'Carolina Hurricanes', 'createdAt' => date('Y-m-d H:i:s')],
            ['id'        => 'cbj',
             'league_id' => 'nhl',
             'title'     => 'Columbus Blue Jacket',
             'createdAt' => date('Y-m-d H:i:s'),
            ],
            ['id' => 'cgy', 'league_id' => 'nhl', 'title' => 'Calgary Flames', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'chi', 'league_id' => 'nhl', 'title' => 'Chicago Blackhawks', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'col', 'league_id' => 'nhl', 'title' => 'Colorado Avalanche', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'cze', 'league_id' => 'iihf', 'title' => 'Чехия', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'dal', 'league_id' => 'nhl', 'title' => 'Dallas Stars', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'den', 'league_id' => 'iihf', 'title' => 'Дания', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'det', 'league_id' => 'nhl', 'title' => 'Detroit Red Wings', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'edm', 'league_id' => 'nhl', 'title' => 'Edmonton Oilers', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'fin', 'league_id' => 'iihf', 'title' => 'Финляндия', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'fla', 'league_id' => 'nhl', 'title' => 'Florida Panthers', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'fra', 'league_id' => 'iihf', 'title' => 'Франция', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'gbr', 'league_id' => 'iihf', 'title' => 'Великобритания', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'ger', 'league_id' => 'iihf', 'title' => 'Германия', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'ita', 'league_id' => 'iihf', 'title' => 'Италия', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'jpn', 'league_id' => 'iihf', 'title' => 'Япония', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'kaz', 'league_id' => 'iihf', 'title' => 'Казахстан', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'lak', 'league_id' => 'nhl', 'title' => 'Los Angeles Kings', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'lat', 'league_id' => 'iihf', 'title' => 'Латвия', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'min', 'league_id' => 'nhl', 'title' => 'Minnesota Wild', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'mtl', 'league_id' => 'nhl', 'title' => 'Montreal Canadiens', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'njd', 'league_id' => 'nhl', 'title' => 'New Jersey Devils', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'nor', 'league_id' => 'iihf', 'title' => 'Норвегия', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'nsh', 'league_id' => 'nhl', 'title' => 'Nashville Predators', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'nyi', 'league_id' => 'nhl', 'title' => 'New York Islanders', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'nyr', 'league_id' => 'nhl', 'title' => 'New York Rangers', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'ott', 'league_id' => 'nhl', 'title' => 'Ottawa Senators', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'phi', 'league_id' => 'nhl', 'title' => 'Philadelphia Flyers', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'pit', 'league_id' => 'nhl', 'title' => 'Pittsburgh Penguins', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'pol', 'league_id' => 'iihf', 'title' => 'Польша', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'rus', 'league_id' => 'iihf', 'title' => 'Россия', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'sjs', 'league_id' => 'nhl', 'title' => 'San Jose Sharks', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'stl', 'league_id' => 'nhl', 'title' => 'St. Louis Blues', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'sui', 'league_id' => 'iihf', 'title' => 'Швейцария', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'svk', 'league_id' => 'iihf', 'title' => 'Словакия', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'swe', 'league_id' => 'iihf', 'title' => 'Швеция', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'tbl', 'league_id' => 'nhl', 'title' => 'Tampa Bay Lightning', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'tor', 'league_id' => 'nhl', 'title' => 'Toronto Maple Leafs', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'ukr', 'league_id' => 'iihf', 'title' => 'Украина', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'usa', 'league_id' => 'iihf', 'title' => 'США', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'van', 'league_id' => 'nhl', 'title' => 'Vancouver Canucks', 'createdAt' => date('Y-m-d H:i:s')],
            ['id'        => 'vgk',
             'league_id' => 'nhl',
             'title'     => 'Vegas Golden Knights',
             'createdAt' => date('Y-m-d H:i:s'),
            ],
            ['id' => 'wpg', 'league_id' => 'nhl', 'title' => 'Winnipeg Jets', 'createdAt' => date('Y-m-d H:i:s')],
            ['id' => 'wsh', 'league_id' => 'nhl', 'title' => 'Washington Capitals', 'createdAt' => date('Y-m-d H:i:s')],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('club', function (Blueprint $table) {
            $table->dropForeign(['league_id']);
        });
        Schema::dropIfExists('club');
    }
}
