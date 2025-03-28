<?php

namespace App\View\Components;

use Illuminate\View\Component;

class NotificationDropdown extends Component
{
    public function __construct()
    {
        //
    }

    public function render()
    {
        return view('components.notification-dropdown');
    }
}
