<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;

/**
 * Class PersonalHeader
 * @package App\Widgets
 */
class PersonalHeader extends AbstractWidget
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
        return view('widgets.personal_header', $this->config);
    }
}
