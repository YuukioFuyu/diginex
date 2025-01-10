<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ImgUp extends Component
{
    /**
     * Create a new component instance.
     */
    public $name;

    public $old;

    public $ref;

    public function __construct($name = null, $old = null, $ref = '')
    {
        $this->name = $name;
        $this->old = $old;
        $this->ref = $ref;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.img-up');
    }
}
