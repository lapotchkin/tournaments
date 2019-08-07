<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;

/**
 * Class GroupMenu
 * @package App\Widgets
 */
class GroupMenu extends AbstractWidget
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
        return view('widgets.group_menu', $this->config);
    }
}
