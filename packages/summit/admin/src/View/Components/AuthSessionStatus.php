<?php

namespace Summit\Admin\View\Components;

use Illuminate\View\Component;

class AuthSessionStatus extends Component
{

    public $status;

    public function __construct($status)
    {
        $this->status = $status;
        
    }
    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('admin::components.auth-session-status');
    }
}
