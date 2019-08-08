<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;

/**
 * Class GroupRegularMenu
 * @package App\Widgets
 */
class GroupRegularMenu extends AbstractWidget
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
        return view('widgets.group_regular_menu', $this->config);
    }
}
