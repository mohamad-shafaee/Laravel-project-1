<?php

namespace Summit\Admin\View\Components;

use Illuminate\View\Component;

class AuthCard extends Component
{
    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('admin::components.auth-card');
    }
}
