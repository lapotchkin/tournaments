<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;

/**
 * Class GroupPlayoffMenu
 * @package App\Widgets
 */
class GroupPlayoffMenu extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'tournament' => null,
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        return view('widgets.group_playoff_menu', $this->config);
    }
}
