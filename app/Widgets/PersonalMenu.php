<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;

/**
 * Class PersonalMenu
 * @package App\Widgets
 */
class PersonalMenu extends AbstractWidget
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
        return view('widgets.personal_menu', $this->config);
    }
}
