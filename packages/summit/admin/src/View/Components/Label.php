<?php

namespace Summit\Admin\View\Components;

use Illuminate\View\Component;

class Label extends Component
{

    public $value;

    public function __construct($value)
    {
        $this->value = $value;
        
    }
    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('admin::components.label');
    }
}
