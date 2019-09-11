<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;

/**
 * Class GroupGamesCarousel
 * @package App\Widgets
 */
class GroupGamesCarousel extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'games' => [],
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        return view('widgets.group_games_carousel', $this->config);
    }
}
