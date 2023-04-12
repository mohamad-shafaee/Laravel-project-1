<?php

namespace Summit\Admin\View\Components;

use Illuminate\View\Component;

class AuthValidationErrors extends Component
{

    public $errors;

    public function __construct($errors)
    {
        $this->errors = $errors;
        
    }
    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('admin::components.auth-validation-errors');

    }
}
