<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PostSlideShow extends Component
{
    public $urls;
    public $id;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($urls, $id)
    {
        $this->urls = $urls;
        $this->id = $id;
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.post-slide-show');
    }
}
