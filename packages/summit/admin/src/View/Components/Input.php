<?php

namespace Summit\Admin\View\Components;

use Illuminate\View\Component;

class Input extends Component
{
    public $disabled;

    public function __construct($disabled = false)
    {
        $this->disabled = $disabled;
        
    }
    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('admin::components.input');
    }
}
