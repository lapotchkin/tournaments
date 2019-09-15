<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;

/**
 * Class PersonalRegularMenu
 * @package App\Widgets
 */
class PersonalRegularMenu extends AbstractWidget
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
        return view('widgets.personal_regular_menu', $this->config);
    }
}
