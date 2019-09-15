<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;

/**
 * Class PersonalPlayoffMenu
 * @package App\Widgets
 */
class PersonalPlayoffMenu extends AbstractWidget
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
        return view('widgets.personal_playoff_menu', $this->config);
    }
}
