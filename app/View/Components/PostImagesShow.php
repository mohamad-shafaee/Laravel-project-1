<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PostImagesShow extends Component
{
    public $urls;
    public $id;
    public $imgIds;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($urls, $id, $imgIds)
    {
        $this->urls = $urls;
        $this->id = $id;
        $this->imgIds = $imgIds;
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.post-images-show');
    }
}
