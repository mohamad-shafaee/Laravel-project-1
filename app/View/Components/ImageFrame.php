<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ImageFrame extends Component
{
    // url of the image
    public $url;

    public $alter;

    public $editable;

    // user (model) id
    //public $id;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($url, $alter, $editable)
    {
        $this->url = $url;
        //$this->id = $id;
        $this->alter = $alter;

        $this->editable = $editable;
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.image-frame');
    }
}
