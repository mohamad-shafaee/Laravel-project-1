<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PostsComponent extends Component
{

    public $posts;

    //public $index;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($posts/*, $index*/)
    {
        $this->posts = $posts;
        //$this->index = $index;
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.posts-component');
    }
}
